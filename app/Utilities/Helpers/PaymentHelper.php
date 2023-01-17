<?php

namespace App\Utilities;

use App\Utilities\Helpers\Contracts\HelperInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class PaymentHelper implements HelperInterface
{

    private $IPGConfig;

    public function __construct()
    {
        $this->IPGConfig = Config::get('IPG');
    }

    /**
     * It takes a request object and returns an array of the request's keys and values, where the keys
     * are in snake case
     * 
     * @param Request The request object
     * 
     * @return The request is being serialized to snake case.
     */
    public function serializeRequest(Request $request)
    {
        $requestKeys = [];
        foreach ($request->all() as $key => $value) {
            $key = Helper::serializeToSnackCase($key);
            $requestKeys[$key] = $value;
        }

        return $requestKeys;
    }


    public function createPaymentLog(string $status, int $orderId, $description = null, $metaData = null)
    {
        return PaymentLog::create([
            'order_id' => $orderId,
            'status'  => $status,
            'meta' => json_encode($metaData),
            'description' => $description,
        ]);
    }

    public function isPaymentSuccessFull($request, $order)
    {
        $paymentSuccessStatus = $this->IPGConfig['gateway']['callbackSuccessStatus'];

        $this->createPaymentLog('callback_data', $request['order_id'], 'دیتای برگشتی از درگاه دریافت شد', json_encode($request));

        if (!$order) {
            return false;
        }

        if ($request['status'] != $paymentSuccessStatus) {
            $this->createPaymentLog('unexpected_status', $request['order_id'], 'status_code:' . $request['status'] . ' پرداخت انجام نشد', json_encode($request));

            $order->update([
                'status' => 'error',
                'meta' => json_encode($request),
                'track_id' => $request['order_id'] ?? null,
                'ref_id' => $request['tr_id'],
                'payment_time' => now(),
            ]);

            return false;
        }

        return true;
    }

    /**
     * It returns the callback view with appropriate message & data
     * 
     * @param data The data that you want to pass to the view.
     * 
     * @return The view is being returned.
     */
    public function showResultView($data)
    {
        return view('Payments.ReturnToApp', compact('data'));
    }
}
