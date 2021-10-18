<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function category ()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function owner ()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem ()
    {
        return $this->hasOne(OrderItem::class);
    }
    
}
