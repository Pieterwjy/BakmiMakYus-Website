<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class OwnerTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d');
    $endDate = Carbon::parse($endDate)->startOfDay()->format('Y-m-d');
    
    // Set default start and end dates if not provided
    $startDate = $startDate ?: Carbon::now()->subWeek()->format('Y-m-d');
    $endDate = $endDate ?: Carbon::now()->format('Y-m-d');
    
    $statuses = ['Settlement', 'Paid By Cash'];
    $order_statuses = ['Selesai'];

    \Log::info("Start Date: $startDate, End Date: $endDate");
    \Log::info(Order::whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)
        ->whereIn('status', $statuses)
        ->whereIn('order_status', $order_statuses)->toSql());

    $filteredOrders = Order::whereDate('created_at', '>=', $startDate)
    ->whereDate('created_at', '<=', $endDate)
    ->whereIn('status', $statuses)
    ->whereIn('order_status', $order_statuses)
    ->get();
    
    // Calculate total revenue from filtered orders
    $totalRevenue = $filteredOrders->sum('gross_amount');
    
    // Retrieve popular products
    $popularProducts = Product::select('products.product_name', DB::raw('COUNT(order_details.product_name) as totalOrders'))
        ->join('order_details', 'products.product_name', '=', 'order_details.product_name')
        ->whereIn('order_details.order_id', $filteredOrders->pluck('id'))
        ->groupBy('products.product_name')
        ->orderByDesc('totalOrders')
        ->limit(5)
        ->get();

    // Retrieve orders and order details

    $orders = Order::with('details')
        ->whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)
        ->whereIn('status', $statuses)
        ->whereIn('order_status', $order_statuses)
        ->get();
    // Prepare order trends data
    $orderData = [
        'labels' => [],
        'data' => [],
    ];

    for ($month = 1; $month <= 12; $month++) {
        $orderCount = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', now()->year)
            ->whereIn('status', ['settlement', 'paid by cash'])
            ->count();

        $orderData['labels'][] = date("M", mktime(0, 0, 0, $month, 1));
        $orderData['data'][] = $orderCount;
    }

    $orderTypes = Order::whereIn('id', $filteredOrders->pluck('id'))
        ->groupBy('order_type')
        ->select('order_type', DB::raw('COUNT(*) as count'))
        ->pluck('count', 'order_type');

    $orderTypeLabels = $orderTypes->keys()->toArray();
    $orderTypeData = $orderTypes->values()->toArray();

    return view('owner.transaction.owner_transaksi')
        ->with('startDate', $startDate)
        ->with('endDate', $endDate)
        ->with('totalRevenue', $totalRevenue)
        ->with('popularProducts', $popularProducts)
        ->with('orders', $orders)
        ->with('orderData', $orderData)
        ->with('orderTypeLabels', $orderTypeLabels)
        ->with('orderTypeData', $orderTypeData)
        ->with('title', 'Menu Transaksi');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
