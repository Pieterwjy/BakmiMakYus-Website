@extends('admin.main')
@section('container')
<div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Daftar Pesanan Aktif</h1>
    <div class=btn-group>
    <a href="{{ route('admin.order.create') }}" class="btn btn-primary">Tambah Pesanan</a>
    <a href="{{ route('admin.order.history') }}" class="btn btn-primary">Histori Pesanan</a>
    </div>
</div>
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
    <table id ="order-table" class="table table-hover" style="margin-bottom: 100px;">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>ID Pesanan</th>
                <th>No. Meja</th>
                <th>Jenis Pesanan</th>
                <th>Catatan</th>
                <th>Status Pesanan</th>
                <th>Total Harga</th>
                <th>Status Pembayaran</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tbody>
            @if($orders->count() > 0)
                @foreach($orders as $rs)
                {{-- {{dd($orders)}} --}}
                    @if(($rs->status === 'Settlement' || $rs->status === 'Paid By Cash' || $rs->status === 'Pending, Payment in Cashier' || $rs->status === 'Pending')  && $rs->order_status !== 'Selesai')
                    {{-- @if($rs->status === 'Settlement'&& $rs->order_status !== 'Selesai') --}}
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->id }}</td>
                        <td class="align-middle">
                        {{ $rs->table_number == 0 ? 'Ambil Di Kasir' : $rs->table_number }}
                        </td>
                        <td class="align-middle">{{ $rs->order_type }}</td>
                        <td class="align-middle">{{ $rs->notes }}</td>
                        <td class="align-middle">{{ $rs->order_status }}</td>
                        <td class="align-middle">Rp. {{ number_format($rs->gross_amount, 0, ',', '.') }}</td>
                        <td class="align-middle">{{ $rs->status }}</td>
                        
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                {{-- <a href="{{ route('admin.product.edit', $rs->id)}}" type="button" class="btn btn-warning">Edit</a> --}}
                                <a href="{{ route('admin.order.show', $rs->id)}}" type="button" class="btn btn-secondary">Rincian</a>
                                @if(($rs->status === 'Settlement' || $rs->status === 'Paid By Cash') && $rs->order_status !== 'Selesai')
                                
                                <form action="{{ route('admin.order.complete', ['id' => $rs->id]) }}" method="POST" >
                                    @method('PUT')
                                    @csrf
                                    <button class="btn btn-warning" type="submit" class="btn btn-danger p-0" onsubmit="return confirm('Selesaikan Pesanan?')">Selesaikan Pesanan</button>
                                  </form>
                               
                                @endif

                            @if(($rs->status === 'Pending, Payment in Cashier') && $rs->order_status !== 'Selesai')

                                <form action="{{ route('admin.order.pay', ['id' => $rs->id]) }}" method="POST" >
                                    @method('PUT')
                                    @csrf
                                    <button class="btn btn-warning" type="submit" class="btn btn-danger p-0" onsubmit="return confirm('Terima Pembayaran?')">Terima Pembayaran</button>
                                  </form>
                                  <form action="{{ route('admin.order.cancel', ['id' => $rs->id]) }}" method="POST">
                                        @method('PUT')
                                        @csrf
                                        <button class="btn btn-danger" type="submit" class="btn btn-danger p-0" onclick="return confirm('Batalkan Pembayaran?')">Batalkan Pesanan</button>
                                    </form>

                               
                            @endif

                            @if(($rs->status === 'Pending') && $rs->order_status !== 'Selesai')

                            <form action="https://bakmimakyus.cloud/admin/order/checkout/{{ $rs->id }}" method="GET">
                                @csrf
                                <button class="btn btn-warning" type="submit">Halaman Bayar</button>
                            </form>
                                  <form action="{{ route('admin.order.cancel', ['id' => $rs->id]) }}" method="POST">
                                        @method('PUT')
                                        @csrf
                                        <button class="btn btn-danger" type="submit" class="btn btn-danger p-0" onclick="return confirm('Batalkan Pembayaran?')">Batalkan Pesanan</button>
                                    </form>

                               
                            @endif
                                {{-- <form action="{{ route('owner.product.destroy', $rs->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-danger m-0">Delete</button>
        
                                </form> --}}
                                
                                
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Pesananan Tidak Ditemukan</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  function formatNumber(number) {
    // Convert the number to an integer
    var integerPart = Math.round(number);

    // Use toLocaleString to add thousands separator
    var formattedNumber = integerPart.toLocaleString('id-ID');

    return formattedNumber;
}
    // Function to fetch updated data from the server
    function fetchUpdatedData() {
        // Make an AJAX request to the endpoint
        $.ajax({
            url: "{{ route('admin.order.fetch') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                // Call a function to update the HTML table with the received data
                updateTable(response);
                console.log("fetchUpdatedDate Success", response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    }

//     // Function to update the HTML table with the received data
//     function updateTable(data) {
//     // Clear the existing table content
//     $('#order-table tbody').empty();

//     // Iterate over the received data and append rows to the table
//     $.each(data, function(index, order) {
//     if ((order.status === 'Settlement' || order.status === 'Paid By Cash' || order.status === 'Pending, Payment in Cashier') && order.order_status !== 'Selesai') {
//         var tableNumberText = order.table_number == 0 ? 'Ambil Di Kasir' : order.table_number;
//         var row = "<tr>" +
//             "<td class='align-middle'>" + (index + 1) + "</td>" +
//             "<td class='align-middle'>" + order.id + "</td>" +
//             // "<td class='align-middle'>" + order.table_number + "</td>" +
//             "<td class='align-middle'>" + tableNumberText + "</td>" +
//             "<td class='align-middle'>" + order.order_type + "</td>" +
//             "<td class='align-middle'>" + (order.notes !== null ? order.notes : "") + "</td>" +
//             "<td class='align-middle'>" + (order.order_status !== null ? order.order_status : "") + "</td>" +
//             "<td class='align-middle'> Rp. " + formatNumber(order.gross_amount) + "</td>" +
//             "<td class='align-middle'>" + order.status + "</td>" +
//             "<td class='align-middle'>" +
//             "<div class='btn-group'>" +
//             "<a href='{{ route("admin.order.show", "") }}/" + order.id + "' type='button' class='btn btn-secondary show-order'>Rincian</a>"; // Concatenate order.id directly

//         if (order.status === 'Pending, Payment in Cashier') {
//             row += "<form id='update-form-" + order.id + "' method='POST' action='{{ route("admin.order.pay", "") }}/" + order.id + "'>" +
//                 "<input type='hidden' name='_method' value='PUT'>" +
//                 "<input type='hidden' name='_token' value='{{ csrf_token() }}'>" +
//                 "<button class='btn btn-warning update-order' data-order-id='" + order.id + "' type='button'>Terima Pembayaran</button>" +
//                 "</form>"+
//                 "<form id='cancel-form-" + order.id + "' method='POST' action='{{ route("admin.order.cancel", "") }}/" + order.id + "'>" +
//                         "<input type='hidden' name='_method' value='PUT'>" +
//                         "<input type='hidden' name='_token' value='{{ csrf_token() }}'>" +
//                         "<button class='btn btn-danger cancel-order' data-order-id='" + order.id + "' type='button'>Batalkan Pesanan</button>" +
//                         "</form>";
//         } else {
//             row +=  // Grouping buttons in a div
//                 "<form id='complete-form-" + order.id + "' method='POST' action='{{ route("admin.order.complete", "") }}/" + order.id + "'>" +
//                 "<input type='hidden' name='_method' value='PUT'>" +
//                 "<input type='hidden' name='_token' value='{{ csrf_token() }}'>" +
//                 "<button class='btn btn-warning complete-order' data-order-id='" + order.id + "' type='button'>Selesaikan Pesanan</button>" +
//                 "</form>" +
//                 "</div>"; // Closing btn-group div
//         }
//         row += "</td>" +
//             "</tr>";

//         $('#order-table tbody').append(row);
//     }
// });
// }
function updateTable(data) {
    // Clear the existing table content
    $('#order-table tbody').empty();

    // Iterate over the received data and append rows to the table
    $.each(data, function(index, order) {
        if ((order.status === 'Settlement' || order.status === 'Paid By Cash' || order.status === 'Pending, Payment in Cashier' || order.status === 'Pending') && order.order_status !== 'Selesai') {
            var tableNumberText = order.table_number == 0 ? 'Ambil Di Kasir' : order.table_number;
            var row = `
                <tr>
                    <td class='align-middle'>${index + 1}</td>
                    <td class='align-middle'>${order.id}</td>
                    <td class='align-middle'>${tableNumberText}</td>
                    <td class='align-middle'>${order.order_type}</td>
                    <td class='align-middle'>${order.notes !== null ? order.notes : ""}</td>
                    <td class='align-middle'>${order.order_status !== null ? order.order_status : ""}</td>
                    <td class='align-middle'> Rp. ${formatNumber(order.gross_amount)}</td>
                    <td class='align-middle'>${order.status}</td>
                    <td class='align-middle'>
                        <div class='btn-group' role='group'>
                            <a href='{{ route("admin.order.show", "") }}/${order.id}' type='button' class='btn btn-secondary show-order'>Rincian</a>
            `;

            if (order.status === 'Pending, Payment in Cashier') {
                row += `
                    <form id='update-form-${order.id}' method='POST' action='{{ route("admin.order.pay", "") }}/${order.id}'>
                        <input type='hidden' name='_method' value='PUT'>
                        <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                        <button class='btn btn-warning update-order' data-order-id='${order.id}' type='button'>Terima Pembayaran</button>
                    </form>
                    <form id='cancel-form-${order.id}' method='POST' action='{{ route("admin.order.cancel", "") }}/${order.id}'>
                        <input type='hidden' name='_method' value='PUT'>
                        <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                        <button class='btn btn-danger cancel-order' data-order-id='${order.id}' type='button'>Batalkan Pesanan</button>
                    </form>
                `;
            } 
            else if (order.status === 'Pending'){
                row += `
                    <form action='https://bakmimakyus.cloud/admin/order/checkout/${order.id}' method='GET'>
                        <button class='btn btn-warning' type='submit'>Halaman Bayar</button>
                    </form>
                    <form action='{{ route("admin.order.cancel", "") }}/${order.id}' method='POST'>
                        @method('PUT')
                        @csrf
                        <button class='btn btn-danger' type='submit' onclick='return confirm("Batalkan Pembayaran?")'>Batalkan Pesanan</button>
                    </form>
                `;
            }
            else {
                row += `
                    <form id='complete-form-${order.id}' method='POST' action='{{ route("admin.order.complete", "") }}/${order.id}'>
                        <input type='hidden' name='_method' value='PUT'>
                        <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                        <button class='btn btn-warning complete-order' data-order-id='${order.id}' type='button'>Selesaikan Pesanan</button>
                    </form>
                `;
            }
            row += `
                        </div>
                    </td>
                </tr>
            `;

            $('#order-table tbody').append(row);
        }
    });
}


// Attach event listener to the dynamically created buttons
$(document).on('click', '.complete-order', function() {
    var orderId = $(this).data('order-id');
    $('#complete-form-' + orderId).submit();
});

$(document).on('click', '.update-order', function() {
    var orderId = $(this).data('order-id');
    $('#update-form-' + orderId).submit();
});

$(document).on('click', '.cancel-order', function(event) {
    var orderId = $(this).data('order-id');
    if (confirm("Batalkan Pembayaran?")) {
        $('#cancel-form-' + orderId).submit();
    } else {
        event.preventDefault(); // Prevent form submission if user clicks "Cancel"
    }
});

// Attach event listener to the dynamically created buttons
$(document).on('click', '.complete-order', function() {
    var orderId = $(this).data('order-id');
    $('#complete-form-' + orderId).attr('action', '/admin/order-complete/' + orderId).submit(); // Update the form action with the correct order ID
});

$(document).on('click', '.update-order', function() {
    var orderId = $(this).data('order-id');
    $('#update-form-' + orderId).attr('action', '/admin/order-pay/' + orderId).submit(); // Pay the form action with the correct order ID
});

$(document).on('click', '.show-order', function() {
    var orderId = $(this).data('order-id');
    // Redirect or perform other actions for showing order details
});

// Event listener for cancel order button click
$(document).on('click', '.cancel-order', function() {
        var orderId = $(this).data('order-id');
        
    });


    // Call fetchUpdatedData function periodically to update the table
    $(document).ready(function() {
        // Update the table every 5 seconds (5000 milliseconds)
        setInterval(fetchUpdatedData, 5000);
    });

</script>

@endsection