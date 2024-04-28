<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;

class PaymentController extends Controller
{
    public function success()
    {
    // Pass the order detail data to the payment success view
    return view('payment.payment_success');
    }
}
