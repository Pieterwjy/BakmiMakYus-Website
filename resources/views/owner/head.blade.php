<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bakmi MakYus || {{$title}}</title>
  <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
  <style>
    trix-toolbar [data-trix-button-group="file-tools"] {
      display:none;
    }
  </style>
  @yield('head')
</head>
<body class="d-flex flex-column h-100">
  @yield('body')

  <footer class="text-center text-lg-start bg-light text-muted fixed-bottom">
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
  </footer>

  <!-- Trix JavaScript -->
  <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
