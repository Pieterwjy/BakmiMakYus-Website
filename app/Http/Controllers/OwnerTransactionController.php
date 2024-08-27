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
    // public function index(Request $request)
    // {
    //     // Retrieve start and end dates from request
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    
    //     // Parse dates and set defaults if not provided
    //     $startDate = $startDate ? Carbon::parse($startDate)->startOfDay()->format('Y-m-d') : Carbon::now()->subWeek()->format('Y-m-d');
    //     $endDate = $endDate ? Carbon::parse($endDate)->startOfDay()->format('Y-m-d') : Carbon::now()->format('Y-m-d');
    
    //     // Default statuses and order statuses
    //     $statuses = ['Settlement', 'Paid By Cash'];
    //     $order_statuses = ['Selesai'];
    
    //     // Check for status filter
    //     $statusFilter = $request->input('status_filter', 'Normal');
    
    //     if ($statusFilter === 'Other') {
    //         $statuses = ['!=', 'Settlement', '!=', 'Paid By Cash'];
    //         $order_statuses = ['!=', 'Selesai'];
    //     }
    
    //     // Log start and end dates for debugging
    //     \Log::info("Start Date: $startDate, End Date: $endDate");
    
    //     // Fetch filtered orders based on date range, status, and order status
    //     $filteredOrders = Order::whereDate('created_at', '>=', $startDate)
    //         ->whereDate('created_at', '<=', $endDate);
    
    //     if ($statusFilter === 'Normal') {
    //         $filteredOrders->whereIn('status', ['Settlement', 'Paid By Cash'])
    //                        ->whereIn('order_status', ['Selesai']);
    //     } else {
    //         $filteredOrders->whereNotIn('status', ['Settlement', 'Paid By Cash']);
    //     }
    
    //     // Filter by order type if provided
    //     if ($request->has('order_type')) {
    //         $orderTypes = $request->input('order_type');
    
    //         // Check if 'All' is selected in order_type dropdown
    //         if (!in_array('All', $orderTypes)) {
    //             $filteredOrders->whereIn('order_type', $orderTypes);
    //         }
    //     }
    
    //     // Get filtered orders
    //     $filteredOrders = $filteredOrders->get();
    
    //     // Calculate total revenue and item quantities only if status filter is 'Normal'
    //     $totalRevenue = 0;
    //     $itemQuantities = collect();
    
    //     if ($statusFilter === 'Normal') {
    //         $totalRevenue = $filteredOrders->sum('gross_amount');
    //         $itemQuantities = $filteredOrders->flatMap->details
    //             ->groupBy('product_name')
    //             ->map(function ($details) {
    //                 return $details->sum('order_qty');
    //             })
    //             ->sortDesc();
    //     }
    
    //     // Retrieve popular products based on filtered orders
    //     $popularProducts = Product::select('products.product_name', DB::raw('COUNT(order_details.product_name) as totalOrders'))
    //         ->join('order_details', 'products.product_name', '=', 'order_details.product_name')
    //         ->whereIn('order_details.order_id', $filteredOrders->pluck('id'))
    //         ->groupBy('products.product_name')
    //         ->orderByDesc('totalOrders')
    //         ->limit(5)
    //         ->get();
    
    //     // Retrieve orders and order details for displaying in the view, filtered by selected menu item
    //     $orders = Order::with('details')
    //         ->whereDate('created_at', '>=', $startDate)
    //         ->whereDate('created_at', '<=', $endDate);
    
    //     if ($statusFilter === 'Normal') {
    //         $orders->whereIn('status', ['Settlement', 'Paid By Cash'])
    //                ->whereIn('order_status', ['Selesai']);
    //     } else {
    //         $orders->whereNotIn('status', ['Settlement', 'Paid By Cash']);
    //     }
    
    //     // Filter by order type if provided
    //     if ($request->has('order_type')) {
    //         $orderTypes = $request->input('order_type');
    
    //         // Check if 'All' is selected in order_type dropdown
    //         if (!in_array('All', $orderTypes)) {
    //             $orders->whereIn('order_type', $orderTypes);
    //         }
    //     }
    
    //     // Filter by selected menu item
    //     if ($request->has('menu_items') && !in_array('All', $request->input('menu_items', []))) {
    //         $menuItems = $request->input('menu_items');
    //         $orders->whereHas('details', function ($query) use ($menuItems) {
    //             $query->whereIn('product_name', $menuItems);
    //         });
    //     }
    
    //     // Get filtered orders with details
    //     $orders = $orders->get();
    
    //     // Prepare order trends data for chart display
    //     $orderData = [
    //         'labels' => [],
    //         'data' => [],
    //     ];
    
    //     // Loop through each month to gather order count data
    //     for ($month = 1; $month <= 12; $month++) {
    //         $orderCount = Order::whereMonth('created_at', $month)
    //             ->whereYear('created_at', now()->year);
    
    //         if ($statusFilter === 'Normal') {
    //             $orderCount->whereIn('status', ['Settlement', 'Paid By Cash']);
    //         } else {
    //             $orderCount->whereNotIn('status', ['Settlement', 'Paid By Cash']);
    //         }
    
    //         // Filter by order type if provided
    //         if ($request->has('order_type')) {
    //             $orderTypes = $request->input('order_type');
    
    //             // Check if 'All' is selected in order_type dropdown
    //             if (!in_array('All', $orderTypes)) {
    //                 $orderCount->whereIn('order_type', $orderTypes);
    //             }
    //         }
    
    //         $orderCount = $orderCount->count();
    
    //         $orderData['labels'][] = date("M", mktime(0, 0, 0, $month, 1));
    //         $orderData['data'][] = $orderCount;
    //     }
    
    //     // Retrieve order types data based on filtered orders
    //     $orderTypes = Order::whereIn('id', $filteredOrders->pluck('id'))
    //         ->groupBy('order_type')
    //         ->select('order_type', DB::raw('COUNT(*) as count'));
    
    //     // Filter by order type if provided
    //     if ($request->has('order_type')) {
    //         $orderTypes = $orderTypes->whereIn('order_type', $request->input('order_type'));
    //     }
    
    //     // Pluck order types and counts for display
    //     $orderTypes = $orderTypes->pluck('count', 'order_type');
    
    //     // Prepare order type labels and data for display
    //     $orderTypeLabels = $orderTypes->keys()->toArray();
    //     $orderTypeData = $orderTypes->values()->toArray();
    
    //     // Return view with data
    //     return view('owner.transaction.owner_transaksi')
    //         ->with('startDate', $startDate)
    //         ->with('endDate', $endDate)
    //         ->with('totalRevenue', $totalRevenue)
    //         ->with('popularProducts', $popularProducts)
    //         ->with('orders', $orders)
    //         ->with('itemQuantities', $itemQuantities)
    //         ->with('orderData', $orderData)
    //         ->with('orderTypeLabels', $orderTypeLabels)
    //         ->with('orderTypeData', $orderTypeData)
    //         ->with('title', 'Menu Transaksi')
    //         ->with('statusFilter', $statusFilter);
    // }
    
    
    
    public function index(Request $request)
    {
        // Retrieve start and end dates from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Parse dates and set defaults if not provided
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay()->format('Y-m-d') : Carbon::now()->subWeek()->startOfDay()->format('Y-m-d');
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay()->format('Y-m-d') : Carbon::now()->endOfDay()->format('Y-m-d');

        // Initialize the query
        $ordersQuery = Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);
    
        // Apply status filter
        $statusFilter = $request->input('status_filter', 'Normal');
        if ($statusFilter === 'Normal') {
            $ordersQuery->whereIn('status', ['Settlement', 'Paid By Cash']);
        } else {
            $ordersQuery->whereNotIn('status', ['Settlement', 'Paid By Cash']);
        }
    
        // Apply order type filter if 'All' is not selected
        if ($request->has('order_type')) {
            $orderTypes = $request->input('order_type');
            if (!in_array('All', $orderTypes)) {
                $ordersQuery->whereIn('order_type', $orderTypes);
            }
        }
    
        // Apply menu items filter if 'All' is not selected
        if ($request->has('menu_items') && !in_array('All', $request->input('menu_items', []))) {
            $menuItems = $request->input('menu_items');
            $ordersQuery->whereHas('details', function ($query) use ($menuItems) {
                $query->whereIn('product_name', $menuItems);
            });
        }
    
        // Get filtered orders with details
        $orders = $ordersQuery->with('details')->get();
    
        // Calculate total revenue
        
        if ($statusFilter === 'Normal'){
            $totalRevenue = $orders->sum('gross_amount');
        } else{
            $totalRevenue = 0;
        }

        if ($statusFilter === 'Normal'){
        // Calculate item quantities
        $itemQuantities = $orders->flatMap->details
            ->groupBy('product_name')
            ->map(function ($details) {
                return $details->sum('order_qty');
            })
            ->sortDesc();
        } else {
            $itemQuantities = [];
        }
        // Retrieve popular products based on filtered orders
        $popularProducts = Product::select('products.product_name', DB::raw('COUNT(order_details.product_name) as totalOrders'))
            ->join('order_details', 'products.product_name', '=', 'order_details.product_name')
            ->whereIn('order_details.order_id', $orders->pluck('id'))
            ->groupBy('products.product_name')
            ->orderByDesc('totalOrders')
            ->limit(5)
            ->get();
    
        // Prepare order trends data for chart display
        $orderData = [
            'labels' => [],
            'data' => [],
        ];
    
        // Loop through each month of the current year (from January to December)
        for ($month = 1; $month <= 12; $month++) {
            $orderCountQuery = Order::whereMonth('created_at', $month)
                                    ->whereYear('created_at', now()->year);
    
            // Apply status filter
            if ($statusFilter === 'Normal') {
                $orderCountQuery->whereIn('status', ['Settlement', 'Paid By Cash']);
            } else {
                $orderCountQuery->whereNotIn('status', ['Settlement', 'Paid By Cash']);
            }
    
            // Apply order type filter if provided
            if ($request->has('order_type')) {
                $orderTypes = $request->input('order_type');
                if (!in_array('All', $orderTypes)) {
                    $orderCountQuery->whereIn('order_type', $orderTypes);
                }
            }
    
            // Get count of orders for the current month
            $orderCount = $orderCountQuery->count();
    
            // Format month label (e.g., Jan, Feb, etc.)
            $orderData['labels'][] = date("M", mktime(0, 0, 0, $month, 1));
            $orderData['data'][] = $orderCount;
        }
    
        // Retrieve order types data based on filtered orders
        $orderTypes = Order::whereIn('id', $orders->pluck('id'))
            ->groupBy('order_type')
            ->select('order_type', DB::raw('COUNT(*) as count'));
    
        // Filter by order type if provided
        if ($request->has('order_type')) {
            $orderTypes = $orderTypes->whereIn('order_type', $request->input('order_type'));
        }
    
        // Pluck order types and counts for display
        $orderTypes = $orderTypes->pluck('count', 'order_type');
    
        // Prepare order type labels and data for display
        $orderTypeLabels = $orderTypes->keys()->toArray();
        $orderTypeData = $orderTypes->values()->toArray();
    
        // Return view with data
        return view('owner.transaction.owner_transaksi')
            ->with('startDate', $startDate)
            ->with('endDate', $endDate)
            ->with('totalRevenue', $totalRevenue)
            ->with('popularProducts', $popularProducts)
            ->with('orders', $orders)
            ->with('itemQuantities', $itemQuantities)
            ->with('orderData', $orderData)
            ->with('orderTypeLabels', $orderTypeLabels)
            ->with('orderTypeData', $orderTypeData)
            ->with('title', 'Menu Transaksi')
            ->with('statusFilter', $statusFilter);
    }
    
    
    

    




    /**
     * Show the form for creating a new resource.
     */
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
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        $order_detail = OrderDetail::where('order_id', $id)->get();
        $title = 'Owner || Lihat Detail Pesanan';
        return view('owner.transaction.owner_lihat_order', [ 
            'order' => $order,
            'order_detail' =>$order_detail,
            'title' => $title, ]);
    }

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
}
