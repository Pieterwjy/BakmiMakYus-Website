@extends('admin.main')
@section('container')
<div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Daftar Histori Pesanan</h1>
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
                    @if($rs)
                    {{-- @if($rs->status === 'Settlement'&& $rs->order_status !== 'Selesai') --}}
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->id }}</td>
                        <td class="align-middle">{{ $rs->table_number }}</td>
                        <td class="align-middle">{{ $rs->order_type }}</td>
                        <td class="align-middle">{{ $rs->notes }}</td>
                        <td class="align-middle">{{ $rs->order_status }}</td>
                        <td class="align-middle">Rp. {{ number_format($rs->gross_amount, 0, ',', '.') }}</td>
                        <td class="align-middle">{{ $rs->status }}</td>
                        
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('admin.order.show', $rs->id)}}" type="button" class="btn btn-secondary">Rincian</a>
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


@endsection