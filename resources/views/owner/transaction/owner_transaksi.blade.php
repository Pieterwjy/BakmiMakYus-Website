@extends('owner.main')
@section('container')

<!-- CSS -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery UI Datepicker -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<div class="container mt-3">
    <div class="row">
        <div class="col">
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
    <div class="row mt-4">
        <div class="col">
            <form id="dateForm" action="{{ route('owner.transaction.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="start_date" class="mr-2">Dari:</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}" readonly>
                </div>
                <div class="form-group mr-3">
                    <label for="end_date" class="mr-2">Sampai:</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}" readonly>
                </div>
                <!-- Order Type Filter -->
                <div class="form-group mr-3">
                    <label for="order_type" class="mr-2">Jenis:</label>
                    <select class="form-control" id="order_type" name="order_type[]" >
                        <option value="All" {{ in_array('All', request()->input('order_type', [])) ? 'selected' : '' }}>
                            Semua
                        </option>
                        <option value="Dine-In" {{ in_array('Dine-In', request()->input('order_type', [])) ? 'selected' : '' }}>
                            Dine-In
                        </option>
                        <option value="Takeaway" {{ in_array('Takeaway', request()->input('order_type', [])) ? 'selected' : '' }}>
                            Takeaway
                        </option>
                    </select>
                </div>
                

                 <!-- Status Filter -->
                 <div class="form-group mr-3">
                    <label for="status_filter" class="mr-2">Status:</label>
                    <select class="form-control" id="status_filter" name="status_filter">
                        <option value="Normal" {{ $statusFilter == 'Normal' ? 'selected' : '' }}>Berhasil</option>
                        <option value="Other" {{ $statusFilter == 'Other' ? 'selected' : '' }}>Batal & Kadaluarsa</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Terapkan</button>
           
        </div>
        <div class="row mt-4">
            <div class="col">
                <!-- Menu Item Filter -->
                <div class="form-group mr-3">
                    <label for="menu_items" class="mr-2">Menu Terpilih:</label>
                    <select class="form-control" id="menu_items" name="menu_items[]" multiple>
                        <option value="All" {{ in_array('All', request()->input('menu_items', [])) ? 'selected' : '' }}>
                            Semua
                        </option>
                        @foreach ($popularProducts as $product)
                            <option value="{{ $product->product_name }}" {{ in_array($product->product_name, request()->input('menu_items', [])) ? 'selected' : '' }}>
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </form>
    </div>
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                @if($startDate == $endDate)
                    <div class="card-header"><h3 class="mb-0 text-center">Laporan Transaksi {{ $startDate }}</h3></div>
                @else
                    <div class="card-header"><h3 class="mb-0 text-center">Laporan Transaksi {{ $startDate }} - {{ $endDate }}</h3></div>
                @endif

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3>Total Pendapatan</h3>
                            <p>Total Pendapatan: Rp.{{ number_format($totalRevenue, 0, ',', '.') }}</p>
                            <hr>
                            <h3>Menu Populer</h3>
                            <ul>
                                @foreach($popularProducts as $product)
                                    <li>{{ $product->product_name }} - {{ $product->totalOrders }} Pesanan</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col">
                            <h3 class="text-center">Jumlah Transaksi Setiap Bulan</h3>
                            <div class="chart-container">
                                <canvas id="orderChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <h3 class="mb-0">Jumlah Item Terjual</h3>
                            @if(count($itemQuantities) > 0)
                                <ul>
                                    @foreach($itemQuantities as $product_name => $quantity)
                                        <li>{{ $product_name }} - {{ $quantity }} Terjual</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Tidak Ada Penjualan Item</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    {{-- <form action="{{ route('owner.generate.report') }}" method="GET" class="float-right">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <button type="submit" class="btn btn-primary">Ekspor Tabel Transaksi</button>
                    </form> --}}
                    <form id="export-form" action="{{ route('owner.generate.report') }}" method="GET" class="float-right">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <input type="hidden" name="order_type" value="{{ json_encode(request()->input('order_type', [])) }}">
                        <input type="hidden" name="status_filter" value="{{ $statusFilter }}">
                        
                        <input type="hidden" name="menu_items" value="{{ json_encode(request()->input('menu_items', [])) }}">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Ekspor Tabel Transaksi</button>
                    </form>
                    
                    <h3 class="mb-0 float-center">Daftar Transaksi</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Tipe Pesanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Aksi</th>
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
                                    <td>{{ $order->created_at }}</td>
                                    <td>Rp.{{ number_format($order->gross_amount, 0, ',', '.') }}</td>
                                    <td><a href="{{ route('owner.transaction.show', $order->id)}}" type="button" class="btn btn-secondary">Rincian</a></td>
                                </tr>
                                @php
                                    $totalGrossAmount += $order->gross_amount; // Add gross amount to total
                                @endphp
                            @endforeach
                            <!-- Last row for total gross amount -->
                            @if(request()->input('status') == 'Normal')
                            <tr>
                                <td colspan="3" class="text-right"><b>Total Pendapatan:</b></td>
                                <td><b>Rp.{{ number_format($totalGrossAmount, 0, ',', '.') }}</b></td>
                                <td></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="3" class="text-right"><b>Total Pendapatan:</b></td>
                                <td><b>Rp.{{ number_format($totalRevenue, 0, ',', '.') }}</b></td>
                                <td></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <br>
                <br>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderTypeSelect = document.getElementById('order_type');
        const submitBtn = document.getElementById('submitBtn');
    
        // Function to check if any option is selected
        function checkSelection() {
            const selectedOptions = Array.from(orderTypeSelect.selectedOptions);
            if (selectedOptions.length === 0) {
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
            }
        }
    
        // Initial check on page load
        checkSelection();
    
        // Event listener for change in selection
        orderTypeSelect.addEventListener('change', checkSelection);
    });
    </script>
    
<script>
    $(function() {
        var currentDate = new Date();
        $("#start_date, #end_date").datepicker({
            dateFormat: 'yy-mm-dd', // Adjust the date format to 'yy-mm-dd'
            changeMonth: true,
            changeYear: true,
            yearRange: "2020:{{ date('Y') }}", // Set the year range
            maxDate: currentDate,
        });

        $('#dateForm').submit(function(e) {
            var startDate = $('#start_date').datepicker('getDate');
            var endDate = $('#end_date').datepicker('getDate');

            if (startDate > endDate) {
                e.preventDefault();
                alert('End date must be after start date.');
            }
        });
    });
</script>
<script>
    // Order Chart Data
    var orderData = {!! json_encode($orderData) !!};
    
    // Create Order Chart
    var ctx = document.getElementById('orderChart').getContext('2d');
    var orderChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: orderData.labels,
            datasets: [{
                label: 'Pesanan Berdasarkan Status Pada Filter',
                data: orderData.data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
