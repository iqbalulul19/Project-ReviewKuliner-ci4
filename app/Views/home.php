<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="bg-light rounded-4 px-4 py-5 mb-5 shadow-sm text-center position-relative overflow-hidden header-banner" style="border: 1px solid rgba(0,0,0,0.05);">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(13,110,253,0.05), rgba(102,16,242,0.05)); z-index: 0;"></div>

    <div class="position-relative" style="z-index: 1;">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3 fw-bold border border-primary-subtle shadow-sm">
            🍔 Temukan Rasa, Dekat Kampus
        </span>
        <h1 class="fw-bold text-dark mb-3 title-main" style="font-weight: 800; letter-spacing: -1px;">
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
            foreach ($allCats as $cat):
            ?>
                <a href="/?keyword=<?= urlencode($cat['name']); ?>" class="btn btn-sm btn-light text-dark border rounded-pill px-3 shadow-sm transition-all hover-primary">
                    📁 <?= esc($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($keyword)): ?>
            <div class="mt-3">
                <small class="text-muted">Menampilkan hasil untuk: <strong>"<?= esc($keyword); ?>"</strong></small>
                <a href="/" class="btn" style="color: white !important; font-size: 0.700rem; background-color: #dc3545 !important; padding: 0.350rem 0.70rem;">Reset ⊗</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow-lg border-0 rounded-4 mb-5 overflow-hidden">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="padding-top: 1.5rem !important; padding-bottom: 1.25rem !important;">
        <h5 class="fw-bold m-0 text-light" style="color: #0C7779 !important;">
            <i class="bi bi-map-fill me-2"></i>Jelajahi Peta
        </h5>
        <span class="badge bg-light text-dark border px-2 py-1 rounded-3">
            <i class="bi bi-geo-alt-fill text-danger"></i> <?= count($places); ?> Tempat Ditemukan
        </span>
    </div>

    <div class="card-body p-0 position-relative">
        <div id="map" style="width: 100%; z-index: 1;"></div>
    </div>
</div>

<style>
    #map {
        height: 450px;
    }

    @media (max-width: 768px) {
        #map {
            height: 300px;
        }

        .header-banner {
            padding: 2rem 1rem !important;
        }

        .title-main {
            font-size: 1.8rem;
        }
    }

    .horizontal-scroll {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 1.5rem;
        padding-bottom: 1rem;
        scroll-snap-type: x mandatory;
    }

    .horizontal-scroll::-webkit-scrollbar {
        display: none;
    }

    .horizontal-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .card-item {
        flex: 0 0 auto;
        width: 85%;
        max-width: 350px;
        scroll-snap-align: start;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .leaflet-popup-tip {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
    <?php foreach ($places as $place) : ?>
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
                    <?php if ($place['avg_rating'] > 0) : ?>
                        <span class="text-warning fw-bold"><i class="bi bi-star-fill"></i> <?= $place['avg_rating']; ?></span>
                        <span class="text-muted small">/ 5.0</span>
                    <?php else : ?>
                        <span class="text-muted small"><i>Belum ada ulasan</i></span>
                    <?php endif; ?>
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
    var map = L.map('map').setView([-6.982, 110.409], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var dataTempat = <?= json_encode($places); ?>;

    dataTempat.forEach(function(place) {

        if (place.latitude && place.longitude) {

            var marker = L.marker([place.latitude, place.longitude]).addTo(map);

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

            marker.bindPopup(popupContent, {
                maxWidth: 220,
                closeButton: true
            });
        }
    });
</script>

<?= $this->endSection(); ?>