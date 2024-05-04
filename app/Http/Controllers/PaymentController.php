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
        $orderId = request('order_id');
        $statusCode = request('status_code');
        $transactionStatus = request('transaction_status');
        $order = Order::findOrFail($orderId);
        $order_detail = OrderDetail::where('order_id', $orderId)->get();        
    // Pass the order detail data to the payment success view
    return view('payment.payment_success')
        ->with('orderId', $orderId)
        ->with('statusCode', $statusCode)
        ->with('transactionStatus', $transactionStatus)
        ->with('order', $order)
        ->with('order_detail', $order_detail);
    }
    public function adminSuccess()
    {
        $orderId = request('order_id');
        $statusCode = request('status_code');
        $transactionStatus = request('transaction_status');
        $order = Order::findOrFail($orderId);
        $order_detail = OrderDetail::where('order_id', $orderId)->get();        
    // Pass the order detail data to the payment success view
    return view('admin.order.payment_success')
    ->with('orderId', $orderId)
        ->with('statusCode', $statusCode)
        ->with('transactionStatus', $transactionStatus)
        ->with('order', $order)
        ->with('order_detail', $order_detail)
        ->with('title','Admin Payment Success');
    }
}
