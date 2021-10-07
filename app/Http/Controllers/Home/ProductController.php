<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($product_id)
    {
        $product = Product::findOrFail($product_id);
        $similarProducts = Product::where('category_id' , $product->category_id)->take(4)->get();
        return view('frontend.home.single' , compact('product' , 'similarProducts'));
    }


}
