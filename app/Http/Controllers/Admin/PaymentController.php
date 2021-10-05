<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index ()
    {
        $payments = Payment::paginate(10);
        return view('frontend.panel.payments.all' , compact('payments') );

    }
}
