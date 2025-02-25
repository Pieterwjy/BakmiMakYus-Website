{{-- @extends('table.main')

@section('container')
<div class="row">
    @foreach($menu as $item)
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <img src="{{ Storage::url($item->images) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="...">
            <div class="card-body">
                <h5 class="card-title">{{ $item->product_name }}</h5>
                <p class="card-text">{{ $item->product_description }}</p>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <p class="mb-0">Price: Rp.{{ $item->product_price }}</p>
                <button class="btn btn-danger btn-sm">Tambah ke Keranjang</button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection --}}


@extends('table.main')

@section('container')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"  />

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <center><h5>{{ $error }}</h5></center>
            @endforeach
        </ul>
    </div>
@endif


<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Added modal-lg class for large modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Keranjang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="cartItems">
                <!-- Cart items will be displayed here -->
                <form id="orderForm"action="{{ route('order.store', ['table' => $table->table_number]) }} " method="POST">
                    @csrf
                <input type="hidden" name="cart" id="cartData">
               <b>Nomor Meja : {{ $table->table_number == 0 ? 'Ambil Di Kasir' : $table->table_number }}</b> 
               <input type="hidden" id="table_number" name="table_number" value="{{$table->table_number}}">
                <div id="cart"></div>
                <b><div id="grandTotalLabel" style="text-align: right;"></div>
                    <input type="hidden" id="gross_amount" name="gross_amount">
                <div style="text-align: right;">
                    <!-- <input type="radio" id="dine-in" name="order_type" value="Dine-In" checked>
                    <label for="dine-in">Dine-In</label>
         
                    <input type="radio" id="takeaway" name="order_type" value="Takeaway">
                    <label for="takeaway">Takeaway</label> -->
                    <input type="radio" id="dine-in" name="order_type" value="Dine-In" {{ $table->table_number == 0 ? 'disabled' : ($table->table_number != 0 ? 'checked' : '') }}>
                    <label for="dine-in">Dine-In</label>

                    <input type="radio" id="takeaway" name="order_type" value="Takeaway" {{ $table->table_number == 0 ? 'checked' : '' }}>
                    <label for="takeaway">Takeaway</label>

                    </div>
                    <div style="text-align: right;">
                        <input type="radio" id="cash" name="payment_type" value="Cash" checked>
                        <label for="cash">Tunai</label>
             
                        <input type="radio" id="cashless" name="payment_type" value="Cashless">
                        <label for="cashless">Non Tunai</label>
                        </div></b>
                        <div class="form-group">
                            <label for="customerName">Nama:</label>
                            <input type="text" class="form-control" id="customerName" name="customer_name" placeholder="Nama Pelanggan" required>
                        </div>
                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Catatan Khusus"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-danger" id="checkoutBtn" disabled>Buat Pesanan</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pesananModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Daftar Pesanan Meja {{$table->table_number}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Status Pesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->status }}<br>{{ $order->order_status }} </td>
                                <td>
                                @if(in_array($order->status, ["Pending", "Pending, Payment in Cashier"]))
                                    <button type="button" class="btn btn-danger" onclick="redirectToCheckout('{{ $order->id }}')">Detail</button>
                                    @else
                                    <button type="button" class="btn btn-danger" onclick="redirectToPaymentSuccess('{{ $order->id }}')">Detail</button>
                                @endif
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // document.getElementById('orderForm').addEventListener('submit', function(event) {
        //     // Get values from inputs
        //     var customerName = document.getElementById('customerName').value.trim();
        //     var notes = document.getElementById('notes').value.trim();

        //     // Merge values into notes textarea
        //     var mergedNotes = "a.n. " + customerName + ", \n" + notes;

        //     // Update textarea value
        //     document.getElementById('notes').value = mergedNotes;
        // });

          // Retrieve stored values from local storage on page load
        if (localStorage.getItem('customerName')) {
            document.getElementById('customerName').value = localStorage.getItem('customerName');
        }
        if (localStorage.getItem('notes')) {
            document.getElementById('notes').value = localStorage.getItem('notes');
        }

        // Store values in local storage before submitting the form
        document.getElementById('orderForm').addEventListener('submit', function() {
            var customerName = document.getElementById('customerName').value.trim();
            var notes = document.getElementById('notes').value.trim();

            var mergedNotes = "a.n. " + customerName + ", \n" + notes;

            document.getElementById('notes').value = mergedNotes;

            localStorage.setItem('customerName', customerName);
            localStorage.setItem('notes', notes);
        });

    });
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

  <script>
    function redirectToCheckout(orderId) {
        window.location.href = "https://bakmimakyus.cloud/checkout/" + orderId;
    }
    function redirectToPaymentSuccess(orderId) {
        window.location.href = "https://bakmimakyus.cloud/payment/success?order_id=" + orderId;
    }
