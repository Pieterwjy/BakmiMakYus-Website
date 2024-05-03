@extends('admin.main')

@section('container')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h1 class="text-center">Pembayaran Berhasil</h1>
                </div>

                <div class="card-body">
                    <div class="text-center">
                        <p>Pembayaran Berhasil, Terima Kasih Atas Pesanan Anda!</p>
                        <p>Mohon Menunggu, Pesanan Sedang Diproses Dan Segera Diantar Ke Meja Anda</p>
                        {{-- @if($orderId !== null)
                            <p>Order ID: {{ $orderId }}</p>
                        @endif
                        @if($statusCode !== null)
                            <p>Status Code: {{ $statusCode }}</p>
                        @endif
                        @if($transactionStatus !== null)
                        
                        <p>Transaction Status: {{ $transactionStatus }}</p>
                        @endif --}}
                    </div>
                </div>

                <div class="container checkout-container">
    {{-- <center><h1 class="checkout-title">Pesanan</h1></center> --}}

    <div class="order-details">
        <b><p class="order-id text-end">ID Pesanan: {{ $order->id }}</p>
        <p class="order-id text-end">Nomor Meja: {{ $order->table_number }}</p></b>

        <h3 class="order-details-title">Detail Pesanan</h3>

        <table class="table order-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Qty.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order_detail as $orderDetail)
                <tr>
                    <td>{{ $orderDetail->product_name }}</td>
                    <td>Rp. {{ $orderDetail->product_price }}</td>
                    <td>{{ $orderDetail->order_qty }}</td>
                    <td>Rp. {{ $orderDetail->product_price * $orderDetail->order_qty }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total-amount text-end"><b>Grand Total: Rp. {{ $order->gross_amount }}</b></p>
    </div>
    <center><h2 class="checkout-title">LUNAS</h2></center>
    <br>
            </div>
        </div>
    </div>
</div>
@endsection