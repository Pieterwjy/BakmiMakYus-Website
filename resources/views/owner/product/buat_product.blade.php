@extends('owner.main')
@section('container')
<h1 class="mb-0">Buat Menu</h1>
@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
{{-- 'product_name','product_price','product_photo',
    'product_description','product_category','product_status' --}}
<form action="{{ route('owner.product.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <label for="title" class="form-label">Nama Produk</label>
      <input type="text" class="form-control" id="product_name" name="product_name" required>
    </div>
    <div class="mb-3">
      <label for="image" class="form-label">Unggah Gambar</label>
      <img class="img-preview img-fluid mb-3 col-sm-5 rounded" style="max-width: 100px; max-height: 100px;">
      <input type="file" class="form-control" id="images" name="images" accept="image/jpeg, image/png" onchange="previewImage()">
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Harga Produk</label>
        <input type="number" class="form-control" id="product_price" name="product_price" min="0" required>
      </div>
      <div class="mb-3">
        <label for="title" class="form-label">Deskripsi Produk</label>
        <input type="text" class="form-control" id="product_description" name="product_description" required>
      </div>
      <div class="mb-3">
        <label for="product_category" class="form-label">Kategori Produk</label>
        {{-- {{dd($categories->product_category);}} --}}
    <select class="form-select" id="product_category" name="product_category" required>
      {{-- @foreach($categories as $category)
      <option value="{{ $category->product_category }}">{{ $category->product_category }}</option>
      @endforeach --}}
      <option value="Makanan">Bakmi</option>
      <option value="Minuman">Nasi</option>
      <option value="DimSum">Dimsum</option>
      <option value="Tambahan">Tambahan</option>
      <option value="Minuman">Minuman</option>
      <option value="Frozen">Frozen</option>
      <option value="Lain-Lain">Lain-Lain</option>
    </select>
      </div>
      <input type="text" class="form-control" id="custom_category_input" name="custom_category" style="display: none;" placeholder="Masukkan Kategori Baru">

    <button type="submit" class="btn btn-primary">Buat Produk</button>
  </form>

  <script>
    function previewImage(){
        const image = document.querySelector('#images');
        const imgPreview = document.querySelector('.img-preview');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);
        oFReader.onload = function (oFREvent){
            imgPreview.src = oFREvent.target.result;
        }
    }

</script>


@endsection
{{-- 'table_number','table_capacity','table_qr' --}}