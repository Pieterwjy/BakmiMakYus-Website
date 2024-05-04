@extends('owner.main')
@section('container')
<div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Daftar Meja</h1>
    <a href="{{ route('owner.table.create') }}" class="btn btn-primary">Tambah Meja</a>
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
                <th>No. Meja</th>
                <th>Kapasitas</th>
                <th>QR Meja</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tbody>
            @if($tables->count() > 0)
                @foreach($tables as $rs)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->table_number }}</td>
                        <td class="align-middle">{{ $rs->table_capacity }} Orang</td>
                        {{-- <td class="align-middle">{{ $rs->table_qr }}</td> --}}
                        <td class="align-middle"><img src="{{ asset( $rs->table_qr) }}" alt="QR Code" width="75" height="75"></td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                
                                @if($rs->role == 'pendeta')
                                
                                @else
                                <a href="{{ route('owner.table.edit', $rs->id)}}" type="button" class="btn btn-warning">Ubah</a>
                                @endif
                                <form action="{{ route('owner.table.destroy', $rs->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
                                    @method('DELETE')
                                    @csrf
                                    @if($rs->role == 'owner')
                                
                                    @else
                                    <button class="btn btn-danger m-0">Hapus</button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Meja Tidak Ditemukan</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection