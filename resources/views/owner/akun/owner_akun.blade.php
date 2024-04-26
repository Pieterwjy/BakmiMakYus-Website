@extends('owner.main')
@section('container')
<div class="d-flex align-items-center justify-content-between">
    <h1 class="mb-0">Daftar Akun</h1>
    <a href="{{ route('owner.akun.create') }}" class="btn btn-primary">Tambah Akun</a>
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
                <th>Nama</th>
                <th>Email</th>
                <th>Handphone</th>
                <th>Hak Akses</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($accounts->count() > 0)
                @foreach($accounts as $rs)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->name }}</td>
                        <td class="align-middle">{{ $rs->email }}</td>
                        <td class="align-middle">{{ $rs->phone }}</td>
                        <td class="align-middle">{{ $rs->role }}</td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('owner.akun.show', $rs->id)}}" type="button" class="btn btn-secondary">Detail</a>
                                @if($rs->role == 'pendeta')
                                
                                @else
                                <a href="{{ route('owner.akun.edit', $rs->id)}}" type="button" class="btn btn-warning">Edit</a>
                                @endif
                                <form action="{{ route('owner.akun.destroy', $rs->id) }}" method="POST" type="button" class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
                                    @method('DELETE')
                                    @csrf
                                    @if($rs->role == 'owner')
                                
                                    @else
                                    <button class="btn btn-danger m-0">Delete</button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Akun Tidak Ditemukan</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection