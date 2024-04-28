<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;

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
        
        // dd($request->cart);
        $validatedData = $request->validate([
            'table_number' => 'required',
            'order_type' => 'required',
            'notes' => 'nullable',
            'gross_amount' => 'required'
        ]);
        $order = Order::create($validatedData);
        // 'order_id','product_name','product_price','order_qty'
        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'product_price' => $item['price'],
                'order_qty' => $item['quantity'],
            ]);
        }


        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->id,
                'gross_amount' => $order->gross_amount,
            ),
            'item_details => array()'
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
    

            $snap_token = \Midtrans\Snap::getSnapToken($params);
            $order->snap_token = $snap_token;
            $order->status = "Pending";
            $order->save();

        // return redirect()->route('owner.table.index')->with('success', 'Order berhasil dibuat');
        // return dd($order->id);
        return redirect()->route('order.checkout', ['orderId' => $order->id]);
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
            'snapToken' => $order->snap_token, // Assuming the Snap token is stored in the 'snap_token' field of the order model
        ]);
    }

    public function updateOrderStatus(Request $request)
    {
        $orderId = $request->orderId;

        // Find the order by ID
        $order = Order::findOrFail($orderId);

        // Update the order status
        $order->status = 'Paid'; // Or any other status you want to set
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
}
