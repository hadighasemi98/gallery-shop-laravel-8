<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\Contracts\AbstractProvider;
use App\Services\Payments\Contracts\PayableInterface;
use App\Services\Payments\Contracts\VerifiableInterface;

class IDPayProvider extends AbstractProvider implements PayableInterface , VerifiableInterface
 {
    
    public function pay()
    {
      dd($this->request);
        $params = array(
            'order_id' => $this->request->getOrderID(),
            'amount'   => $this->request->getAmount(),
            'name'     => $this->request->getUser()->Name,
            'phone'    => $this->request->getUser()->phone,
            'mail'     => $this->request->getUser()->email,
            'callback' => route('payment.callback'),
          );
          
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: '.$this->request->getApiKey().' ' ,
            'X-SANDBOX: 1'
          ));
          
          $result = curl_exec($ch);
          curl_close($ch);
          
          var_dump($result);
          
    }

    public function verify()
    {
        
    }
}