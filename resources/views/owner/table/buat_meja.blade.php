@extends('owner.main')
@section('container')
<h1 class="mb-0">Buat Meja</h1>
@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('owner.table.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="title" class="form-label">No. Meja</label>
      <input type="number" class="form-control" id="table_number" name="table_number" min="1" value="{{ $nextTableNumber }}" required>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Kapasitas Meja</label>
        <input type="number" class="form-control" id="table_capacity" name="table_capacity" min="1" required>
      </div>
    
    <button type="submit" class="btn btn-primary">Buat Meja</button>
  </form>

@endsection
{{-- 'table_number','table_capacity','table_qr' --}}