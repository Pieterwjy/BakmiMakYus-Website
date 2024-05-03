<nav class="navbar sticky-top navbar-expand-lg bg-light py-3">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('logo/logo.png') }}" width="60" height="60" alt="Logo">
        Bakmi MakYus - Pemesanan
      </a>
      {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          {{-- Uncomment and modify as needed --}}
          {{-- <li class="nav-item">
            <a class="nav-link {{ ($title === "Cart") ? 'active' : "" }}" aria-current="page" href="{{ route('table.cart.index') }}">Cart</a>
          </li> --}}
          {{-- <li class="nav-item">
            <a class="nav-link {{ ($title === "Meja") ? 'active' : "" }}" aria-current="page" href="{{ route('owner.table.index') }}">Meja</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ ($title === "Menu") ? 'active' : "" }}" aria-current="page" href="{{ route('owner.product.index') }}">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ ($title === "Logout") ? 'active' : "" }}" aria-current="page" href="{{ route('owner.logout') }}">Keluar</a>
          </li> 
        </ul>
      </div> --}}
    </div>
  </nav>
  