</script>
  <script>

document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    // Update cart data before stock check
    updateCartDataBeforeSubmit();

    let cartData = document.getElementById('cartData').value;

    // Update your existing JavaScript code where you handle adding items to the cart
// Add this check before allowing the user to add items
$.ajax({
    url: "{{ route('order.store', ['table' => $table->table_number]) }}",
    type: "POST",
    data: {
        _token: "{{ csrf_token() }}",
        cart: cartData
    },
    success: function(response) {
        if (response.status === 'success') {
            // Proceed with adding items to cart
            document.querySelector('form').submit();
        } else {
            // Display error message or prevent further action
            alert('Error: ' + response.error); // Example handling, adjust as per your UI/UX needs
        }
    }
});

    // Perform AJAX request to check stock
    $.ajax({
        url: "{{ route('check.stock') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            cart: cartData
        },
        success: function(response) {
            if (response.status === 'success') {
                // If stock is sufficient, submit the form
                document.querySelector('form').submit();
            } else {
                // If stock is insufficient, alert the user with detailed information
                let errorMessage = response.message + '\n';
                response.items.forEach(function(item) {
                    errorMessage += `Product: ${item.name}, Requested Quantity: ${item.requested_quantity}, Available Stock: ${item.available_stock}\n`;
                });
                alert(errorMessage);
                // Reset the textarea with id 'notes'
            document.getElementById('notes').value = '';
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred while checking stock. Please reload page again.');
        }
    });
});



    $(document).ready(function() {
      // Handle View Cart button click
      $('#view-cart-btn').on('click', function() {
        // Trigger the cart modal to show
        $('#cartModal').modal('show');
  
        // Optionally, you can load cart items dynamically here using AJAX
        // and update the cart content inside the modal
      });

      $('#view-pesanan-btn').on('click', function() {
        // Trigger the cart modal to show
        $('#pesananModal').modal('show');
  
        // Optionally, you can load cart items dynamically here using AJAX
        // and update the cart content inside the modal
      });
    });

     // Lightbox functions
     function showLightbox(imageUrl) {
    var lightbox = document.getElementById('lightbox');
    var lightboxImage = document.getElementById('lightboxImage');

    // Set the image src
    lightboxImage.src = imageUrl;

    // Calculate maximum dimensions for the lightbox image
    var maxWidth = window.innerWidth * 0.8; // 80% of the viewport width
    var maxHeight = window.innerHeight * 0.8; // 80% of the viewport height

    // Get the original dimensions of the image
    var img = new Image();
    img.onload = function() {
        var width = img.width;
        var height = img.height;

        // Calculate scaled dimensions while maintaining aspect ratio
        var scaleFactor = Math.min(maxWidth / width, maxHeight / height);
        var scaledWidth = width * scaleFactor;
        var scaledHeight = height * scaleFactor;

        // Apply scaled dimensions to the image
        lightboxImage.style.width = scaledWidth + 'px';
        lightboxImage.style.height = scaledHeight + 'px';

        // Display the lightbox
        lightbox.style.display = 'block';
    };
    img.src = imageUrl;

    // Close lightbox when clicking outside the image
    lightbox.addEventListener('click', function(event) {
        if (event.target === lightbox) {
            closeLightbox();
        }
    });
}


    function closeLightbox() {
        var lightbox = document.getElementById('lightbox');
        lightbox.style.display = 'none';
    }


  </script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="custom-nav">
                @foreach($menu->unique('product_category') as $category)
                <a class="nav-item{{ $loop->first ? '' : '' }}" href="#{{ strtolower($category->product_category) }}">{{ $category->product_category }}</a>
                {{-- <a class="nav-item{{ $loop->first ? ' active' : '' }}" href="#{{ strtolower($category->product_category) }}">{{ $category->product_category }}</a> --}}
                @endforeach
                <center><button id="view-cart-btn" class="btn btn-danger btn-block" style="width: 100%;"><i class="fas fa-shopping-cart"></i> Lihat Keranjang</button><br>
                <button id="view-pesanan-btn" class="btn btn-danger btn-block" style="margin-top: 8px; width: 100%;">Lihat Pesanan</button></center>
            </div>
        </div>
        
        <div class="col-md-9">
            @foreach($menu->unique('product_category') as $category)
            <div id="{{ strtolower($category->product_category) }}" class="tab-content{{ $loop->first ? ' active' : '' }}">
                <div class="row">
                    @foreach($menu as $item)
                    @if($item->product_category === $category->product_category && $item->product_status ==="active")
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($item->images)
                                <img src="{{ Storage::url($item->images) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="..." onclick="showLightbox('{{ Storage::url($item->images) }}')" >
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->product_name }}</h5>
                                <p class="card-text">{{ $item->product_description }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <p class="mb-0">Harga: Rp.{{ number_format($item->product_price, 0, ',', '.') }}</p>
                                <button class="btn btn-danger btn-sm" onclick="addToCart('{{ $item->id }}', '{{ $item->product_name }}', '{{ $item->product_price }}', '{{ $item->product_stock }}')"><i class="fas fa-shopping-cart"></i> +</button>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Lightbox for Image Preview -->
<div id="lightbox" class="lightbox">
    <div class="lightbox-content">
        <img id="lightboxImage">
        <button class="custom-button" onclick="closeLightbox()">Tutup</button>
    </div>
</div>

<style>

    

.lightbox {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0%;
    top: 0%;
    width: 100%;
    height: 100%;
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.9); /* Black with opacity */
}

