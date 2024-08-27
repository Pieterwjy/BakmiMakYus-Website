<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Product;
class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::whereIn('status', ['settlement','Paid By Cash','Pending, Payment in Cashier', 'Pending'])->get();
        return view('admin.order.admin_order')->with('orders',$orders)->with('title','Menu Pesanan');
    }

    public function history()
    {
        // $orders = Order::orderBy('created_at', 'desc')->get();
        // $orders = Order::whereNotIn('status', ['Pending', 'Pending, Payment in Cashier'])
        //         ->whereNotIn('order_status', ['Diteruskan Ke Koki'])
        //         ->orderBy('created_at', 'desc')
        //         ->get();
        $orders = Order::whereIn('status', ['Settlement', 'Expired', 'Cancelled','Paid By Cash'])
                ->where(function($query) {
                    $query->where('order_status', 'Selesai')
                          ->orWhereNull('order_status');
                })
                ->orderBy('created_at', 'desc')
                ->get();

        return view('admin.order.admin_order_history')->with('orders',$orders)->with('title','Histori Pesanan');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menu = Product::all();   
        $table = Table::orderBy('table_number')->get();
    
        if (! $table) {
            // Redirect back with an error message if the table is not found
            return redirect()->back()->with('error', 'Meja tidak ditemukan');
        }
        // return view('admin.order.admin_buat_order',compact(menu,table))->with('title','Buat Pesanan Admin');
        return view('admin.order.admin_buat_order')->with('title','Buat Pesanan Admin')->with('menu',$menu)->with('table',$table);
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
    //         return redirect()->route('admin.order.checkout', ['orderId' => $order->id]);
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

    //         return redirect()->route('admin.order.checkout', ['orderId' => $order->id]);
    //     } else {
    //         // Invalid payment type
    //         return redirect()->back()->withErrors(['error' => 'Invalid payment type']);
    //     }
    // }
    
    public function store(Request $request)
{
    $cart = json_decode($request->cart, true);

    // Validate request data
    $validatedData = $request->validate([
        'table_number' => 'required',
        'order_type' => 'required',
        'notes' => 'nullable',
        'gross_amount' => 'required'
    ]);

    // Create order record
    $order = Order::create($validatedData);

    // Iterate through cart items
    foreach ($cart as $item) {
        // Create order detail for each item
        OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'product_name' => $item['name'],
            'product_price' => $item['price'],
            'order_qty' => $item['quantity'],
        ]);

        // Deduct stock from the product
        $product = Product::find($item['id']);
        if ($product) {
            $product->product_stock -= $item['quantity'];
            $product->save();
        } else {
            // Handle case where product is not found
            // This case should ideally not happen if products are properly managed
            return redirect()->back()->withErrors(['error' => 'Product not found']);
        }
    }

    // Handle payment type
    if ($request->payment_type === 'Cash') {
        $order->status = "Pending, Payment in Cashier";
    } elseif ($request->payment_type === 'Cashless') {
        // Handle cashless payment logic
        // Example: Set up Midtrans payment
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Prepare parameters for Midtrans
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
        $order->status = "Pending"; // Update order status for cashless payment
    } else {
        // Invalid payment type
        return redirect()->back()->withErrors(['error' => 'Invalid payment type']);
    }

    // Save order with updated status
    $order->save();

    // Redirect to checkout page
    return redirect()->route('admin.order.checkout', ['orderId' => $order->id]);
}

    public function checkout($orderId)
    {
        // Retrieve the order details by orderId
        $order = Order::findOrFail($orderId);
        $order_detail = OrderDetail::where('order_id', $orderId)->get();
        $title = 'Admin || Checkout';
        // Pass the order details and Snap token to the view
        return view('admin.order.checkout', [
            'order' => $order,
            'order_detail' =>$order_detail,
            'snapToken' => $order->snap_token,
            'title' => $title, 
            // Assuming the Snap token is stored in the 'snap_token' field of the order model
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $order = Order::findOrFail($id);
        $order_detail = OrderDetail::where('order_id', $id)->get();
        $title = 'Admin || Lihat Detail Pesanan';
        return view('admin.order.admin_lihat_order', [ 
            'order' => $order,
            'order_detail' =>$order_detail,
            'title' => $title, ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Order $order)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Order $order)
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
    
    public function cancel($id)
    {
        
        $order = Order::find($id);
        
        if ($order) {
            $order->status = 'Cancelled';
            $order->save();
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function complete($id)
    {
        // Find the order by ID
        $order = Order::findOrFail($id);

        // Update the order status
        $order->order_status = 'Selesai';
        $order->cashier = auth()->user()->name;
        $order->save();

        // Return a response
        return redirect()->back();
    }

    public function pay($id)
    {
        // Find the order by ID
        $order = Order::findOrFail($id);

        // Update the order status
        $order->status = 'Paid By Cash';
        $order->order_status = 'Diteruskan Ke Koki';
        $order->cashier = auth()->user()->name;
        $order->save();

        // Return a response
        return redirect()->back();
    }

    public function payAndRedirect($id)
    {
        // Find the order by ID
        $order = Order::findOrFail($id);

        // Update the order status
        $order->status = 'Paid By Cash';
        $order->order_status = 'Diteruskan Ke Koki';
        $order->cashier = auth()->user()->name;
        $order->save();

        $successUrl = '/admin/payment/success';
        $successUrl .= '?order_id=' . $order->id;
        $successUrl .= '&transaction_status=Paid By Cash';
        // Return a response
        return redirect()->to($successUrl);
    }

    public function fetch()
    {
        $orders = Order::whereIn('status', ['settlement','Paid By Cash','Pending, Payment in Cashier','Pending'])
        ->get();
        // Return the updated data as JSON response
        return response()->json($orders);
    }
}
