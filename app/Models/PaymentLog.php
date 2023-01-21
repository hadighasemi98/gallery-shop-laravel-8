<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = ['description', 'meta', 'status', 'order_id'];
    protected $connection  = 'mysqlWithOutTransaction';
}
