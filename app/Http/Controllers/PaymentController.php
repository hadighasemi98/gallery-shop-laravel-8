<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Payments\PaymentService;
use App\Services\Payments\Requests\IDPayRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay()
    {
        $user = User::first();
        
        $idPay = new IDPayRequest([
            'amount' => 1000,
            'user' => $user,
        ]);
        
        $pay = new PaymentService( PaymentService::IDPAY , $idPay );
        dd($pay->pay());
        
    }
}
