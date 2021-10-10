<?php

namespace App\Services\Payments\Contracts;

abstract class AbstractProvider {

    public function __construct (RequestInterface $request)
    {
        
    }
}