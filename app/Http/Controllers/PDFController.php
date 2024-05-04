<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;

class PDFController extends Controller
{
    public function generatePDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $statuses = ['Settlement', 'Paid By Cash'];
        $order_statuses = ['Selesai'];

        // Generate the file name with start date and end date
        $fileName = 'Laporan_' . str_replace('-', '', $startDate) . '_' . str_replace('-', '', $endDate) . '.pdf';
        // Retrieve orders based on start and end date
        $orders = Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->whereIn('status', $statuses)
            ->whereIn('order_status', $order_statuses)
            ->get();

        // Generate PDF
        $pdf = app('dompdf.wrapper')->loadView('owner.pdf.report', compact('orders', 'startDate', 'endDate'));
        // Download the PDF with the custom file name
        return $pdf->download($fileName);
    }
}