.lightbox-content {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);

}

.custom-button {
    display: block;
    margin: 2px auto 0;
    margin-bottom: 15px auto 0;
    text-align: center;
    padding: 5px 10px;
    background-color: red;
    color: black;
    border: none;
    border-radius: 12px;
    cursor: pointer;
}

</style>


<style>






input[type="number"] {
    width: 100%; /* Set width to 100% for responsiveness */
    max-width: 70px; /* Set maximum width if needed */
}

    .custom-button {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .custom-nav {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }

    .nav-item {
        display: block;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        color: #333;
        text-decoration: none;
        transition: background-color 0.3s ease;
        width: 100%;
        text-align: center;
    }

    .nav-item:hover {
        background-color: #e9ecef;
    }

    .nav-item.active {
        background-color: #dc3545;
        color: #fff;
    }

    .tab-content {
        padding: 20px;
        margin-bottom: 50px; /* Add bottom margin to prevent overlap with footer */
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-img-top {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .card-text {
        margin-bottom: 15px;
    }

    .btn {
        border-radius: 5px;
    }
</style>



{{-- <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{env('MIDTRANS_CLIENT_KEY')}}"></script>
    <script type="text/javascript">
      // Function to handle payment initiation when the checkout button is clicked
      document.getElementById('checkout-form').addEventListener('submit', function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Execute payment initiation logic using Snap
        snap.pay('<?=$snapToken?>', {
          // Optional: Callback functions for different payment outcomes
          onSuccess: function(result) {
            // Handle successful payment
            // You may redirect the user to a success page or display a success message
            // Example: window.location.href = "<Your success page URL>";
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
    </script> --}}



<script>



    document.addEventListener('DOMContentLoaded', function () {
        const navItems = document.querySelectorAll('.nav-item');
        const tabContents = document.querySelectorAll('.tab-content');

        // Add event listener to nav items
        navItems.forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();

                // Remove 'active' class from all nav items
                navItems.forEach(item => {
                    item.classList.remove('active');
                });

                // Hide all tab contents
                tabContents.forEach(content => {
                    content.style.display = 'none';
                });

                // Add 'active' class to clicked nav item
                this.classList.add('active');

                // Show corresponding tab content
                const targetId = this.getAttribute('href').replace('#', '');
                document.getElementById(targetId).style.display = 'block';
            });
        });
    });


    function getTableNumberFromURL() {
        let url = window.location.href;
        let parts = url.split('/');
        let tableNumber = parts[parts.length - 1];
        return tableNumber;
    }

    function calculateSubtotal(cart) {
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.quantity;
    });
    return subtotal;
    }

    function calculateGrandTotal(cart) {
    let subtotal = calculateSubtotal(cart);
    // You can add additional charges like taxes or shipping fees here if needed
    let grandTotal = subtotal;
    return grandTotal;
}

    // function addTableNumberToCart(tableNumber) {
    //     // Get the cart data from local storage
    //     let cart = JSON.parse(localStorage.getItem('cart')) || [];

    //     // Check if the table number is already in the cart
    //     let existingTableIndex = cart.findIndex(item => item.type === 'table' && item.number === tableNumber);

    //     // If the table number is not in the cart, add it
    //     if (existingTableIndex === -1) {
    //         cart.push({ type: 'table', number: tableNumber });
    //     }

    //     // Update the cart data in local storage
    //     localStorage.setItem('cart', JSON.stringify(cart));
    // }

    function addToCart(id, name, price, available_stock) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    let existingItemIndex = cart.findIndex(item => item.id === id);
    if (existingItemIndex !== -1) {
        cart[existingItemIndex].quantity++;
    } else {
        cart.push({ id: id, name: name, price: price, quantity: 1, available_stock: available_stock });
    }

    localStorage.setItem('cart', JSON.stringify(cart));

    displayCart();
    toggleCheckoutButton();

    const alertMsg = 'Menu Di Tambah Kedalam Keranjang!';
    alert(alertMsg);
    setTimeout(() => {
        let alertElement = document.querySelector('.alert');
        if (alertElement) {
            alertElement.classList.add('hide');
        }
    }, 2000);
}


    // Function to display cart
    function changeQuantity(index, delta) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart[index].quantity += delta;
        if (cart[index].quantity < 1) {
            // Remove item if quantity becomes zero or negative
            cart.splice(index, 1);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        displayCart();
        toggleCheckoutButton();
    }
