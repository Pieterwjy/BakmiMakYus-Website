@extends('cook.main')
@section('container')
<div id="card-container"></div>
<br>
<br>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Function to fetch updated data from the server
    function fetchUpdatedData() {
        // Make an AJAX request to the endpoint
        $.ajax({
            url: "{{ route('cook.order.fetch') }}",
            type: "GET",
            dataType: "json",
            
            success: function(response) {
                // Call a function to update the HTML table with the received data
                renderData(response);
                
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    }

    function renderData(data) {
    // Select the container element
    var container = $('#card-container');

    // Remove existing cards from the container
    container.empty();

    // Check if data is defined and is an array
    if (Array.isArray(data)) {
        var row = $('<div class="row row-cols-1 row-cols-md-3 g-4"></div>'); // Added grid spacing

        data.forEach(function(order) {
            var col = $('<div class="col"></div>'); // Adjusted column class
            var card = $('<div class="card h-100 shadow"></div>'); // Added shadow for depth effect
            var cardHeader = $('<div class="card-header bg-primary text-white text-center"></div>'); // Added header
            cardHeader.text('Pesanan #' + order.id); // Display order ID in header
            var cardBody = $('<div class="card-body d-flex flex-column"></div>'); // Added flex for content alignment

            var cardText = '';
            if (order.order_type) {cardText += '<p class="card-text mb-2"><strong>Jenis Pesanan:</strong> ' + order.order_type + '</p>';}
            if (order.notes) {cardText += '<p class="card-text mb-2"><strong>Catatan:</strong> ' + order.notes + '</p>';}
            var ul = $('<ul class="list-group mb-3"></ul>');

            order.details.forEach(function(detail) {
                var li = $('<li class="list-group-item d-flex justify-content-between align-items-center"></li>'); // Adjusted list item layout
                li.html('<span><strong>' + detail.product_name + '</strong></span><span class="badge bg-secondary rounded-pill">Jumlah: ' + detail.order_qty + '</span>'); // Added badge for quantity
                ul.append(li);
            });

            cardBody.append(cardText, ul);
            card.append(cardHeader, cardBody); // Added header to card
            col.append(card);
            row.append(col);
        });

        container.append(row);
    } else {
        console.error('Data is not an array or is undefined');
    }
}





    // Call the fetchDataAndRender function when the page loads
    function updateDataPeriodically() {
       // Fetch and render data initially

        setInterval(function() {
          fetchUpdatedData(); // Fetch and render data every 5 seconds
        }, 5000); // 5000 milliseconds = 5 seconds
    }

    // Call the updateDataPeriodically function when the page loads
    $(document).ready(function() {
      fetchUpdatedData();
        updateDataPeriodically();
    });
</script>
@endsection