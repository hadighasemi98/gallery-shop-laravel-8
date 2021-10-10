<?php

namespace App\Services\Payments;

use App\Services\Payments\Contracts\RequestInterface;
use App\Services\Payments\Exceptions\ProviderNotFindException;
use App\Services\Payments\Providers\ZarinpalProvieders;
use App\Services\Payments\Request\IDPayRequest;
use App\Services\Payments\Request\ZarinpalRequest;

class PaymentService {

    public const IDPAY = 'IDPay';
    public const ZARINPAL = 'Zarinpal';

    private string $providerName ;
    private RequestInterface $request ;

    public function __construct( string $providerName , RequestInterface $request)
    {
        $this->providerName = $providerName ;
        $this->request = $request ;
    }

    public function pay()
    {
        $this->findProviders()->pay() ;
    }

    private function findProviders()
    {
        $className = 'App\Services\Payments\Providers\\' . $this->providerName;

        if(!class_exists($className)){
            throw new ProviderNotFindException('درگاه پرداخت انتخابی پیدا نشد');
        }
        return new $className($this->request) ;

    }
}

$idPay = new IDPayRequest([
    'amount' => 1000,
    'user' => $user,
]);

$pay = new PaymentService(self::IDPAY , $idPay);
$pay->pay();

