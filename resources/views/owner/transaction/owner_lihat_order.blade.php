@extends('owner.main')
@section('container')

<div class="order-details">
    <b><p class="order-id text-end">ID Pesanan: {{ $order->id }}</p>
    <p class="order-id text-end">
        Nomor Meja: {{ $order->table_number == 0 ? 'Ambil Di Kasir' : $order->table_number }}
        </p></b>
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
    <h4 class="order-details-title">Catatan Pesanan</h3>
        <p>{{$order->notes}}</p>
</div>
<center>
    <h4>
        @if($order->status == 'Paid By Cash' || $order->status == 'Settlement')
            Lunas
        @elseif($order->status == 'Cancelled')
            Dibatalkan
        @elseif($order->status == 'Expired')
            Kadaluarsa
        @else
            {{ $order->status }}
        @endif
    </h4>
    
    <h8>* Jika ingin kembali ke halaman sebelumnya, klik tombol dibawah ini. </h8><br>
<button id="back-button" class="btn btn-danger btn-block" style="margin-top: 10px;" onclick="goBackAndRefresh();">Kembali</button>
</center>

<script>
    function goBackAndRefresh() {
    const previousURL = document.referrer;
 
        if (previousURL) {
            
            window.history.replaceState(null, '', previousURL); // Update history without reloading
            window.addEventListener('popstate', function() {
                location.reload(true);
            });
            location.reload(true);
        } else {
            
            window.history.back();
            window.addEventListener('popstate', function() {
                location.reload(true);
            });
            location.reload(true);
        }
}
</script>
    @endsection