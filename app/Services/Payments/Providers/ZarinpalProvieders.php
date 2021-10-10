<?php

namespace App\Services\Payments\Providers;

use App\Services\Payments\Contracts\AbstractProvider;
use App\Services\Payments\Contracts\PayableInterface;
use App\Services\Payments\Contracts\VerifiableInterface;

class ZarinpalProvieders extends AbstractProvider implements PayableInterface , VerifiableInterface
 {
    
    public function pay()
    {
        
    }

    public function verify()
    {
        
    }
}