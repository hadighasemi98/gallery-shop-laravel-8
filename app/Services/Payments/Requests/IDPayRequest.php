<?php 

namespace App\Services\Payments\Request;

use App\Services\Payments\Contracts\RequestInterface;

class IDPayRequest implements RequestInterface{

    private $user ;
    private $amount ;

    public function __construct (array $data)
    {
        $this->amount = $data['amount'];
        $this->user   = $data['user'];
    }

    public function getUser()
    {
        return $this->user ;
    }

    public function getAmount()
    {
        return $this->amount ;
    }
    
}
