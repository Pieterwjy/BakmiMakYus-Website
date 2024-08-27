<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-top: 20px; /* Add padding to push content down */
            background-color: #ffffff; /* Add background color */
        }
        .logo {
            max-width: 150px;
            margin-top: -70px; /* Adjust margin to pull logo up */
        }
        .container {
            max-width: 1600px; /* Adjust container width */
        }
        .table {
            width: 100%; /* Make the table fill the container width */
            font-size: 0.9rem; /* Reduce font size for compactness */
        }
        .table th, .table td {
            padding: 0.5rem; /* Reduce padding for compactness */
        }
    </style>
</head>
<body>

<!-- Header with Logo -->
<div class="container-fluid header">
    <img src="{{ public_path('logo/logo.png') }}" alt="Your Logo" class="logo">
    @if($startDate == $endDate)
        <h4 class="text-center">Laporan Transaksi {{ customDate($startDate) }}</h4>
    @else
        <h4 class="text-center">Laporan Transaksi {{ customDate($startDate) }} / {{ customDate($endDate) }}</h4>
    @endif
</div>

<!-- Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Table -->
<div class="container">
    <table class="table table-sm table-striped text-center"> <!-- Add text-center class here -->
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Tipe Pesanan</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGrossAmount = 0; // Initialize total gross amount
            @endphp
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->order_type }}</td>
                    <td>{{ customDate($order->created_at) }}</td>
                    <td>Rp.{{ number_format($order->gross_amount, 0, ',', '.') }}</td>
                </tr>
                @php
                    $totalGrossAmount += $order->gross_amount; // Add gross amount to total
                @endphp
            @endforeach
            <!-- Last row for total gross amount -->
            <tr>
                <td colspan="3" class="text-end"><b>Total Pendapatan:</b></td>
                <td><b>Rp.{{ number_format($totalGrossAmount, 0, ',', '.') }}</b></td>
            </tr>
        </tbody>
    </table>
</div>


<!-- Date and Signature -->
<div class="container">
    <div class="row justify-content-end mt-4">
        <div class="col-md-6 text-end">
            <p>Surabaya, {{ customDate(date('d F Y')) }}</p>
            <br>
            <br>
            <p>{{ auth()->user()->name }}</p>
        </div>
    </div>
</div>

<?php 
function customDate($date) {
    $months = array(
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    );

    return date('d', strtotime($date)) . ' ' . $months[date('F', strtotime($date))] . ' ' . date('Y', strtotime($date));
}
?>
</body>
</html>
