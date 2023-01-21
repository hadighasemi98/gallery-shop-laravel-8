<?php

namespace App\Services\Payments\Contracts;

use Illuminate\Support\Facades\Config;

abstract class AbstractProvider
{
    protected RequestInterface $request;
    protected $IPGData;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;

        $IPGConfig = Config::get('IPG');
        $this->IPGData = $IPGConfig[$IPGConfig['gateway']];
    }

    protected function getIPGData()
    {
        return $this->IPGData;
    }
}
