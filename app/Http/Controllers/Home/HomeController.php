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
        $products = null;

        if($request->has('search')){
            $products = Product::where('title' , 'LIKE' , '%' . $request->input('search') . '%' )->get();
        
        }elseif (isset($request->filter) && isset($request->action)){

            $products = $this->findFilter($request->filter , $request->action, $request->value) ?? Product::all();
        
        }else{
            $products = Product::all();
        }
        
        $categories = Category::all();
        
        return view('frontend.home.all' , compact('products','categories') );
    }

    public function findFilter($class_name , $method_name , $param='' )
    {
        $baseNameSpace = 'App\Http\Controllers\Filter\\' ;
        $className = $baseNameSpace . (ucfirst($class_name) . 'Filter') ;

        if(!class_exists($className)){
            return null ;
        }
        
        if(!method_exists($className,$method_name)){
            return null ;
        }
        return $className::$method_name($param) ;
    }

}
