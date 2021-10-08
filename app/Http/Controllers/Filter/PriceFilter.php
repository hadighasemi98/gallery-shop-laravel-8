<?php

namespace App\Http\Controllers\filter;

use App\Models\Product;
use Illuminate\Http\Request;

class PriceFilter {

    public static function sort($param)
    {
        $params = (explode('to',$param));

        if(empty($params) || !is_numeric($params[0]) || !is_numeric($params[1]) ){
            return Product::all();
        }

        return Product::whereBetween('price' ,[$params[0], $params[1]] )->get();
    }

   
}