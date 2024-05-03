<nav class="navbar navbar-expand-lg navbar-light bg-light py-3 sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('owner.dashboard') }}">
      <img src="{{ asset('logo/logo.png') }}" width="60" height="60" alt="Logo">
      Bakmi MakYus - Pemilik Dasbor
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ ($title === "Akun") ? 'active' : "" }}" href="{{ route('owner.akun.index') }}">Akun</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ ($title === "Meja") ? 'active' : "" }}" href="{{ route('owner.table.index') }}">Meja</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ ($title === "Menu") ? 'active' : "" }}" href="{{ route('owner.product.index') }}">Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ ($title === "Menu") ? 'active' : "" }}" href="{{ route('owner.transaction.index') }}">Laporan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ ($title === "Logout") ? 'active' : "" }}" href="{{ route('owner.logout') }}">Keluar</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
