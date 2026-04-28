<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
    <div class="row mb-4 mt-2">
        <div class="col-12 text-center">
            <h1 class="fw-bold text-primary">🗺️ Peta Kuliner Mahasiswa</h1>
            <p class="text-muted">Jelajahi rekomendasi tempat makan terbaik, termurah, dan paling enak di sekitar area kampus!</p>
            <hr class="w-25 mx-auto opacity-75">
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div id="map" style="height: 450px; width: 100%; z-index: 1;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <h4 class="fw-bold border-bottom pb-2">📍 Rekomendasi Terpopuler</h4>
        </div>
    </div>

    <div class="row">
        <?php if(empty($places)) : ?>
            <div class="col-12 text-center my-4">
                <p class="text-muted">Belum ada data tempat kuliner. Yuk, klik tombol <b>+ Tambah Tempat</b> di atas!</p>
            </div>
        <?php else : ?>
            <?php foreach($places as $p) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        
                        <?php if(!empty($p['thumbnail'])) : ?>
                            <img src="/uploads/<?= esc($p['thumbnail']); ?>" class="card-img-top" alt="<?= esc($p['name']); ?>" style="height: 180px; object-fit: cover;">
                        <?php else : ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-muted" style="height: 180px;">
                                <span>📷 Belum ada foto</span>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title fw-bold text-dark text-truncate" title="<?= esc($p['name']); ?>">
                                <?= esc($p['name']); ?>
                            </h5>
                            <p class="card-text text-muted small mb-3">
                                🏠 <?= esc($p['address']); ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3 pt-0">
                            <a href="/tempat/<?= $p['id']; ?>" class="btn btn-outline-primary btn-sm w-100 fw-bold">Lihat Detail & Ulasan</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
    // Set view awal peta
var map = L.map('map').setView([-6.982631, 110.409245], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Looping data dari database kita (Pin Biru)
    <?php foreach($places as $p) : ?>
        <?php if(!empty($p['latitude']) && !empty($p['longitude'])) : ?>
            L.marker([<?= $p['latitude']; ?>, <?= $p['longitude']; ?>])
                .addTo(map)
                .bindPopup("<h6 class='mb-1'><b><?= esc($p['name']); ?></b></h6><p class='mb-2' style='font-size:12px;'><?= esc($p['address']); ?></p><hr class='my-1'><a href='/tempat/<?= $p['id']; ?>' class='btn btn-sm btn-primary w-100'>Lihat Detail</a>");
        <?php endif; ?>
    <?php endforeach; ?>
</script>

<?= $this->endSection(); ?>