<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CheckoutController extends Controller
{
    private $minute ;
    public function show()
    {
        $basket = json_decode(Cookie::get('basket'), true);
        if($basket){
            $totalPrice = array_sum(array_column($basket , 'price'));
        }

        $data = [
            'basket'     => $basket ,
            'totalPrice' => $totalPrice ?? 0,
        ];
        return view('frontend.home.checkout' , $data);
    }


}
