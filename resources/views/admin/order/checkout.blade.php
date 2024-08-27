<?php
// Check if the script is accessed via an AJAX request
if (isset($_GET['run_cron']) && $_GET['run_cron'] == '1') {
    // Path to the PHP binary and the Artisan command
    $cronCommand = '/usr/local/bin/php /home/bakmimak/public_html/artisan schedule:run >> /dev/null 2>&1';

    // Execute the command
    $output = shell_exec($cronCommand);

    // Return the result as JSON
    if ($output === null) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to execute cron job.']);
    } else {
        echo json_encode(['status' => 'success', 'message' => $output]);
    }
    exit;
}
?>
@extends('admin.main')

@section('container')
<div class="container checkout-container">
    <h1 class="checkout-title">Pesanan</h1>

    <div class="order-details">
        <b><p class="order-id text-end">ID Pesanan: {{ $order->id }}</p>
        <p class="order-id text-end">
        Nomor Meja: {{ $order->table_number == 0 ? 'Ambil Di Kasir' : $order->table_number }}
        </p>

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
    <!-- Display payment button here -->
    @if($order->status == "Pending, Payment in Cashier")
   <h3><center>Belum Lunas, Pastikan Nominal Pembayaran Sesuai.</center></h3>
            @php
                // Calculate the target time 15 minutes after the order creation time
                $createdAt = \Carbon\Carbon::parse($order->created_at);
                $targetTime = $order->created_at->addMinutes(15)->timestamp * 1000; // Convert to milliseconds for JavaScript
            @endphp
            <div id="countdown" style="font-size: 24px; text-align: center; margin-top: 10px;"></div>

            <script>
            // Set the target time from the server-side PHP
            var countDownDate = new Date({{ $targetTime }}).getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {
            // Get today's date and time
            var now = new Date().getTime();
            
            // Find the distance between now and the count down date
            var distance = countDownDate - now;
            
            // Time calculations for minutes and seconds
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Display the result in the element with id="countdown"
            document.getElementById("countdown").innerHTML ="Menunggu Pembayaran Pesanan Pelanggan Sebelum " + minutes + " Menit " + seconds + " Detik";
            
            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "EXPIRED";
                
                $.ajax({
                url: window.location.href, // URL of the current page
                method: 'GET',
                data: { run_cron: 1 },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'error') {
                        console.log("Failed to execute cron job.");
                    } else {
                        console.log(result.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
                alert("Waktu untuk pembayaran telah habis. Lakukan pemesanan kembali.");
               
                var baseUrl = "https://bakmimakyus.cloud/admin/order/create";
                
                window.location.href = baseUrl;
            }
        }, 1000);
    </script>
   <form action="{{ route('admin.order.payandredirect', ['id' => $order->id]) }}" method="POST" >
    @method('PUT')
    @csrf
    <center> <button class="btn btn-warning" type="submit" class="btn btn-danger p-0" onsubmit="return confirm('Pastikan Nominal Pembayaran Sesuai.')" id="payByCashButton">Terima Pembayaran</button></center>
    </form>
    @elseif($order->status == "Settlement")
    <h3><center>Lunas</center></h3>
    @elseif($order->status == "Paid By Cash")
    <h3><center>Lunas</center></h3>
    @elseif($order->status == "Expired")
    <h3><center>Pembayaran Kadaluarsa, Harap Pesan Kembali</center></h3>
    @elseif($order->status == "Cancelled")
    <h3><center>Pesanan Dibatalkan, Harap Pesan Kembali</center></h3>
    @else

    @php
                // Calculate the target time 15 minutes after the order creation time
                $createdAt = \Carbon\Carbon::parse($order->created_at);
                $targetTime = $order->created_at->addMinutes(15)->timestamp * 1000; // Convert to milliseconds for JavaScript
            @endphp
            <div id="countdown" style="font-size: 24px; text-align: center; margin-top: 10px;"></div>

            <script>
            // Set the target time from the server-side PHP
            var countDownDate = new Date({{ $targetTime }}).getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {
            // Get today's date and time
            var now = new Date().getTime();
            
            // Find the distance between now and the count down date
            var distance = countDownDate - now;
            
            // Time calculations for minutes and seconds
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Display the result in the element with id="countdown"
            document.getElementById("countdown").innerHTML ="Menunggu Pembayaran Pesanan Pelanggan Sebelum " + minutes + " Menit " + seconds + " Detik";
            
            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "EXPIRED";
                // Show alert message and run cron job via AJAX
            $.ajax({
                url: window.location.href, // URL of the current page
                method: 'GET',
                data: { run_cron: 1 },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'error') {
                        console.log("Failed to execute cron job.");
                    } else {
                        console.log(result.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
                // Show alert message
                alert("Waktu untuk pembayaran telah habis. Lakukan pemesanan kembali.");
                
                
                var baseUrl = "https://bakmimakyus.cloud/admin/order/create";
                
                window.location.href = baseUrl;
            }
        }, 1000);
    </script>

    <div class="payment-button">
        <center><button id="payButton" class="btn btn-success">Bayar Sekarang</button></center>
    </div>
    @endif
    <center>
        <h8>* Jika ingin membatalkan / kembali ke halaman menu, klik tombol dibawah ini. </h8><br>
    <button id="back-button" class="btn btn-danger btn-block" style="margin-top: 10px;" onclick="goBackAndRefresh();">Kembali</button>
    </center>
    <br>
<br>
<br>
<br>
</div>

<!-- Include Snap.js script with the client key -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
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

<script>

function confirmCancellation() {
    if (confirm('Apakah Anda ingin membatalkan pesanan?')) {
        // If confirmed, send a request to cancel the order
        cancelOrder();

    } else {
        
    }
}

function cancelOrder() {
    // Replace ORDER_ID with the actual order ID
    var orderId = {{$order->id}};
    
    fetch('{{ route('checkout.cancel') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ order_id: orderId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            
        } else {
            alert(data.message);
            
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function goBackAndRefresh() {
    const previousURL = document.referrer;
    var orderStatus = "{{ $order->status }}"; // Get the order status from the backend

    if (orderStatus === "Settlement" || orderStatus === "Paid By Cash") {
        
        var baseUrl = "https://bakmimakyus.cloud/admin/order";
       
        window.location.href = baseUrl;
    } else {
        if (previousURL) {
            confirmCancellation();
            setTimeout(function() {
            window.history.replaceState(null, '', previousURL); // Update history without reloading
            window.addEventListener('popstate', function() {
            location.reload(true);
            });
            location.reload(true);
        }, 1500);
           
        } else {
            confirmCancellation();
            setTimeout(function() {
            window.history.back();
            window.addEventListener('popstate', function() {
                location.reload(true);
            });
            location.reload(true);
        }, 1500);
        }
    }
}


    document.addEventListener('DOMContentLoaded', function () {
        const payByCashButton = document.getElementById('payByCashButton');

        if (payByCashButton) {
            payByCashButton.addEventListener('click', function () {
                
                updateOrderStatus();
                localStorage.removeItem('cart');
                localStorage.removeItem('customerName');
                localStorage.removeItem('notes');

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
                localStorage.removeItem('customerName');
                localStorage.removeItem('notes');
                }
                if (response.status === 'Cancelled') {
                 // Show alert message
                 alert("Pesanan Dibatalkan. Anda akan diarahkan ke halaman menu, silakan pesan kembali.");
                // Redirect to the menu page with the table number
                
                var baseUrl = "https://bakmimakyus.cloud/admin/order/create";
                
                window.location.href = baseUrl;
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
                localStorage.removeItem('customerName');
                localStorage.removeItem('notes');
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
