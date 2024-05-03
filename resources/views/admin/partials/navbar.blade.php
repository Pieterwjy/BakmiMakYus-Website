<nav class="navbar navbar-expand-lg navbar-light bg-light py-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><img src="{{asset('logo/logo.png')}}" width="60" height="60" alt="Logo">Bakmi MakYus - Admin Dasbor</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ ($title === "Menu") ? 'active' : "" }}" aria-current="page" href="{{ route('admin.product.index') }}">Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ ($title === "Pesanan") ? 'active' : "" }}" aria-current="page" href="{{ route('admin.order.index') }}">Pesanan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ ($title === "Logout") ? 'active' : "" }}" aria-current="page" href="{{ route('admin.logout') }}">Keluar</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> --}}