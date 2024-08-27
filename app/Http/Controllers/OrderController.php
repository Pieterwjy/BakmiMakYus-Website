<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
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
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    public function checkStock(Request $request)
{
    $cart = json_decode($request->cart, true);
    $outOfStockItems = [];

    foreach ($cart as $item) {
        $product = Product::find($item['id']);
        if ($product) {
            if ($product->product_stock < $item['quantity']) {
                $outOfStockItems[] = [
                    'name' => $product->product_name,
                    'requested_quantity' => $item['quantity'],
                    'available_stock' => $product->product_stock
                ];
            }
        } else {
            $outOfStockItems[] = [
                'name' => 'Unknown Product (ID: ' . $item['id'] . ')',
                'requested_quantity' => $item['quantity'],
                'available_stock' => 0
            ];
        }
    }

    if (count($outOfStockItems) > 0) {
        return response()->json([
            'status' => 'error',
            'message' => 'Some items are out of stock',
            'items' => $outOfStockItems
        ]);
    }

    return response()->json(['status' => 'success']);
}



    public function checkoutCancel(Request $request)
    {
        $orderId = $request->input('order_id');
        
        $order = Order::find($orderId);
        
        if ($order) {
            $order->status = 'Cancelled';
            $order->save();
            return response()->json(['success' => true, 'message' => 'Order cancelled successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $cart = json_decode($request->cart, true);
    //     $validatedData = $request->validate([
    //         'table_number' => 'required',
    //         'order_type' => 'required',
    //         'notes' => 'nullable',
    //         'gross_amount' => 'required'
    //     ]);

    //     $order = Order::create($validatedData);

    //     foreach ($cart as $item) {
    //         OrderDetail::create([
    //             'order_id' => $order->id,
    //             'product_id' => $item['id'],
    //             'product_name' => $item['name'],
    //             'product_price' => $item['price'],
    //             'order_qty' => $item['quantity'],
    //         ]);
    //     }

    //     if ($request->payment_type === 'Cash') {
    //         // Handle cash payment logic here
    //         $order->status = "Pending, Payment in Cashier";
    //         $order->save();
    //         return redirect()->route('order.checkout', ['orderId' => $order->id]);
    //     } elseif ($request->payment_type === 'Cashless') {
    //         // Handle cashless payment logic here
    //         // Initialize Midtrans payment
    //         \Midtrans\Config::$serverKey = config('midtrans.serverKey');
    //         \Midtrans\Config::$isProduction = false;
    //         \Midtrans\Config::$isSanitized = true;
    //         \Midtrans\Config::$is3ds = true;

    //         $params = array(
    //             'transaction_details' => array(
    //                 'order_id' => $order->id,
    //                 'gross_amount' => $order->gross_amount,
    //             ),
    //             'item_details' => array(),
    //         );

    //         // Add order details to item_details array
    //         foreach ($cart as $item) {
    //             $params['item_details'][] = array(
    //                 'id' => $item['id'],
    //                 'name' => $item['name'],
    //                 'price' => $item['price'],
    //                 'quantity' => $item['quantity'],
    //             );
    //         }

    //         // Get Snap token from Midtrans
    //         $snap_token = \Midtrans\Snap::getSnapToken($params);
    //         $order->snap_token = $snap_token;
    //         $order->status = "Pending";
    //         $order->save();

    //         return redirect()->route('order.checkout', ['orderId' => $order->id]);
    //     } else {
    //         // Invalid payment type
    //         return redirect()->back()->withErrors(['error' => 'Invalid payment type']);
    //     }
    // }

    public function store(Request $request)
{

     // Check for any active orders with 'Pending' or 'Pending, Payment in Cashier' status
     $activeOrders = Order::where('table_number', $request->table_number)
     ->whereIn('status', ['Pending', 'Pending, Payment in Cashier'])
     ->get();

    if ($activeOrders->isNotEmpty()) {
    // If there are active orders, return an error response or prevent further action
    // return response()->json(['error' => 'There are pending orders for this table. Cannot proceed with new order.'], 400);
    return redirect()->back()->withErrors(['error' => 'Terdapat pesanan yang sedang menunggu pembayaran, harap melakukan pembayaran sebelum membuat pesanan baru, atau hubungi kasir untuk informasi lebih lanjut.']);
    }

    $cart = json_decode($request->cart, true);
    
    // Validate the request data
    $validatedData = $request->validate([
        'table_number' => 'required',
        'order_type' => 'required',
        'notes' => 'nullable',
        'gross_amount' => 'required',
        'payment_type' => 'required'
    ]);
    
    // Check stock levels
    $outOfStockItems = [];
    foreach ($cart as $item) {
        $product = Product::find($item['id']);
        if (!$product || $product->product_stock < $item['quantity']) {
            $outOfStockItems[] = [
                'name' => $product ? $product->product_name : 'Unknown Product (ID: ' . $item['id'] . ')',
                'requested_quantity' => $item['quantity'],
                'available_stock' => $product ? $product->product_stock : 0
            ];
        }
    }

    if (count($outOfStockItems) > 0) {
        return redirect()->back()->withErrors([
            'error' => 'Some items are out of stock',
            'outOfStockItems' => $outOfStockItems
        ]);
    }

    // Create the order
    $order = Order::create($validatedData);

    // Create order details and update stock
    foreach ($cart as $item) {
        $product = Product::find($item['id']);
        $product->product_stock -= $item['quantity'];
        $product->save();

        OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
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

        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->gross_amount,
            ],
            'item_details' => [],
        ];

        // Add order details to item_details array
        foreach ($cart as $item) {
            $params['item_details'][] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ];
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
    // public function show(Order $order)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Order $order)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(UpdateOrderRequest $request, Order $order)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Order $order)
    // {
    //     //
    // }
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
