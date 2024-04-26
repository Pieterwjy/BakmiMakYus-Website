@extends('owner.main')
@section('container')
<div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Daftar Menu</h1>
    <a href="{{ route('owner.product.create') }}" class="btn btn-primary">Tambah Menu</a>
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
    <table class="table table-hover">
        <thead class="thead-light">
            <tr>
                
                <th>No</th>
                <th>Nama Produk</th>
                <th>Gambar Produk</th>
                <th>Harga Produk</th>
                <th>Deskripsi Produk</th>
                <th>Kategori Produk</th>
                <th>Status Produk</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($products->count() > 0)
                @foreach($products as $rs)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->product_name }}</td>
                        {{-- <td class="align-middle">{{ asset($rs->images) }}</td> --}}
                        {{-- <td class="align-middle">{{ Storage::url($rs->images) }}</td> --}}
                        <td class="align-middle"><img src="{{ Storage::url($rs->images) }}" alt="Menu Image" width="75" height="75"></td>
                        <td class="align-middle">{{ $rs->product_price }}</td>
                        <td class="align-middle">{{ $rs->product_description }}</td>
                        <td class="align-middle">{{ $rs->product_category }}</td>
                        <td class="align-middle">{{ $rs->product_status }}</td>
                        
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('owner.product.edit', $rs->id)}}" type="button" class="btn btn-warning">Edit</a>
                                
                                <form action="{{ route('owner.product.destroy', $rs->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-danger m-0">Delete</button>
        
                                </form>

                                
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Menu Tidak Ditemukan</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection