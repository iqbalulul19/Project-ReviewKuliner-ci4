<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
    <div class="bg-light rounded-4 px-4 py-5 mb-5 shadow-sm text-center position-relative overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05);">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(13,110,253,0.05), rgba(102,16,242,0.05)); z-index: 0;"></div>
    
    <div class="position-relative" style="z-index: 1;">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 fw-bold border border-primary-subtle shadow-sm">
            🍔 Temukan Rasa, Dekat Kampus
        </span>
        <h1 class="fw-bold text-dark mb-3" style="font-weight: 800; letter-spacing: -1px;">
            Peta Kuliner Mahasiswa
        </h1>
        <p class="text-secondary fs-5 mx-auto mb-4" style="max-width: 600px;">
            Jelajahi rekomendasi tempat makan terbaik, termurah, dan paling enak di sekitar area kampus dengan mudah!
        </p>

        <form action="/" method="GET" class="mx-auto" style="max-width: 550px;">
            <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border bg-white">
                <span class="input-group-text bg-transparent border-0 text-muted ps-4">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="keyword" class="form-control border-0 shadow-none px-2" 
                       placeholder="Cari nama warung atau alamat..." 
                       value="<?= esc($keyword ?? ''); ?>">
                <button class="btn btn-primary px-4 fw-bold" type="submit">Cari</button>
            </div>
        </form>
        <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">
            <a href="/" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold">
                🌐 Semua
            </a>
            <?php 
                $catModel = new \App\Models\CategoryModel();
                $allCats = $catModel->findAll();
                foreach($allCats as $cat): 
            ?>
                <a href="/?keyword=<?= urlencode($cat['name']); ?>" class="btn btn-sm btn-light text-dark border rounded-pill px-3 shadow-sm transition-all hover-primary">
                    📁 <?= esc($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <?php if(!empty($keyword)): ?>
            <div class="mt-3">
                <small class="text-muted">Menampilkan hasil untuk: <strong>"<?= esc($keyword); ?>"</strong></small>
                <a href="/" class="ms-2 badge bg-danger text-decoration-none">Reset <i class="bi bi-x-circle"></i></a>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-lg border-0 rounded-4 mb-5 overflow-hidden">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold m-0 text-dark">
                <i class="bi bi-map text-primary me-2"></i> Jelajahi Peta
            </h5>
            <span class="badge bg-light text-dark border px-2 py-1 rounded-3">
                <i class="bi bi-geo-alt-fill text-danger"></i> <?= count($places); ?> Tempat Ditemukan
            </span>
        </div>
            
        <div class="card-body p-0 position-relative">
            <div id="map" style="height: 450px; width: 100%; z-index: 1;"></div>
        </div>
    </div>

    <style>
    /* Menyembunyikan scrollbar bawah tapi tetap bisa digeser */
    .horizontal-scroll {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 1.5rem; /* Jarak antar card */
        padding-bottom: 1rem;
        scroll-snap-type: x mandatory; /* Efek magnet saat digeser */
    }
    .horizontal-scroll::-webkit-scrollbar {
        display: none; /* Khusus Chrome/Safari/Edge */
    }
    .horizontal-scroll {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    /* Memastikan ukuran card tetap dan tidak menyusut */
    .card-item {
        flex: 0 0 auto;
        width: 85%; /* Lebar di HP (sisakan sedikit agar user tahu bisa digeser) */
        max-width: 350px; /* Lebar maksimal di Laptop */
        scroll-snap-align: start; /* Titik henti efek magnet */
    }
    /* CSS Opsional untuk Popup Peta (Rounded & Shadow) */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .leaflet-popup-tip {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>

<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3 mt-4">
    <h4 class="fw-bold m-0">
        <i class="bi bi-geo-alt-fill text-danger"></i> Rekomendasi Terpopuler
    </h4>
    <small class="text-muted fst-italic">
        Geser untuk melihat <i class="bi bi-arrow-right"></i>
    </small>
</div>

<div class="horizontal-scroll">
    <?php foreach($places as $place) : ?>
        <div class="card-item">
            <div class="card shadow-sm h-100 rounded-4 overflow-hidden">
                
                <?php if (!empty($place['cover_image'])): ?>
                    <img src="<?= base_url($place['cover_path'] . $place['cover_image']); ?>" 
                         class="card-img-top" 
                         style="height: 200px; width: 100%; object-fit: cover;" 
                         alt="Foto <?= esc($place['name']); ?>">
                <?php else: ?>
                    <div class="bg-light d-flex justify-content-center align-items-center border-bottom" style="height: 200px; width: 100%;">
                        <span class="text-muted"><i class="bi bi-camera"></i> Belum ada foto</span>
                    </div>
                <?php endif; ?>
                
                <div class="card-body p-3">
                    <h5 class="card-title fw-bold text-dark text-truncate mb-2" title="<?= esc($place['name']); ?>">
                        <?= esc($place['name']); ?>
                    </h5>
                    <p class="card-text text-muted small text-truncate mb-0">
                        <i class="bi bi-geo-alt-fill text-danger"></i> <?= esc($place['address']); ?>
                    </p>
                </div>
                
                <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
                    <a href="/tempat/<?= esc($place['id']); ?>" 
                       class="btn btn-outline-primary btn-sm w-100 fw-bold rounded-pill shadow-sm d-flex justify-content-center align-items-center gap-2">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                </div>

            </div>
        </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
    // 1. Inisialisasi Peta (Sesuaikan koordinat tengah & zoom jika perlu)
    var map = L.map('map').setView([-6.982, 110.409], 14); 

    // 2. Load Gambar Peta dari OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 3. MANTRA SAKTI: Mengubah data PHP $places dari Controller menjadi Array JavaScript JSON
    // 👇 Ganti '$places' jika nama variabel dari controller-mu berbeda
    var dataTempat = <?= json_encode($places); ?>; 

    // 4. Lakukan Perulangan untuk Menggambar Pin
    dataTempat.forEach(function(place) {
        
        // Pengecekan: Pastikan koordinat tidak kosong di database
        // ⚠️ PENTING: Cek apakah nama kolom di DB-mu 'latitude'/'longitude' atau 'lat'/'lng'
        if (place.latitude && place.longitude) {
            
            // Gambar Pin ke Peta
            var marker = L.marker([place.latitude, place.longitude]).addTo(map);

            // Susun Konten Pop-up saat Pin di-klik (Sudah diperbaiki formatnya)
            var popupContent = `
                <div class="text-center" style="max-width: 200px; font-family: sans-serif;">
                    <h6 class="fw-bold text-primary mb-1" style="font-size: 14px;">${place.name}</h6>
                    <p class="text-muted small mb-3" style="font-size: 11px; line-height: 1.3;">${place.address}</p>
                    
                    <a href="/tempat/${place.id}" 
                       class="btn btn-outline-primary btn-sm rounded-pill shadow-sm d-inline-flex align-items-center justify-content-center gap-1 w-100 fw-bold"
                       style="font-size: 11px; padding: 5px 10px; text-decoration: none;">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                </div>
            `;

            // Tempelkan Popup ke Pin
            marker.bindPopup(popupContent, {
                maxWidth: 220,
                closeButton: true
            });
        }
    });
</script>

<?= $this->endSection(); ?>