@extends('admin.main')
@section('container')
<div class="container mb-5"> <!-- Add margin-bottom -->
    <h1 class="mb-0">Ubah Menu</h1>
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
    <form action="{{ route('admin.product.update',$product->id) }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 100px;">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name}}" required disabled>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Unggah Gambar</label>

            <input type="hidden" name="oldImage" value="{{$product->images}}">
            @if($product->images)
            <img src="{{asset('storage/'.$product->images)}}" class="img-preview img-fluid mb-3 col-sm-5 rounded d-block" style="max-width: 100px; max-height: 100px;">
            
            @else
            <img class="img-preview img-fluid mb-3 col-sm-5 rounded" style="max-width: 100px; max-height: 100px;">
            @endif

            
            <input type="file" class="form-control" id="images" name="images" onchange="previewImage()" disabled>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Harga Produk</label>
            <input type="text" class="form-control" id="product_price" name="product_price" value="{{ $product->product_price}}"required disabled>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Deskripsi Produk</label>
            <input type="text" class="form-control" id="product_description" name="product_description" value="{{ $product->product_description}}"required disabled> 
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Kategori Produk</label>
            <select class="form-select" id="product_category" name="product_category" required disabled>
                <option value="Bakmi" {{ $product->product_category === 'Bakmi' ? 'selected' : '' }}>Bakmi</option>
                <option value="Nasi" {{ $product->product_category === 'Nasi' ? 'selected' : '' }}>Nasi</option>
                <option value="DimSum" {{ $product->product_category === 'DimSum' ? 'selected' : '' }}>Dimsum</option>
                <option value="Tambahan" {{ $product->product_category === 'Tambahan' ? 'selected' : '' }}>Tambahan</option>
                <option value="Minuman" {{ $product->product_category === 'Minuman' ? 'selected' : '' }}>Minuman</option>
                <option value="Frozen" {{ $product->product_category === 'Frozen' ? 'selected' : '' }}>Frozen</option>
                <option value="Lain-Lain" {{ $product->product_category === 'Lain-Lain' ? 'selected' : '' }}>Lain-Lain</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Status Produk</label>
            <select class="form-select" id="product_status" name="product_status" required>
                <option value="active" {{ $product->product_status === 'active' ? 'selected' : '' }}>Tersedia</option>
                <option value="inactive" {{ $product->product_status === 'inactive' ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ubah Produk</button>
    </form>
</div>

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
