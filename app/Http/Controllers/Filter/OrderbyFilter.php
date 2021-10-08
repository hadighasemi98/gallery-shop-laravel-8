<?php

namespace App\Http\Controllers\filter;

use App\Models\Product;

class OrderbyFilter {

    public static function newest()
    {
        return Product::orderby('created_at' , 'desc')->get();
    }

    public static function default()
    {
        return Product::orderby('created_at' , 'asc')->get();
    }

    public static function lowToHigh()
    {
        return Product::orderby('price' , 'asc')->get();
    }

    public static function highToLow()
    {
        return Product::orderby('price' , 'desc')->get();
    }

}