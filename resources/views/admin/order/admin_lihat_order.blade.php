@extends('admin.main')
@section('container')

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
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order_detail as $orderDetail)
            <tr>
                <td>{{ $orderDetail->product_name }}</td>
                <td>Rp.{{ number_format($orderDetail->product_price, 0, ',', '.') }}</td>
                <td>{{ $orderDetail->order_qty }}</td>
                <td>Rp.{{ number_format(($orderDetail->product_price * $orderDetail->order_qty), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total-amount text-end"><b>Total Harga: Rp.{{ number_format($order->gross_amount, 0, ',', '.') }}</b></p>
    @endsection