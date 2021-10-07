<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // dd(Product::where('title' , 'for home' )->get());
        $products = null;

        if($request->has('search')){
            $products = Product::where('title' , 'LIKE' , '%' . $request->input('search') . '%' )->get();
        }else {
            $products = Product::all();
        }
        
        $categories = Category::all();
        
        return view('frontend.home.all' , compact('products','categories') );
    }

}