//     function displayCart() {
//     // Get cart data from local storage
//     let cart = JSON.parse(localStorage.getItem('cart')) || [];
//     let cartContainer = document.getElementById('cart');
//     cartContainer.innerHTML = '';

//     // Create and populate the cart table
//     let table = document.createElement('table');
//     table.classList.add('table');
//     let headerRow = table.insertRow();
//     // ['ID', 'Menu', 'Harga', 'Qty.', 'Harga', 'Action'].forEach(headerText => {
//     //     let header = document.createElement('th');
//     //     header.textContent = headerText;
//     //     headerRow.appendChild(header);
//     // });
//     ['Menu', 'Harga', 'Qty.', 'Sub Total', 'Pilihan'].forEach(headerText => {
//         let header = document.createElement('th');
//         header.textContent = headerText;
//         headerRow.appendChild(header);
//     });

//     let grandTotal = 0; // Initialize grand total variable

//     cart.forEach((item, index) => {
//         let row = table.insertRow();
//         // row.insertCell().textContent = item.id;
//         row.insertCell().textContent = item.name;
//         row.insertCell().textContent = 'Rp.' + item.price.toLocaleString('id-ID');
//         let quantityCell = row.insertCell();
//         let quantityInput = document.createElement('input');
//         quantityInput.type = 'number';
//         quantityInput.value = item.quantity;
//         quantityInput.min = 1;
//         quantityInput.addEventListener('change', function() {
//             let delta = parseInt(quantityInput.value) - item.quantity;
//             changeQuantity(index, delta);
//         });
//         quantityCell.appendChild(quantityInput);
//         row.insertCell().textContent = 'Rp.' +(item.price * item.quantity).toLocaleString('id-ID');
//         let actionsCell = row.insertCell();
//         let removeButton = document.createElement('button');
//         removeButton.textContent = 'Hapus';
//         removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
//         removeButton.addEventListener('click', function() {
//             changeQuantity(index, -item.quantity);
//         });
//         actionsCell.appendChild(removeButton);
//         grandTotal += item.price * item.quantity;
//     });

//     // Display grand total inside the grand total container
//     let grandTotalContainer = document.getElementById('grandTotalLabel');
//     grandTotalContainer.textContent = 'Total Harga: Rp.' + grandTotal.toLocaleString('id-ID'); // Display grand total with two decimal places

