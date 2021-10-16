<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PayRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\Payments\PaymentService;
use App\Services\Payments\Requests\IDPayRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function  pay(PayRequest $request)
    {
        // dd($request->all()) ;
        
        $validData = $request->validated();

        $addedUser = User::firstOrCreate([
            'email'   => $validData['email'],
        ],[
            'name'   => $validData['name'],
            'mobile'   => $validData['mobile'],
        ]);

        try {

            $orderItem = json_decode(Cookie::get('basket'),true);

            $products = Product::findMany(array_keys($orderItem)) ;

            $totalPrice = $products->sum( 'price' ) ;

            $ref_code = Str::random(30) ;

            $createdOrder = Order::Create([
                'amount' => $totalPrice,
                'user_id' => $addedUser->id,
                'status' => 'unpaid',
                'ref_code' => $ref_code,
            ]);

            $orderItemForCreateOrder = $products->map(function ($product){
                
                $currentProduct = $product->only('price','id') ;
                $currentProduct['product_id'] = $currentProduct['id'];
                unset($currentProduct['id']);

                return $currentProduct ;
            });
            

            $createdOrder->orderItems()->createMany($orderItemForCreateOrder->toArray());

            $randomNumber = rand(1111,9999);

            $createdPayments = Payment::create([
                'gateways' => 'idPay',
                'res_id'   => $randomNumber,
                'ref_id'   => $randomNumber,
                'order_id' => $createdOrder->id,
                'status'   => 'unpaid',
            ]);
            
            $idPayRequest = new IDPayRequest([
                'amount'    => $totalPrice,
                'user'      => $addedUser,
                'order_id'  => $ref_code,
            ]);
                
            $paymentService = new PaymentService(PaymentService::IDPAY , $idPayRequest);
            return $paymentService->pay();

        } catch (\Exception $e) {
            back()->with('failed' , $e->getMessage());
        }
        
        
    }
}
