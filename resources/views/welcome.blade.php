<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bakmi MakYus || Order</title>
  <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
    }

    .carousel,
    .carousel-inner,
    .carousel-item {
        height: 100vh; /* Set the height to 100% of the viewport height */
    }

    .footer-button {
      width: 100%;
      padding: 20px; /* Adjust the padding to increase button height */
      border-radius: 0; /* Remove button border-radius */
    }
  </style>
</head>
<nav class="navbar sticky-top navbar-expand-lg bg-light py-3">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('logo/logo.png') }}" width="60" height="60" alt="Logo">
        Bakmi MakYus
      </a>
    </div>
</nav>

<div class="position-relative">
    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{asset('carousel/carousel1.png')}}" class="d-block w-100" style="object-fit: cover; height: 100%;" alt="...">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5);"></div>
            </div>
            <div class="carousel-item">
                <img src="{{asset('carousel/carousel2.png')}}" class="d-block w-100" style="object-fit: cover; height: 100%;" alt="...">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5);"></div>
            </div>
            <div class="carousel-item">
                <img src="{{asset('carousel/carousel3.png')}}" class="d-block w-100" style="object-fit: cover; height: 100%;" alt="...">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5);"></div>
            </div>
        </div>
    </div>
</div>

    
    
<div class="jumbotron position-absolute top-50 start-50 translate-middle text-center" style="transform: translateY(-30%); z-index: 1;">
    <h1 class="display-4 fw-bold text-light" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Selamat datang di <span class="text-danger">Bakmi MakYus</span>!</h1>
    <p class="lead text-light" style="font-size: 1.2rem;">Nikmati <span class="fw-bold">Mie Lezat</span> Disajikan Dengan <span class="fw-bold">Cinta</span>.</p>
    <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.5);">
    <p class="text-light" style="font-size: 1.2rem;"><span class="fw-bold">Yuk, Pesan Sekarang!</span></p>
</div>


    </div>
    
    
    

    <footer class="text-center text-lg-start bg-light text-muted fixed-bottom">
        <section>
            <button type="button" class="btn btn-danger footer-button" onclick="showPopup()">
                <h3 style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">Pesan Sekarang</h3>
            </button>
        </section>
        <!-- Section: Social media -->
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
            <!-- Left -->
            <div class="me-5 d-none d-lg-block">
                <span>© 2024 Copyright: Bakmi MakYus Surabaya</span>
            </div>
            <div>
                <a href="https://www.instagram.com/bakmimakyus/" class="me-4 text-reset">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="https://www.facebook.com/bakmimakyus/" class="me-4 text-reset">
                    <i class="bi bi-facebook"></i>
                </a>
            </div>
        </section>
        <!-- Button -->
    </footer>

    <!-- Modal -->
    <div class="modal" tabindex="-1" id="tableSelectModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Nomor Meja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" id="tableNumber">
                        <option selected disabled>Pilih Nomor Meja</option>
                        <!-- <?php foreach ($tables as $table): ?> -->
                        <!-- <option value="<?php echo $table['table_number']; ?>"><?php echo "Meja " . $table['table_number']; ?></option> -->
                        <!-- <?php endforeach; ?> -->
                        <?php foreach ($tables as $table): ?>
                            <option value="<?php echo $table['table_number']; ?>">
                                <?php echo $table['table_number'] == 0 ? "Ambil Di Kasir" : "Meja " . $table['table_number']; ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="redirectToScan()">Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Trix JavaScript -->
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        function showPopup() {
            var modal = new bootstrap.Modal(document.getElementById('tableSelectModal'));
            modal.show();
        }

        function redirectToScan() {
            var selectedTableNumber = document.getElementById('tableNumber').value;
            if (selectedTableNumber) {
                window.location.href = "/scan/" + selectedTableNumber;
            } else {
                alert("Harap Pilih Nomor Meja.");
            }
        }
    </script>
</body>
</html>
