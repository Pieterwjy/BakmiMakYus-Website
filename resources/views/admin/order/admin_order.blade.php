@extends('admin.main')
@section('container')
<div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Daftar Pesanan Aktif</h1>
    <a href="{{ route('admin.order.create') }}" class="btn btn-primary">Tambah Transaksi</a>
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
                <th>Order_ID</th>
                <th>Table Number</th>
                <th>Order Type</th>
                <th>Notes</th>
                <th>Order Status</th>
                <th>Gross Amount</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($orders->count() > 0)
                @foreach($orders as $rs)
                {{-- {{dd($orders)}} --}}
                    @if(($rs->status === 'Settlement' || $rs->status === 'Paid By Cash' || $rs->status === 'Pending, Payment in Cashier')  && $rs->order_status !== 'Selesai')
                    {{-- @if($rs->status === 'Settlement'&& $rs->order_status !== 'Selesai') --}}
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->id }}</td>
                        <td class="align-middle">{{ $rs->table_number }}</td>
                        <td class="align-middle">{{ $rs->order_type }}</td>
                        <td class="align-middle">{{ $rs->notes }}</td>
                        <td class="align-middle">{{ $rs->order_status }}</td>
                        <td class="align-middle">Rp. {{ $rs->gross_amount }}</td>
                        <td class="align-middle">{{ $rs->status }}</td>
                        
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                {{-- <a href="{{ route('admin.product.edit', $rs->id)}}" type="button" class="btn btn-warning">Edit</a> --}}
                                @if(($rs->status === 'Settlement' || $rs->status === 'Paid By Cash') && $rs->order_status !== 'Selesai')

                                <form action="{{ route('admin.order.complete', ['id' => $rs->id]) }}" method="POST" >
                                    @method('PUT')
                                    @csrf
                                    <button class="btn btn-warning" type="submit" class="btn btn-danger p-0" onsubmit="return confirm('Complete?')">Complete Order</button>
                                  </form>

                               
                            @endif

                            @if(($rs->status === 'Pending, Payment in Cashier') && $rs->order_status !== 'Selesai')

                                <form action="{{ route('admin.order.pay', ['id' => $rs->id]) }}" method="POST" >
                                    @method('PUT')
                                    @csrf
                                    <button class="btn btn-warning" type="submit" class="btn btn-danger p-0" onsubmit="return confirm('Complete?')">Pay</button>
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
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    }

    // Function to update the HTML table with the received data
    function updateTable(data) {
    // Clear the existing table content
    $('#order-table tbody').empty();

    // Iterate over the received data and append rows to the table
    $.each(data, function(index, order) {
        if ((order.status === 'Settlement' || order.status === 'Paid By Cash' || order.status === 'Pending, Payment in Cashier') && order.order_status !== 'Selesai') {
        var row = "<tr>" +
            "<td class='align-middle'>" + (index + 1) + "</td>" +
            "<td class='align-middle'>" + order.id + "</td>" +
            "<td class='align-middle'>" + order.table_number + "</td>" +
            "<td class='align-middle'>" + order.order_type + "</td>" +
            "<td class='align-middle'>" + (order.notes !== null ? order.notes : "") + "</td>" + // Include order.notes if not null
            "<td class='align-middle'>" + (order.order_status !== null ? order.order_status : "") + "</td>" +
            "<td class='align-middle'> Rp. " + order.gross_amount + "</td>" +
            "<td class='align-middle'>" + order.status + "</td>" +
            "<td class='align-middle'>";
                if (order.status === 'Pending, Payment in Cashier') {
    row += "<form id='update-form-" + order.id + "' method='POST' action='{{ route("admin.order.pay", ":id") }}'>" +
            "<input type='hidden' name='_method' value='PUT'>" +
            "<input type='hidden' name='_token' value='{{ csrf_token() }}'>" +
            "<button class='btn btn-warning update-order' data-order-id='" + order.id + "' type='button'>Pay</button>" +
            "</form>";
    }       else{
    row += "<form id='complete-form-" + order.id + "' method='POST' action='{{ route("admin.order.complete", ":id") }}'>" + // Set the form action directly
            "<input type='hidden' name='_method' value='PUT'>" +
            "<input type='hidden' name='_token' value='{{ csrf_token() }}'>" +
            "<button class='btn btn-warning complete-order' data-order-id='" + order.id + "' type='button'>Complete Order</button>" +
            "</form>";
            }
    row += "</td>" +
            "</tr>";

        $('#order-table tbody').append(row);
            }
    });

    // Attach event listener to the dynamically created buttons
    $('.complete-order').click(function() {
        var orderId = $(this).data('order-id');
        $('#complete-form-' + orderId).attr('action', '{{ route("admin.order.complete", ":id") }}'.replace(':id', orderId)).submit(); // Update the form action with the correct order ID
    });
    $('.update-order').click(function() {
        var orderId = $(this).data('order-id');
        $('#update-form-' + orderId).attr('action', '{{ route("admin.order.pay", ":id") }}'.replace(':id', orderId)).submit(); // Pay the form action with the correct order ID
    });
}
    // Call fetchUpdatedData function periodically to update the table
    $(document).ready(function() {
        // Update the table every 5 seconds (5000 milliseconds)
        setInterval(fetchUpdatedData, 5000);
    });
</script>
@endsection