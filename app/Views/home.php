<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row">
  <div class="col-12 text-center mb-4">
    <h1>Peta Kuliner Mahasiswa</h1>
    <p>Temukan rekomendasi tempat makan terbaik di sekitar area kampus!</p>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card shadow">
      <div class="card-body p-0">
        <div id="map" style="height: 500px; width: 100%; border-radius: 5px;"></div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
  // Koordinat area kampus Udinus
  var map = L.map('map').setView([-6.982500, 110.409000], 15);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  // Looping data dari database
  <?php foreach ($places as $p) : ?>
    L.marker([<?= $p['latitude']; ?>, <?= $p['longitude']; ?>])
      .addTo(map)
      .bindPopup("<b><?= $p['name']; ?></b><br><?= $p['address']; ?>");
  <?php endforeach; ?>
</script>
<?= $this->endSection(); ?>