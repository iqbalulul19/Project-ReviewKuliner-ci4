<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Kuliner Mahasiswa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

    <style>
        /* =========================================
           1. PENGATURAN TEMA & FONT UTAMA
           ========================================= */
        body {
            background-color: #F4F7F7;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            color: #2c3e3e;
        }

        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* =========================================
           2. OVERRIDE BOOTSTRAP 
           ========================================= */
        .btn-primary {
            background-color: #249E94 !important;
            border-color: #249E94 !important;
            color: #fff !important;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #0C7779 !important;
            border-color: #0C7779 !important;
        }

        .btn-warning {
            background-color: #249E94 !important;
            border-color: #249E94 !important;
            color: #fff !important;
        }

        .btn-warning:hover {
            background-color: #1d7f77 !important;
            border-color: #1d7f77 !important;
            color: #fff !important;
        }

        .btn-danger {
            background-color: #005461 !important;
            border-color: #005461 !important;
            color: #fff !important;
        }

        .btn-danger:hover {
            background-color: #003a43 !important;
            border-color: #003a43 !important;
            color: #fff !important;
        }

        .form-control:focus {
            border-color: #249E94 !important;
            box-shadow: 0 0 0 0.25rem rgba(36, 158, 148, 0.25) !important;
            background-color: #fff;
        }

        .card {
            border: 1px solid #e0e6e6 !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
            border-radius: 15px !important;
        }

        /* =========================================
           3. SIDEBAR KIRI
           ========================================= */
        #sidebar {
            min-width: 240px;
            max-width: 240px;
            background: #005461;
            transition: all 0.3s;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border-right: none;
        }

        #sidebar .sidebar-header {
            height: 70px !important;
            min-height: 70px !important;
            max-height: 70px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0C7779;
            color: #fff;
            font-size: 1.2rem;
            border-bottom: 1px solid #00424d;
            position: sticky;
            top: 0;
            z-index: 10;
            box-sizing: border-box;
        }

        #sidebar .sidebar-header .text-warning {
            color: #3BC1A8 !important;
        }

        #sidebar ul.components {
            padding: 20px 0;
            flex-grow: 1;
            border-bottom: none !important;
        }

        #sidebar ul li a {
            padding: 12px 15px;
            margin: 0 15px 5px 15px;
            border-radius: 8px;
            font-size: 1.05em;
            display: block;
            color: #ffffff !important;
            text-decoration: none;
            transition: background 0.2s;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #sidebar ul li a:hover,
        #sidebar ul li.active>a {
            color: #005461 !important;
            background: #ffffff !important;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        #sidebar ul li a i {
            margin-right: 10px;
        }

        #sidebar ul:last-child {
            border-top: 1px solid #0C7779 !important;
        }

        /* =========================================
           4. KONTEN KANAN & NAVBAR ATAS
           ========================================= */
        #content {
            width: calc(100% - 240px);
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: 70px !important;
            min-height: 70px !important;
            max-height: 70px !important;
            background: #ffffff;
            padding: 0 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            z-index: 10;
            box-sizing: border-box;
            border-bottom: 1px solid #e0e6e6;
        }

        .card-header {
            background-color: #0C7779 !important;
            color: #fff !important;
        }

        .btn-success {
            background-color: #3BC1A8 !important;
            border-color: #3BC1A8 !important;
        }

        .text-primary {
            color: #249E94 !important;
        }

        a {
            color: #249E94 !important;
        }

        .main-content {
            padding: 30px;
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            width: 100%;
        }

        .top-navbar .text-primary {
            color: #249E94 !important;
        }

        .btn-outline-primary {
            color: #249E94 !important;
            border-color: #249E94 !important;
            background-color: transparent !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active {
            color: #ffffff !important;
            background-color: #249E94 !important;
            border-color: #249E94 !important;
            box-shadow: 0 0 0 0.25rem rgba(36, 158, 148, 0.25) !important;
        }

        .modal-header.bg-primary {
            background: linear-gradient(135deg, #0C7779, #249E94) !important;
            border-bottom: none !important;
        }

        .form-control:focus {
            outline: none !important;
            box-shadow: none !important;
            border: 1px solid #ced4da;
        }

        /* =========================================
           5. RESPONSIVE & MOBILE ADAPTATION
           ========================================= */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                height: 100vh;
                z-index: 1050;
                transform: translateX(-100%);
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);
            }

            #sidebar.active {
                transform: translateX(0);
            }

            #content {
                width: 100%;
            }

            .main-content {
                padding: 15px;
            }

            .top-navbar {
                padding: 0 15px;
            }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            top: 0;
            left: 0;
            transition: all 0.3s;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <nav id="sidebar">
            <div class="sidebar-header fw-bold">
                🍔 Peta<span class="text-warning">Kuliner</span>
            </div>

            <ul class="list-unstyled components">
                <li class="<?= url_is('/') ? 'active' : '' ?>">
                    <a href="/"><i class="bi bi-house-door-fill"></i> Beranda</a>
                </li>

                <?php if (session()->get('logged_in')) : ?>
                    <li class="mt-2 <?= url_is('tambah-kuliner') ? 'active' : '' ?>">
                        <a href="/tambah-kuliner" style="color: #3BC1A8; font-weight: 600;"><i class="bi bi-plus-circle-fill"></i> Tambah Tempat</a>
                    </li>

                    <?php if (session()->get('role') !== 'admin') : ?>
                        <li class="<?= url_is('favorit') ? 'active' : '' ?>">
                            <a href="/favorit" style="font-weight: 600;">
                                <i class="bi bi-heart-fill" ></i> Favorit Saya

                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (session()->get('role') === 'admin') : ?>
                        <li class="mt-4 px-3 mb-1">
                            <small class="text-uppercase" style="color: rgba(255,255,255,0.5); font-weight: 700; font-size: 11px;">Panel Admin</small>
                        </li>
                        <li class="<?= url_is('admin/validasi*') ? 'active' : '' ?>">
                            <a href="/admin/validasi"><i class="bi bi-check2-square me-2"></i> Validasi Tempat</a>
                        </li>
                        <li class="<?= url_is('admin/places*') ? 'active' : '' ?>">
                            <a href="/admin/places"><i class="bi bi-shop me-2"></i> Kelola Tempat</a>
                        </li>
                        <li class="<?= url_is('admin/categories*') ? 'active' : '' ?>">
                            <a href="/admin/categories"><i class="bi bi-grid-fill"></i> Kelola Kategori</a>
                        </li>
                        <li class="<?= url_is('admin/tags*') ? 'active' : '' ?>">
                            <a href="/admin/tags"><i class="bi bi-tags-fill"></i> Kelola Tag</a>
                        </li>
                        <li class="<?= url_is('admin/vouchers*') ? 'active' : '' ?>">
                            <a href="/admin/vouchers"><i class="bi bi-ticket-perforated"></i> Kelola Voucher</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <ul class="list-unstyled" style="padding: 10px 0;">
                <?php if (session()->get('logged_in')) : ?>
                    <li>
                        <a href="/logout" style="color: #ff6b6b !important; font-weight: 600;"><i class="bi bi-box-arrow-left"></i> Logout</a>
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
                <button class="btn btn-outline-primary d-md-none" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <div class="d-flex align-items-center ms-auto">
                    <?php if (session()->get('logged_in')): ?>
                        <a href="/profile" class="btn btn-light border rounded-pill d-flex align-items-center shadow-sm px-3 py-1 text-decoration-none" style="background-color: #fff; border-color: #e0e6e6 !important;">
                            <span class="fw-bold me-2 d-none d-sm-inline" style="color: #005461;">Halo, <?= esc(session()->get('name')); ?></span>
                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                        </a>
                    <?php else: ?>
                        <span class="fst-italic" style="color: #839595;">Tamu (Belum Login)</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="main-content">
                <?= $this->renderSection('content'); ?>
            </div>
        </div>

    </div>

    <button type="button" class="btn btn-primary rounded-circle shadow-lg" id="btn-back-to-top" style="position: fixed; bottom: 25px; right: 25px; display: none; z-index: 9999; width: 45px; height: 45px; border: 2px solid #fff;">
        <i class="bi bi-arrow-up-short fs-5"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <script>
        // Back to top button logic
        let mybutton = document.getElementById("btn-back-to-top");
        let mainContent = document.querySelector('.main-content');

        if (mainContent) {
            mainContent.onscroll = function() {
                if (mainContent.scrollTop > 100) {
                    mybutton.style.display = "block";
                } else {
                    mybutton.style.display = "none";
                }
            };
            mybutton.addEventListener("click", function() {
                mainContent.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        }

        // Logika untuk Sidebar Mobile
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });
    </script>

    <?= $this->renderSection('scripts'); ?>
</body>

</html>