<?php

namespace App\Services\Payments\Contracts;

use Illuminate\Support\Facades\Config;

abstract class AbstractProvider
{

    protected RequestInterface $request;
    protected Config $IPGData;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    protected function getIPGData()
    {
        return $this->IPGData;
    }
}
