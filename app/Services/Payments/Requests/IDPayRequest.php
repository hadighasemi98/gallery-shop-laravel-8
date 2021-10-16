<?php 

namespace App\Services\Payments\Requests;

use App\Services\Payments\Contracts\RequestInterface;

class IDPayRequest implements RequestInterface{

    private $user ;
    private $amount ;
    private $order_id ;

    public function __construct (array $data)
    {
        $this->amount = $data['amount'];
        $this->user   = $data['user'];
        $this->order_id   = $data['order_id'];
    }

    public function getUser()
    {
         $this->user ;
        //  $this->phone ;
        //  $this->email ;
    }

    public function getAmount()
    {
        return $this->amount ;
    } 
    
    public function order_id()
    {
        return $this->order_id ;
    }
    
}
