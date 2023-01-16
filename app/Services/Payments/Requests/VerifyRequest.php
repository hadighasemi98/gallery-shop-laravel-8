<?php

namespace App\Services\Payments\Requests;

use App\Services\Payments\Contracts\RequestInterface;

class VerifyRequest implements RequestInterface
{

    private $transactionId;
    private $order_id;

    public function __construct(array $data)
    {
        $this->transactionId = $data['transactionId'];
        $this->order_id = $data['order_id'];
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function getOrderId()
    {
        return $this->order_id;
    }
}
