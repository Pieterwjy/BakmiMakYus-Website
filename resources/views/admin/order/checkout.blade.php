@extends('admin.main')

@section('container')
<div class="container checkout-container">
    <h1 class="checkout-title">Pesanan</h1>

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
    <!-- Display payment button here -->
    @if($order->status == "Pending, Payment in Cashier")
   <h3><center>Belum Lunas, Pastikan Nominal Pembayaran Sesuai.</center></h3>
   <form action="{{ route('admin.order.payandredirect', ['id' => $order->id]) }}" method="POST" >
    @method('PUT')
    @csrf
    <center> <button class="btn btn-warning" type="submit" class="btn btn-danger p-0" onsubmit="return confirm('Pastikan Nominal Pembayaran Sesuai.')" id="payByCashButton">Terima Pembayaran</button></center>
    </form>
    @elseif($order->status == "Settlement")
    <h3><center>Lunas</center></h3>
    @elseif($order->status == "Paid By Cash")
    <h3><center>Lunas</center></h3>
    @else
    <div class="payment-button">
        <center><button id="payButton" class="btn btn-primary">Bayar Sekarang</button></center>
    </div>
    @endif
</div>

<!-- Include Snap.js script with the client key -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const payByCashButton = document.getElementById('payByCashButton');

        if (payByCashButton) {
            payByCashButton.addEventListener('click', function () {
                
                updateOrderStatus();
                localStorage.removeItem('cart');

                var baseUrl = window.location.origin;
                var successUrl = baseUrl + '/admin/payment/success';

                successUrl += '?order_id=' + orderId;
                successUrl += '&transaction_status=' + response.status;
                
                // Redirect to payment_success.blade.php
                window.location.href = successUrl;
            });
        }
    });
</script>

<script>
    // Function to check payment status periodically
    function checkPaymentStatus(orderId) {
        // Make an AJAX request to check the payment status
        $.ajax({
            url: '/check-payment-status/' + orderId,
            type: 'GET',
            success: function(response) {
                console.log(response.status);
                console.log(response.status === 'Paid by Cash' || response.status === 'Settlement');
                if (response.status === 'Paid by Cash' || response.status === 'Settlement') {
                   // Construct the correct URL dynamically
                   console.log(response.status);
                var baseUrl = window.location.origin;
                var successUrl = baseUrl + '/admin/payment/success';

                successUrl += '?order_id=' + orderId;
                successUrl += '&transaction_status=' + response.status;
                
                // Redirect to payment_success.blade.php
                window.location.href = successUrl;
                localStorage.removeItem('cart');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error checking payment status:', error);
            }
        });
    }

    $(document).ready(function() {
        // Get the order ID from the page
        var orderId = {{ $order->id }};
        
        // Check payment status every 10 seconds
        setInterval(function() {
            checkPaymentStatus(orderId);
        }, 6000); // Adjust the interval as needed
    });
</script>




<script>
    // Function to handle payment initiation when the payButton is clicked
    document.getElementById('payButton').addEventListener('click', function() {
        snap.pay("{{ $snapToken }}", {

            // Optional: Callback functions for different payment outcomes
            onSuccess: function(result) {
                // Handle successful payment
                // You may redirect the user to a success page or display a success message
                // Example: window.location.href = "<Your success page URL>";
                    // localStorage.removeItem('cart');
                    // // updateOrderStatus();
                    // window.location.href = "{{ route('payment.success') }}";
                    var baseUrl = window.location.origin;
                var successUrl = baseUrl + '/admin/payment/success';

                successUrl += '?order_id=' + orderId;
                successUrl += '&transaction_status=' + response.status;
                
                // Redirect to payment_success.blade.php
                window.location.href = successUrl;
                localStorage.removeItem('cart');
            },
            onPending: function(result) {
                // Handle pending payment
                // This callback is triggered when the payment is pending or waiting for approval
                // Example: display a message to the user indicating that the payment is pending
            },
            onError: function(result) {
                // Handle payment error
                // This callback is triggered when an error occurs during payment processing
                // Example: display an error message to the user
            }
        });
    });

    function updateOrderStatus() {
        // Send AJAX request to update order status
        var orderId = "{{ $order->id }}";
        var url = "{{ route('update.order.status') }}";

        $.ajax({
        type: "POST",
        url: url,
        data: {
            orderId: orderId
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Handle success response
            console.log(response);
        },
        error: function(error) {
            // Handle error response
            console.log(error);
        }
    });
    }

</script>
@endsection
