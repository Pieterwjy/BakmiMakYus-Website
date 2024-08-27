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
                        <p>
                            @if($order->table_number == 0)
                                <p>Mohon Menunggu, Pesanan Sedang Diproses Dan Segera Diserahkan Ke Anda</p>
                            @else
                                <p>Mohon Menunggu, Pesanan Sedang Diproses Dan Segera Diantar Ke Meja Anda</p>
                            @endif
                        </p>
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
    </div>
    <center><h2 class="checkout-title">LUNAS</h2></center>
    <br>
    <center>
        <h8>* Jika ingin kembali ke halaman menu, klik tombol dibawah ini. </h8><br>
    <button id="back-button" class="btn btn-danger btn-block" style="margin-top: 10px;" onclick="goBackAndRefresh();">Kembali</button>
    </center>
    <br>
<br>
<br>
<br>
<script>
    function goBackAndRefresh() {
    // const previousURL = document.referrer;
    // if (previousURL) {
    //     window.history.replaceState(null, '', previousURL); // Update history without reloading
    //     location.reload(true);
    // } else {
    //     window.history.back();
    //     window.addEventListener('popstate', function() {
    //         location.reload(true);
    //     });
    // }
    
                var baseUrl = "https://bakmimakyus.cloud/admin/order";
                
                window.location.href = baseUrl;
}
    </script>
    <script>
    // Check if the page was accessed via browser back button
    window.addEventListener('pageshow', function(event) {
        var historyTraversal = event.persisted || 
                               (typeof window.performance != 'undefined' && 
                                window.performance.navigation.type === 2);
        if (historyTraversal) {
            // Page was accessed via back button
            location.reload(true); // Reload the page to update the content
        }
    });
</script>
            </div>
        </div>
    </div>
</div>
@endsection