//     document.getElementById("gross_amount").value = grandTotal;
//     cartContainer.appendChild(table);
// }

function displayCart() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartContainer = document.getElementById('cart');
    cartContainer.innerHTML = '';

    let table = document.createElement('table');
    table.classList.add('table', 'table-responsive');
    let headerRow = table.insertRow();
    ['Menu', 'Harga', 'Qty.', 'Pilihan'].forEach(headerText => {
        let header = document.createElement('th');
        header.textContent = headerText;
        headerRow.appendChild(header);
    });

    let grandTotal = 0;

    // Function to fetch item details asynchronously
    async function fetchItemDetails(item) {
    try {
        let response = await fetch(`/api/items/${item.id}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        let data = await response.json();
        console.log('API response:', data.item.product_stock); // Log API response for debugging
        return data.item.product_stock || 0; // Handle undefined case
    } catch (error) {
        console.error('Error fetching item details:', error);
        return 0; // Default to 0 or handle error accordingly
    }
}

function formatNumber(number) {
    // Ensure the number is an integer
    const intNumber = Math.floor(number);
    // Use toLocaleString with options to add thousands separator and no decimal places
    return intNumber.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}
    // Async function to display cart items with dynamic max quantity
    async function displayCartItems() {
        for (let index = 0; index < cart.length; index++) {
            let item = cart[index];
            console.log('Fetching details for item:', item);
            let availableStock = await fetchItemDetails(item);
            // console.log('Available stock for item', item.id, ':', await fetchItemDetails(item)); // Log availableStock for debugging

            let row = table.insertRow();
            row.insertCell().textContent = item.name;
            row.insertCell().textContent = 'Rp.' + formatNumber(item.price);

            let quantityCell = row.insertCell();
            let quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.value = item.quantity;
            quantityInput.min = 1;
            quantityInput.max = availableStock; // Set max to available stock from Ajax
            // quantityInput.addEventListener('change', function() {
            //     let delta = parseInt(quantityInput.value) - item.quantity;
            //     changeQuantity(index, delta);
            // });
            let timeout;
            quantityInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    let value = parseInt(quantityInput.value);
                    if (value > availableStock) {
                        quantityInput.value = availableStock; // Ensure value does not exceed availableStock
                    } else if (value < 1) {
                        quantityInput.value = 1; // Ensure value is not less than 1
                    }
                    let delta = parseInt(quantityInput.value) - item.quantity;
                    changeQuantity(index, delta);
                }, 500); // Delay of 500ms
            });
            quantityCell.appendChild(quantityInput);

            // row.insertCell().textContent = 'Rp.' + (item.price * item.quantity).toLocaleString('id-ID');

            let actionsCell = row.insertCell();
            let removeButton = document.createElement('button');
            removeButton.textContent = 'Hapus';
            removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
            removeButton.addEventListener('click', function() {
                changeQuantity(index, -item.quantity);
            });
            actionsCell.appendChild(removeButton);

            grandTotal += item.price * item.quantity;
        }

        let grandTotalContainer = document.getElementById('grandTotalLabel');
        grandTotalContainer.textContent = 'Total Harga: Rp.' + grandTotal.toLocaleString('id-ID');

        document.getElementById("gross_amount").value = grandTotal;
        cartContainer.appendChild(table);
    }

    // Call async function to display cart items
    displayCartItems();
}






    function updateCartDataBeforeSubmit() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        document.getElementById('cartData').value = JSON.stringify(cart);
    }


        function toggleCheckoutButton() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let checkoutBtn = document.getElementById('checkoutBtn');
        if (cart.length === 0) {
            checkoutBtn.disabled = true;
        } else {
            checkoutBtn.disabled = false;
        }
    }

    // Call the displayCart function when the page loads
     window.addEventListener('DOMContentLoaded', function() {
        // let tableNumber = getTableNumberFromURL();
        // addTableNumberToCart(tableNumber);
        toggleCheckoutButton()
        // Display the cart after adding the table number
        displayCart();
    });

    document.querySelector('form').addEventListener('submit', function() {
     // Update cart data before form submission
        updateCartDataBeforeSubmit();
    // Clear the 'cart' key from local storage after submitting the order
    // localStorage.removeItem('cart');
});


</script>
@endsection
