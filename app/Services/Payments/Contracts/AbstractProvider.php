<?php

namespace App\Services\Payments\Contracts;

abstract class AbstractProvider {

    protected RequestInterface $request ;
    
    public function __construct ( RequestInterface $request)
    {
        $this->request = $request ;
    }
}