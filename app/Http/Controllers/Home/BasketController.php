<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class BasketController extends Controller
{
    private $minute = 600 ; 

    public function addToBasket($product_id)
    {
        $product = Product::findOrFail($product_id);
        $basket = json_decode(Cookie::get('basket'), true) ;

        if (!$basket) {
            $basket = [
                $product->id => [
                    'title'    => $product->title,
                    'price'    => $product->price,
                    'demo_url' => $product->demo_url
                ]
            ];
            $basket = json_encode($basket);
            Cookie::queue('basket', $basket, $this->minute);
            return back()->with('success', 'محصول به سبد خرید اضافه شد');
        }

        if (isset($basket[$product->id])) {
            return back()->with('success', 'محصول به سبد خرید اضافه شد');
        }

        # Add new one
        $basket [$product->id] = [
            'title'    => $product->title,
            'price'    => $product->price,
            'demo_url' => $product->demo_url
        ];

        Cookie::queue('basket', json_encode($basket), $this->minute);
        return back()->with('success', 'محصول به سبد خرید اضافه شد');
    }

    public function removeFromBasket($product_id)
    {
        // dd($product_id);
        $basket = json_decode(Cookie::get('basket'), true);
        if(isset($basket[$product_id])){
            unset( $basket[$product_id] );
        }
        Cookie::queue('basket', json_encode($basket), $this->minute);
        return back()->with('success', 'محصول از سبد خرید حذف شد');
    }
}
