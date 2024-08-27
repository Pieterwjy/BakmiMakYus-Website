<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderDetail;
class CookController extends Controller
{

    public function fetch()
    {
        $orders = Order::whereIn('order_status',['Diteruskan Ke Koki'])
        ->get();
        $order_details = OrderDetail::all();

        $data = [];
        foreach ($orders as $order) {
            $orderData = [
                'id' => $order->id,
                'order_status' => $order->order_status,
                'order_type' => $order->order_type,
                'notes' => $order->notes,
                'details' => []
            ];
    
            // Get order details for this order
            $details = $order_details->where('order_id', $order->id);
    
            foreach ($details as $detail) {
                    $orderData['details'][] = [
                    'product_name' => $detail->product_name,
                    'product_price' => $detail->product_price,
                    'order_qty' => $detail->order_qty,
                ];
            }
            $data[] = $orderData;
        }
        // Return data as JSON response
        return response()->json($data);
    }


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

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }

    public function CookLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
