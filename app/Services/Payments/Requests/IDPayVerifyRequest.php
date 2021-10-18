<?php 

namespace App\Services\Payments\Requests;

use App\Services\Payments\Contracts\RequestInterface;

class IDPayVerifyRequest implements RequestInterface{

    private $id ;
    private $order_id ;
    private $apiKey ;

    public function __construct (array $data)
    {
        $this->id       = $data['id'];
        $this->order_id = $data['order_id'];
        $this->apiKey   = $data['apiKey'];
    }

    public function getId()
    {
        return $this->id ;
    }
    
    public function getOrder_id()
    {
        return $this->order_id ;
    }

    public function getApiKey()
    {
        return $this->apiKey ;
    }
    
}
