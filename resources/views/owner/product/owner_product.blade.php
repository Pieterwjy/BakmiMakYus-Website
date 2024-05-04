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
    <table class="table table-hover" style="margin-bottom: 100px;">
        <thead class="thead-light text-center">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Gambar</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Pilihan</th>
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
                        <td class="align-middle">
                            @if($rs->images)
                                <img src="{{ Storage::url($rs->images) }}" alt="Menu Image" width="75" height="75">
                            @endif
                        </td>
                        <td class="align-middle">Rp.{{ number_format($rs->product_price, 0, ',', '.') }}</td>
                        <td class="align-middle">{{ $rs->product_description }}</td>
                        <td class="align-middle">{{ $rs->product_category }}</td>
                        <td class="align-middle">{{ $rs->product_status == 'active' ? 'Tersedia' : 'Tidak Tersedia'}}</td>
                        
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('owner.product.edit', $rs->id)}}" type="button" class="btn btn-warning">Ubah</a>
                                
                                <form action="{{ route('owner.product.destroy', $rs->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-danger m-0">Hapus</button>
        
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