<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peta Kuliner Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top py-3">
            <div class="container">
            <a class="navbar-brand fw-bold text-white" href="/">
                <span class="fs-4 me-1">🍔</span> PetaKuliner<span class="text-warning">.Mahasiswa</span>
            </a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-white px-3" href="/">Beranda</a>
                    </li>
                    
                    <?php if(session()->get('isLoggedIn')) : ?>
                        
                        <?php if(session()->get('role') === 'admin') : ?>
                            <li class="nav-item mx-lg-2 my-2 my-lg-0">
                                <a class="btn btn-warning fw-bold rounded-pill px-4 shadow-sm" href="/tambah-kuliner">
                                    + Tambah Tempat
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item">
                            <a href="/profile" class="nav-link fw-bold">
                                Halo, <?= session()->get('name'); ?> 👤
                            </a>
                        </li>

                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold text-white px-3" href="/login">Login</a>
                        </li>
                        <li class="nav-item mx-lg-2 my-2 my-lg-0">
                            <a class="btn btn-outline-light fw-bold px-4 rounded-pill shadow-sm" href="/register">
                                📝 Daftar Akun
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

  <div class="container mt-4">
    <?= $this->renderSection('content'); ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <?= $this->renderSection('scripts'); ?>
    <button type="button" class="btn btn-primary rounded-circle shadow-lg" id="btn-back-to-top" style="position: fixed; bottom: 25px; right: 25px; display: none; z-index: 9999; width: 45px; height: 45px; border: 1px solid white;">
        <i class="bi bi-arrow-up-short fs-5"></i>
    </button>

    <script>
        let mybutton = document.getElementById("btn-back-to-top");

        window.onscroll = function () {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        };

        mybutton.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    </script>
</body>

</html>