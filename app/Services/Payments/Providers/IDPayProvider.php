<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\Contracts\AbstractProvider;
use App\Services\Payments\Contracts\PayableInterface;
use App\Services\Payments\Contracts\VerifiableInterface;

class IDPayProvider extends AbstractProvider implements PayableInterface , VerifiableInterface
 {
    
    public function pay()
    {
        dd($this->request->getAmount());
    }

    public function verify()
    {
        
    }
}