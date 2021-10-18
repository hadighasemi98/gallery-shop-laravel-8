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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function  pay(PayRequest $request)
    {
        // dd($request->all()) ;
        
        $validData = $request->validated();

        $user = User::firstOrCreate([
            'email'   => $validData['email'],
        ],[
            'name'   => $validData['name'],
            'mobile'   => $validData['mobile'],
        ]);

        try {

            $orderItem = json_decode(Cookie::get('basket'),true);

            if(count($orderItem) <= 0){
                throw new \InvalidArgumentException('سبد خرید شما خالی میباشد');
            }

            $products = Product::findMany(array_keys($orderItem)) ;

            $totalPrice = $products->sum( 'price' ) ;

            $ref_code = Str::random(30) ;

            $createdOrder = Order::Create([
                'amount' => $totalPrice,
                'user_id' => $user->id,
                'status' => 'unpaid',
                'ref_code' => $ref_code,
            ]);

            $orderItemForCreateOrder = $products->map(function ($product){
                
                $currentProduct = $product->only('price','id') ;
                $currentProduct['product_id'] = $currentProduct['id'];
                unset($currentProduct['id']);

                return $currentProduct ;
            });
            

            $createdOrder->orderItems()->createMany($orderItemForCreateOrder->toArray());

            $randomNumber = rand(1111,9999);

            $createdPayments = Payment::create([
                'gateways' => 'idPay',
                'ref_code'   => $ref_code,
                'order_id' => $createdOrder->id,
                'status'   => 'unpaid',
            ]);
            
            $idPayRequest = new IDPayRequest([
                'amount'    => $totalPrice,
                'user'      => $user,
                'order_id'  => $ref_code,
                'apiKey'  => config('services.gateways.id_pay.api_key'),
            ]);
                
            $paymentService = new PaymentService(PaymentService::IDPAY , $idPayRequest);
            return $paymentService->pay();

        } catch (\Exception $e) {
            return back()->with('failed' , $e->getMessage());
        }
        
    }

    public function callback (Request $request)
    {
        $callbackData = $request->all() ;

        $idPayVerifyRequest = new IDPayVerifyRequest([
            'id' => $callbackData['id'],
            'order_id' => $callbackData['order_id'],
            'apiKey' => config('services.gateways.id_pay.api_key'),
        ]);

        $paymentService = new PaymentService(PaymentService::IDPAY , $idPayVerifyRequest);

        $result = $paymentService->verify() ;

        if( !$result['status'] ){
            return redirect()->route('home.checkout.show')->with('failed','پرداخت انجام نشد');
        }

        $currentPayment = Payment::where('ref_code' , $result['data']['order_id'])->first() ;
        $currentPayment->update([
            'status' => 'paid',
            'res_id' => $result['data']['track_id']
        ]);
        
        $currentPayment->order()->update([
            'status' => 'paid',
        ]);

        $orderedImages = $currentPayment->order->orderItems->map(function($orderItem){
            return ($orderItem->product->source_url);
        });

        $currentUser = $currentPayment->order->user;

        Mail::to($currentUser)->send(new SendOrderedImages($currentUser , $orderedImages->toArray() ));

        Cookie::queue('basket', null);
        return redirect()->route('home.page')->with('success' , 'باتشکر ، پرداخت شما انجام شد و تصاویر برای شما ایمیل شدند ');
    }
 
}

