<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;

class PDFController extends Controller
{
    // public function generatePDF(Request $request)
    // {
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $statuses = ['Settlement', 'Paid By Cash'];
    //     $order_statuses = ['Selesai'];

    //     // Generate the file name with start date and end date
    //     $fileName = 'Laporan_' . str_replace('-', '', $startDate) . '_' . str_replace('-', '', $endDate) . '.pdf';
    //     // Retrieve orders based on start and end date
    //     $orders = Order::whereDate('created_at', '>=', $startDate)
    //         ->whereDate('created_at', '<=', $endDate)
    //         ->whereIn('status', $statuses)
    //         ->whereIn('order_status', $order_statuses)
    //         ->get();

    //     // Generate PDF
    //     $pdf = app('dompdf.wrapper')->loadView('owner.pdf.report', compact('orders', 'startDate', 'endDate'));
    //     // Download the PDF with the custom file name
    //     return $pdf->download($fileName);
    // }

    public function generatePDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $orderTypes = json_decode($request->input('order_type', '[]'), true);
        $statusFilter = $request->input('status_filter');
        $menuItems = json_decode($request->input('menu_items', '[]'), true);
        if (empty($orderTypes)){
            $orderTypes = ['All'];
        };
        if (empty($menuItems)){
            $menuItems = ['All'];
        }

        
        // if $orderTypes

        //if($orderType == [] || menu_items)
        // Fetch orders based on filters
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        
        if (!in_array('All', $orderTypes)) {
            $ordersQuery->whereIn('order_type', $orderTypes);
        }
        
        if ($statusFilter == 'Normal') {
           
            $ordersQuery->whereIn('status', ['Paid By Cash', 'Settlement']);
        } else {
            $ordersQuery->whereIn('status', ['Cancelled', 'Expired']);
        }

        if (!in_array('All', $menuItems)) {
            $ordersQuery->whereHas('details', function($query) use ($menuItems) {
                $query->whereIn('product_name', $menuItems);
            });
        }

        
        
        $orders = $ordersQuery->with('details')->get();
        // $pdf = PDF::loadView('pdf.report', compact('orders', 'startDate', 'endDate', 'orderTypes', 'statusFilter', 'menuItems'));
        $pdf = app('dompdf.wrapper')->loadView('owner.pdf.report', compact('orders', 'startDate', 'endDate', 'orderTypes', 'statusFilter', 'menuItems'));
        $fileName = 'Laporan_' . str_replace('-', '', $startDate) . '_' . str_replace('-', '', $endDate) . '.pdf';
        return $pdf->download($fileName);
    }



}
