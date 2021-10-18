<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\Contracts\AbstractProvider;
use App\Services\Payments\Contracts\PayableInterface;
use App\Services\Payments\Contracts\VerifiableInterface;

class IDPayProvider extends AbstractProvider implements PayableInterface , VerifiableInterface
 {
    const STATUS_OK = 100 ; 

    public function pay()
    {
        $params = array(
            'order_id' => $this->request->getOrder_id(),
            'amount'   => $this->request->getAmount(),
            'name'     => $this->request->getUser()->name,
            'phone'    => $this->request->getUser()->mobile,
            'mail'     => $this->request->getUser()->email,
            'callback' => route('payment.callback'),
          );

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: '.$this->request->getApiKey().'' ,
            'X-SANDBOX: 1'
          ));

          
          $result = curl_exec($ch);
          curl_close($ch);
          
          $result = json_decode($result , true);

          if(isset($result['error_code'])){
            throw new \InvalidArgumentException($result['error_message']);
          }

          return redirect()->away($result['link']);

    }

    public function verify()
    {
      $params = array(
        'id' => $this->request->getId(),
        'order_id' => $this->request->getOrder_id(),
      );
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment/verify');
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'X-API-KEY: '.$this->request->getApiKey().' ',
        'X-SANDBOX: 1',
      ));
      
      $result = curl_exec($ch);
      curl_close($ch);
      
      // dd(json_decode( $result, true)) ;
      $result = json_decode( $result, true) ;

      if (isset ($result['error_code'])){
        return [
          'status'   => false,
          'message'   => $result['error_message'],
        ];
      }

      if ( $result['status'] == self::STATUS_OK ){
        return [
          'status'   => true,
          'data'   => $result,
        ];
      }

      return [
        'status'   => true,
        'data'   => $result,
      ];


    }
}