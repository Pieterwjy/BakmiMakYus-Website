<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cart = json_decode($request->cart, true);
        $validatedData = $request->validate([
            'table_number' => 'required',
            'order_type' => 'required',
            'notes' => 'nullable',
            'gross_amount' => 'required'
        ]);

        $order = Order::create($validatedData);

        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'product_price' => $item['price'],
                'order_qty' => $item['quantity'],
            ]);
        }

        if ($request->payment_type === 'Cash') {
            // Handle cash payment logic here
            $order->status = "Pending, Payment in Cashier";
            $order->save();
            return redirect()->route('order.checkout', ['orderId' => $order->id]);
        } elseif ($request->payment_type === 'Cashless') {
            // Handle cashless payment logic here
            // Initialize Midtrans payment
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => $order->id,
                    'gross_amount' => $order->gross_amount,
                ),
                'item_details' => array(),
            );

            // Add order details to item_details array
            foreach ($cart as $item) {
                $params['item_details'][] = array(
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                );
            }

            // Get Snap token from Midtrans
            $snap_token = \Midtrans\Snap::getSnapToken($params);
            $order->snap_token = $snap_token;
            $order->status = "Pending";
            $order->save();

            return redirect()->route('order.checkout', ['orderId' => $order->id]);
        } else {
            // Invalid payment type
            return redirect()->back()->withErrors(['error' => 'Invalid payment type']);
        }
    }

        public function checkout($orderId)
    {
        // Retrieve the order details by orderId
        $order = Order::findOrFail($orderId);
        $order_detail = OrderDetail::where('order_id', $orderId)->get();
        // Pass the order details and Snap token to the view
        return view('order.checkout', [
            'order' => $order,
            'order_detail' =>$order_detail,
            'snapToken' => $order->snap_token,
        ]);
    }

    public function updateOrderStatus(Request $request)
    {
        $orderId = $request->orderId;
        // Find the order by ID
        $order = Order::findOrFail($orderId);
        $order->order_status = 'Diteruskan Ke Koki';
        // Update the order status
        $order->status = 'Paid by Cash'; // Or any other status you want to set
        $order->save();
        // Return a response
        return response()->json(['message' => 'Order status updated successfully']);
    }


    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
    public function checkPaymentStatus($orderId)
    {
        // Retrieve the order from the database
        $order = Order::findOrFail($orderId);
        // Check the payment status
        $paymentStatus = $order->status;

        if ($paymentStatus === 'Paid By Cash') {
            $order->order_status = 'Diteruskan Ke Koki';
            $order->save();
            // Return the payment status as JSON response
            return response()->json(['status' => $paymentStatus]);
        }
        // Return the payment status as JSON response
        return response()->json(['status' => $paymentStatus]);
    }
}
