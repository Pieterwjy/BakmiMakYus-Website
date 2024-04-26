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
                <button class="btn btn-primary btn-sm">Tambah ke Keranjang</button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection --}}


@extends('table.main')

@section('container')




<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Added modal-lg class for large modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Keranjang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="cartItems">
                <!-- Cart items will be displayed here -->
               <b>Nomor Meja : {{$table->table_number}}</b> 
               
                <div id="cart"></div>
                <b><div id="grandTotalLabel" style="text-align: right;"></div>
                <div style="text-align: right;">
                    <input type="radio" id="dine-in" name="order-type" value="dine-in" checked>
                    <label for="dine-in">Dine-In</label>
         
                    <input type="radio" id="takeaway" name="order-type" value="takeaway">
                    <label for="takeaway">Takeaway</label>
                    </div></b>
                <textarea class="form-control" id="additionalNotes" name="additionalNotes" rows="2" placeholder="Catatan Khusus"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Checkout</button>
            </div>
        </div>
    </div>
</div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      // Handle View Cart button click
      $('#view-cart-btn').on('click', function() {
        // Trigger the cart modal to show
        $('#cartModal').modal('show');
  
        // Optionally, you can load cart items dynamically here using AJAX
        // and update the cart content inside the modal
      });
    });
  </script>

<div class="container-fluid">
    
    <div class="row">
        <div class="col-md-3 mb-4">
            
            <div class="custom-nav">
                <a class="nav-item active" href="#foods">Makanan</a>
                <a class="nav-item" href="#drinks">Minuman</a>
                <button id="view-cart-btn" class="btn btn-primary btn-block">View Cart</button>
            </div>
        </div>
        
        <div class="col-md-9">
            <div id="foods" class="tab-content">
                <div class="row">
                    @foreach($menu as $item)
                    @if($item->product_category === 'Makanan' && $item->product_status ==="active")
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ Storage::url($item->images) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->product_name }}</h5>
                                <p class="card-text">{{ $item->product_description }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <p class="mb-0">Harga: Rp.{{ $item->product_price }}</p>
                                <button class="btn btn-primary btn-sm" onclick="addToCart('{{ $item->id }}', '{{ $item->product_name }}', '{{ $item->product_price }}')">Tambah Ke Keranjang</button>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            <div id="drinks" class="tab-content" style="display: none;">
                <div class="row">
                    @foreach($menu as $item)
                    @if($item->product_category === 'Minuman' && $item->product_status ==="active")
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ Storage::url($item->images) }}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->product_name }}</h5>
                                <p class="card-text">{{ $item->product_description }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <p class="mb-0">Harga: Rp.{{ $item->product_price }}</p>
                                <button class="btn btn-primary btn-sm" onclick="addToCart('{{ $item->id }}', '{{ $item->product_name }}', '{{ $item->product_price }}')">Tambah Ke Keranjang</button>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>



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
        background-color: #007bff;
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

    function addTableNumberToCart(tableNumber) {
        // Get the cart data from local storage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Check if the table number is already in the cart
        let existingTableIndex = cart.findIndex(item => item.type === 'table' && item.number === tableNumber);

        // If the table number is not in the cart, add it
        if (existingTableIndex === -1) {
            cart.push({ type: 'table', number: tableNumber });
        }

        // Update the cart data in local storage
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function addToCart(id, name, price) {
    // Check if cart exists in local storage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Check if item is already in cart
    let existingItemIndex = cart.findIndex(item => item.id === id);
    if (existingItemIndex !== -1) {
        // Increment quantity if item is already in cart
        cart[existingItemIndex].quantity++;
    } else {
        // Add item to cart with quantity 1
        cart.push({ id: id, name: name, price: price, quantity: 1 });
    }

    // Update cart in local storage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Update cart view
    displayCart();

    // Optionally, you can provide feedback to the user
    const alertMsg = 'Menu Di Tambah Kedalam Keranjang!';
    alert(alertMsg);
    setTimeout(() => {
        // Close the alert after 2 seconds
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
    }
    function displayCart() {
    // Get cart data from local storage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartContainer = document.getElementById('cart');
    cartContainer.innerHTML = '';

    // Create and populate the cart table
    let table = document.createElement('table');
    table.classList.add('table');
    let headerRow = table.insertRow();
    // ['ID', 'Menu', 'Harga', 'Qty.', 'Harga', 'Action'].forEach(headerText => {
    //     let header = document.createElement('th');
    //     header.textContent = headerText;
    //     headerRow.appendChild(header);
    // });
    ['Menu', 'Harga', 'Qty.', 'Sub Total', 'Action'].forEach(headerText => {
        let header = document.createElement('th');
        header.textContent = headerText;
        headerRow.appendChild(header);
    });

    let grandTotal = 0; // Initialize grand total variable

    cart.forEach((item, index) => {
        let row = table.insertRow();
        // row.insertCell().textContent = item.id;
        row.insertCell().textContent = item.name;
        row.insertCell().textContent = 'Rp.' + item.price;
        let quantityCell = row.insertCell();
        let quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.value = item.quantity;
        quantityInput.min = 1;
        quantityInput.addEventListener('change', function() {
            let delta = parseInt(quantityInput.value) - item.quantity;
            changeQuantity(index, delta);
        });
        quantityCell.appendChild(quantityInput);
        row.insertCell().textContent = 'Rp.' +item.price * item.quantity;
        let actionsCell = row.insertCell();
        let removeButton = document.createElement('button');
        removeButton.textContent = 'Hapus';
        removeButton.classList.add('btn', 'btn-danger', 'btn-sm');
        removeButton.addEventListener('click', function() {
            changeQuantity(index, -item.quantity);
        });
        actionsCell.appendChild(removeButton);
        grandTotal += item.price * item.quantity;
    });

    // Display grand total inside the grand total container
    let grandTotalContainer = document.getElementById('grandTotalLabel');
    grandTotalContainer.textContent = 'Grand Total: Rp.' + grandTotal.toFixed(2); // Display grand total with two decimal places

    cartContainer.appendChild(table);
}

    // Call the displayCart function when the page loads
     window.addEventListener('DOMContentLoaded', function() {
        // let tableNumber = getTableNumberFromURL();
        // addTableNumberToCart(tableNumber);

        // Display the cart after adding the table number
        displayCart();
    });


</script>
@endsection
