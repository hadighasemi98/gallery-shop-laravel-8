<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PayRequest;
use App\Mail\SendOrderedImages;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\Payments\PaymentService;
use App\Services\Payments\Requests\IDPayRequest;
use App\Services\Payments\Requests\IDPayVerifyRequest;
use App\Services\Payments\Requests\VerifyRequest;
use App\Utilities\Helpers\Helper;
use App\Utilities\Helpers\PaymentHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class PaymentControllerCopy extends Controller
{
    private $IPGConfig;
    private $paymentHelper;

    public function __construct(PaymentHelper $paymentHelper)
    {
        $this->IPGConfig = Config::get('IPG');
        $this->paymentHelper = $paymentHelper;
    }

    public function pay(PayRequest $request)
    {
        try {

            $user = $this->setUser($request);

            $orderItem = json_decode(Cookie::get('basket'), true);

            if (count($orderItem) <= 0) {
                throw new \InvalidArgumentException(__('conditions.basket.empty_basket'));
            }

            $products = Product::findMany(array_keys($orderItem));

            $totalPrice = $products->sum('price');

            $ref_code = Str::random(30);

            $createdOrder = $this->setOrder($totalPrice, $user, $ref_code);

            $this->setOrderItems($products, $createdOrder);

            $this->setPayment($createdOrder, $ref_code);

            $idPayRequest = new IDPayRequest([
                'amount'    => $totalPrice,
                'user'      => $user,
                'order_id'  => $ref_code,
                'apiKey'  => config('services.gateways.id_pay.api_key'),
            ]);

            $paymentService = new PaymentService($this->IPGConfig['gateway'], $idPayRequest);
            return $paymentService->pay();
        } catch (\Exception $e) {
            return back()->with('failed', $e->getMessage());
        }
    }

    private function setUser($request)
    {

        $validData = $request->validated();

        $user = User::firstOrCreate([
            'email'   => $validData['email'],
        ], [
            'name'   => $validData['name'],
            'mobile' => $validData['mobile'],
        ]);

        return $user;
    }

    private function  setOrderItems($products, $createdOrder)
    {

        $orderItemForCreateOrder = $products->map(function ($product) {

            $currentProduct = $product->only('price', 'id');
            $currentProduct['product_id'] = $currentProduct['id'];
            unset($currentProduct['id']);

            return $currentProduct;
        });

        $createdOrder->orderItems()->createMany($orderItemForCreateOrder->toArray());
    }


    private function  setOrder($totalPrice, $user, $ref_code)
    {

        $createdOrder = Order::Create([
            'amount' => $totalPrice,
            'user_id' => $user->id,
            'status' => 'unpaid',
            'ref_code' => $ref_code,
        ]);

        return $createdOrder;
    }

    private function  setPayment($createdOrder, $ref_code)
    {

        Payment::create([
            'gateways' => 'idPay',
            'ref_code'   => $ref_code,
            'order_id' => $createdOrder->id,
            'status'   => 'unpaid',
        ]);
    }


    public function callback(Request $request)
    {
        $request = $this->paymentHelper->serializeRequest($request);

        $myOrder = Order::where('id', $request['order_id'])->first();
        $paymentSuccessStatus = $this->paymentHelper->isPaymentSuccessFull($request, $myOrder);

        if ($paymentSuccessStatus == false) {
            return $this->paymentHelper->showResultView(['status' => 'failed', 'track_id' => $request['order_id'], 'message' => __('conditions.failed_payment'), 'channel' => $myOrder->channel ?? 'app']);
        }

        $inquiryRequest = new VerifyRequest([
            'id' => $request['id'],
            'order_id' => $request['order_id'],
        ]);

        $paymentService = new PaymentService($this->IPGConfig['gateway'], $inquiryRequest);
        $inquiryResult = $paymentService->validateTransaction();

        if ($myOrder->amount * 10 != $inquiryResult['payedAmount']) {
            $this->paymentHelper->createPaymentLog('callback_data', $request['order_id'], 'دیتای برگشتی از درگاه دریافت شد', json_encode($request));
            return $this->paymentHelper->showResultView(['message' => __('payment.wrong_amount'), "track_id" => $myOrder->id, "status" => 'failed', 'channel' => $myOrder->channel]);
        }

        if ($myOrder->status != 'paid') {
            try {

                DB::beginTransaction();

                $result = $paymentService->verify();

                if (!$result['status']) {
                    DB::rollback();

                    $this->paymentHelper->createPaymentLog('callback_data', $request['order_id'], __('payment.un_verify_transaction_message'), json_encode($request));
                    return redirect()->route('home.checkout.show')->with('failed', __('payment.failed_payment'));
                }

                $currentPayment = Payment::where('ref_code', $result['data']['order_id'])->first();
                $currentPayment->update([
                    'status' => 'paid',
                    'res_id' => $result['data']['track_id']
                ]);

                $currentPayment->order()->update([
                    'status' => 'paid',
                ]);

                $orderedImages = $currentPayment->order->orderItems->map(function ($orderItem) {
                    return ($orderItem->product->source_url);
                });

                $currentUser = $currentPayment->order->user;

                Mail::to($currentUser)->send(new SendOrderedImages($currentUser, $orderedImages->toArray()));

                Cookie::queue('basket', null);
                $this->paymentHelper->createPaymentLog('fulfillment_data', $request['order_id'], __('payment.giving_order'));

                return redirect()->route('home.page')->with('success', __('payment.success_payment'));
            } catch (Exception $e) {

                $this->paymentHelper->createPaymentLog('catch_section', $request['order_id'], __('payment.failed_payment'), json_encode(["message" => $e->getMessage(), 'Line : ' => $e->getLine()]));
                DB::rollback();

                return $this->paymentHelper->showResultView(['message' => __('payment.un_verify_transaction_message'), "status" => 'failed', "track_id" => $request['order_id'], 'channel' => $myOrder->channel]);
            }
        }
    }
}
