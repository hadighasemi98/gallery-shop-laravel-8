<?php

namespace App\Services\Payments\Providers;

use App\Models\PaymentLog;
use App\Services\Payments\Contracts\AbstractProvider;
use App\Services\Payments\Contracts\PayableInterface;
use App\Services\Payments\Contracts\ProviderInterface;
use App\Services\Payments\Contracts\VerifiableInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class PEPProvider extends AbstractProvider implements VerifiableInterface, PayableInterface
{

    public function pay()
    {
        $params = [
            'orderId'  => $this->request->getOrderId(),
            'amount'   => $this->request->getAmount(),
            'gateway'  => $this->request->getGateway(),
            'appId'    => $this->request->getUser()->app_id,
            'appVersion'  => $this->request->getUser()->app_version,
            'callbackUrl' => $this->getIPGData()['callBackMethod'] == 'get' ? route('payment.getCallback') : route('payment.postCallback'),
        ];
        $url = $this->getIPGData()['host'] . '/ipg';

        $pepResponse = Http::acceptJson()
            ->withToken($this->getIPGData()['auth'])
            ->post($url, $params);

        $pepResponse->throwIf($pepResponse->failed());

        $orderId = $this->request->getOrderId();
        $order = $this->request->getUser()->order->find($orderId);
        $gatewayAmount = $pepResponse['pepResponse']['amount'] / 10;

        if ($order->amount === $gatewayAmount) {
            $order->update([
                'track_id' => $pepResponse['pepResponse']['invoiceNumber'],
            ]);
        } else {
            PaymentLog::create([
                'order_id' => $this->request->getOrderId(),
                'status' => 'failed',
                'meta' => $pepResponse,
                'description' => 'مبلغ درگاه پرداخت با مبلغ سفارش مغایرت دارد',
            ]);

            throw new \InvalidArgumentException('مبلغ درگاه پرداخت با مبلغ سفارش مغایرت دارد');
        }

        PaymentLog::create([
            'order_id' => $this->request->getOrderId(),
            'status' => 'generate_link',
            'meta' => $pepResponse,
            'description' => 'لینک درگاه پرداخت ارسال شد',
        ]);

        return Response::json([
            'error' => false,
            'link' => $pepResponse['paymentGatewayUrl'],
            'order_id' => $this->request->getOrderId(),
        ]);
    }

    public function verify()
    {
        $params = array(
            'id' => $this->request->getTransactionId(),
        );
        $url = $this->getIPGData()['host'] . '/ipg/verify?id=' . $this->request->getTransactionId();

        $result = Http::acceptJson()
            ->withToken($this->getIPGData()['auth'])
            ->post($url, $params);

        $pepResponse = json_decode($result, true);

        $verifyResult = ["data" => $pepResponse, "status" => 'failed'];

        if ($pepResponse['pepResponse']['verifyPaymentResponse']['IsSuccess'] != 'true') {
            PaymentLog::create([
                'order_id' => $this->request->getOrderId(),
                'status' => 'failed',
                'meta' => $result,
                'description' => $pepResponse['pepResponse']['verifyPaymentResponse']['Message'],
            ]);
            return $verifyResult;
        }

        $verifyResult = ["data" => $pepResponse, "status" => $this->getIPGData()['verifySuccessStatus']];
        if ($pepResponse['status'] == $this->getIPGData()['verifySuccessStatus']) {
            PaymentLog::create([
                'order_id' => $this->request->getOrderId(),
                'status' => 'verified',
                'meta' => $result,
                'description' => $pepResponse['pepResponse']['verifyPaymentResponse']['Message'],
            ]);
        } else {
            $verifyResult = ["data" => $pepResponse, "status" => 'failed'];
        }

        return $verifyResult;
    }

    public function validateTransaction()
    {
        $params = [
            'id' => $this->request->getTransactionId(),
        ];
        $url = $this->getIPGData()['host'] . '/ipg/inquiry?id=' . $this->request->getTransactionId();

        $requestResult = Http::acceptJson()
            ->withToken($this->getIPGData()['auth'])
            ->post($url, $params);

        $pepResponse = (json_decode($requestResult, true));

        $payedAmount = 0;
        $isSuccess = $pepResponse['status'] == 'callback';

        if ($isSuccess) {
            PaymentLog::create([
                'order_id' => $this->request->getOrderId(),
                'status' => 'validate_transaction_success',
                'meta' => $requestResult,
                'description' => 'در انتظار پرداخت',
            ]);
            $payedAmount = $pepResponse['pepResponse']['inquiryPaymentResponse']['Amount'];
        } else {
            PaymentLog::create([
                'order_id' => $this->request->getOrderId(),
                'status' => 'validate_transaction_failed',
                'meta' => $requestResult,
                'description' => 'پرداخت ناموفق',
            ]);
        }
        return ["success" => $isSuccess, "payedAmount" => $payedAmount];
    }
}
