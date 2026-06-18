<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Kuliner Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    
    <style>
        /* 1. Reset & Setup Body */
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }
        #wrapper { display: flex; width: 100%; height: 100vh; }
        
        /* 2. Sidebar Kiri (Ukuran ramping 220px) */
        #sidebar { min-width: 220px; max-width: 220px; background: #0d6efd; color: #fff; transition: all 0.3s; overflow-y: auto; display: flex; flex-direction: column; }
        
        /* 3. Header Sidebar PetaKuliner (Tinggi pas 70px) */
        #sidebar .sidebar-header { 
            height: 70px !important; 
            min-height: 70px !important;
            max-height: 70px !important;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: #0b5ed7; 
            font-size: 1.2rem; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
            position: sticky; 
            top: 0; 
            z-index: 10; 
            box-sizing: border-box; 
        }
        
        /* 4. Wadah Menu Utama */
        #sidebar ul.components { padding: 20px 0; flex-grow: 1; }
        
        /* 5. GAYA DASAR TOMBOL MENU (Ini yang sebelumnya terhapus!) */
        #sidebar ul li a { 
            padding: 12px 15px; 
            margin: 0 15px 5px 15px; /* Jarak agar tidak mentok ke pinggir */
            border-radius: 8px;      /* Sudut melengkung */
            font-size: 1.05em; 
            display: block; 
            color: rgba(255,255,255,0.8); 
            text-decoration: none; 
            transition: 0.2s;
        }
        
        /* 6. Efek Saat Menu Aktif & Dihover */
        #sidebar ul li a:hover, #sidebar ul li.active > a { 
            color: #0d6efd; 
            background: #fff; 
            font-weight: bold;
        }
        
        #sidebar ul li a i { margin-right: 10px; }
        
        /* 7. Area Konten Kanan */
        #content { width: calc(100% - 220px); padding: 0; height: 100vh; display: flex; flex-direction: column; }
        
        /* 8. Navbar Atas (Tinggi 70px agar sejajar sempurna dengan Sidebar Header) */
        .top-navbar { 
            height: 70px !important; 
            min-height: 70px !important;
            max-height: 70px !important;
            background: #fff; 
            padding: 0 25px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
            display: flex; 
            justify-content: flex-end; 
            align-items: center; 
            z-index: 10; 
            box-sizing: border-box; 
        }
        
        /* 9. Area Konten Utama */
        .main-content { padding: 30px; flex-grow: 1; overflow-y: auto; overflow-x: hidden; width: 100%; }
    </style>
</head>
<body>
    <div id="wrapper">
        
        <nav id="sidebar" class="shadow-lg">
            <div class="sidebar-header fw-bold">
                🍔 Peta<span class="text-warning">Kuliner</span>
            </div>
            
            <ul class="list-unstyled components">
                <li class="<?= url_is('/') ? 'active' : '' ?>">
                    <a href="/"><i class="bi bi-house-door-fill"></i> Beranda</a>
                </li>
                
                <?php if (session()->get('logged_in')) : ?>
                    <li class="mt-2 <?= url_is('tambah-kuliner') ? 'active' : '' ?>">
                        <a href="/tambah-kuliner" class="text-warning"><i class="bi bi-plus-circle-fill"></i> Tambah Tempat</a>
                    </li>
                    
                    <?php if (session()->get('role') === 'admin') : ?>
                        <li class="mt-4 px-3 mb-1">
                            <small class="text-white-50 fw-bold text-uppercase" style="font-size: 11px;">Panel Admin</small>
                        </li>
                        <li class="<?= url_is('admin/categories*') ? 'active' : '' ?>">
                            <a href="/admin/categories"><i class="bi bi-grid-fill"></i> Kelola Kategori</a>
                        </li>
                        <li class="<?= url_is('admin/tags*') ? 'active' : '' ?>">
                            <a href="/admin/tags"><i class="bi bi-tags-fill"></i> Kelola Tag</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            
            <ul class="list-unstyled" style="padding: 10px 0; border-top: 1px solid rgba(255,255,255,0.1);">
                <?php if (session()->get('logged_in')) : ?>
                    <li>
                        <a href="/logout" class="text-warning fw-bold"><i class="bi bi-box-arrow-left"></i> Logout</a>
                    </li>
                <?php else : ?>
                    <li class="<?= url_is('login') ? 'active' : '' ?>">
                        <a href="/login"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                    </li>
                    <li class="<?= url_is('register') ? 'active' : '' ?>">
                        <a href="/register"><i class="bi bi-pencil-square"></i> Daftar Akun</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div id="content">
            <div class="top-navbar">
                <div class="d-flex align-items-center">
                    <?php if(session()->get('logged_in')): ?>
                        <a href="/profile" class="btn btn-light border rounded-pill d-flex align-items-center shadow-sm px-3 py-1 text-decoration-none">
                            <span class="fw-bold me-2 text-dark">Halo, <?= esc(session()->get('name')); ?></span>
                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                        </a>
                    <?php else: ?>
                        <span class="text-muted fst-italic">Tamu (Belum Login)</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="main-content">
                <?= $this->renderSection('content'); ?>
            </div>
        </div>
        
    </div>

    <button type="button" class="btn btn-primary rounded-circle shadow-lg" id="btn-back-to-top" style="position: fixed; bottom: 25px; right: 25px; display: none; z-index: 9999; width: 45px; height: 45px; border: 1px solid white;">
        <i class="bi bi-arrow-up-short fs-5"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    
    <script>
        let mybutton = document.getElementById("btn-back-to-top");
        let mainContent = document.querySelector('.main-content');
        
        mainContent.onscroll = function() {
            if (mainContent.scrollTop > 100) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        };
        mybutton.addEventListener("click", function() {
            mainContent.scrollTo({ top: 0, behavior: "smooth" });
        });
    </script>

    <?= $this->renderSection('scripts'); ?>
</body>
</html>