@extends('owner.main')
@section('container')
<h1 class="mb-0">Edit Meja</h1>
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <hr />
    <form action="{{ route('owner.table.update',$table->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">No. Meja</label>
            <input type="number" class="form-control" id="table_number" name="table_number" min="1" value="{{ $table->table_number}}" required>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Kapasitas Meja</label>
            <input type="number" class="form-control" id="table_capacity" name="table_capacity" min="1" value="{{ $table->table_capacity}}" required>
        </div>
        
            <div class="row">
                <div class="d-grid">
                    <button class="btn btn-primary">Ubah Meja</button>
                </div>
            </div>
        </div>
    </form>
    
    <br>
    <br>
    <br>
    <br>
    </div>
 
@endsection