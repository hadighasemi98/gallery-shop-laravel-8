<?php

namespace App\Services\Payments;

use App\Services\Payments\Contracts\RequestInterface;
use App\Services\Payments\Exceptions\ProviderNotFindException;

class PaymentService {

    public const IDPAY = 'IDPayProvider';
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
       return $this->findProviders()->pay() ;
    }

    public function verify()
    {
       return $this->findProviders()->verify() ;
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
