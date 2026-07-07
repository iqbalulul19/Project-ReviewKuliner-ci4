<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<div class="row justify-content-center mt-4 mb-5">
  <div class="col-md-8">
    <div class="card shadow-sm border-0 rounded-4">
      <div class="card-header bg-primary text-white py-3 rounded-top-4">
        <h5 class="mb-0 fw-bold">Tambah Tempat Kuliner Baru</h5>
      </div>
      <div class="card-body p-4">

        <form action="/place/store" method="post" enctype="multipart/form-data">
          <?= csrf_field(); ?>

          <?php $errors = session()->getFlashdata('errors'); ?>
          <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger pb-0 rounded-3">
              <ul>
                <?php foreach ($errors as $error) : ?>
                  <li><?= esc($error); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label fw-bold">Nama Tempat Kuliner</label>
            <input type="text" name="name" class="form-control" value="<?= old('name'); ?>" placeholder="Contoh: Nasi Goreng Babat Pak Karmin">
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Kategori Tempat</label>
            <select name="category_id" class="form-select">
              <option value="">-- Pilih Kategori --</option>
              <?php
              $catModel = new \App\Models\CategoryModel();
              $categories = $catModel->findAll();
              foreach ($categories as $cat):
              ?>
                <option value="<?= $cat['id']; ?>" <?= old('category_id') == $cat['id'] ? 'selected' : ''; ?>>
                  <?= esc($cat['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Tag Tempat</label>
            <div class="d-flex flex-wrap gap-3">

              <?php if (!empty($tags)) : ?>
                <?php foreach ($tags as $tag) : ?>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tags[]" value="<?= $tag['id']; ?>" id="tag_<?= $tag['id']; ?>">
                    <label class="form-check-label" for="tag_<?= $tag['id']; ?>">
                      <?= esc($tag['name']); ?>
                    </label>
                  </div>
                <?php endforeach; ?>
              <?php else : ?>
                <p class="text-muted small"><em>Belum ada master data tag. Silakan isi di Kelola Tag terlebih dahulu.</em></p>
              <?php endif; ?>

            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Alamat Lengkap</label>
            <div class="input-group">
              <input type="text" id="addressInput" name="address" class="form-control" value="<?= old('address'); ?>" placeholder="Contoh: Jl. Pemuda Semarang">
              <button class="btn btn-warning fw-bold" type="button" id="btnCariKoordinat">Cari Koordinat 📍</button>
            </div>
            <small id="statusPencarian" class="text-muted"></small>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Latitude</label>
              <input type="text" id="latitude" name="latitude" class="form-control" value="<?= old('latitude'); ?>" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Longitude</label>
              <input type="text" id="longitude" name="longitude" class="form-control" value="<?= old('longitude'); ?>" readonly>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-bold">Preview Lokasi Peta</label>
            <div id="mapPreview" style="height: 350px; width: 100%; border-radius: 8px; z-index: 1;"></div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-bold">Upload Foto (Maksimal 3)</label>
            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
            <small class="text-muted">Bisa pilih lebih dari 1 foto. Otomatis di-resize maks 800px.</small>
          </div>

          <button type="submit" class="btn btn-success w-100 fw-bold py-2 rounded-pill shadow-sm">Simpan Tempat Kuliner</button>
        </form>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
  // Inisialisasi Peta (Default map terpusat di Semarang, TAPI TANPA MARKER)
  let initialLat = -6.9932;
  let initialLng = 110.4203;
  let zoomLevel = 13;

  const map = L.map('mapPreview').setView([initialLat, initialLng], zoomLevel);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
  }).addTo(map);

  let marker = null;

  function setMapMarker(lat, lng) {
    if (marker !== null) {
      marker.setLatLng([lat, lng]);
    } else {
      marker = L.marker([lat, lng], { draggable: false }).addTo(map);
    }
    map.setView([lat, lng], 17);
  }

  // Logika pencarian koordinat via Nominatim
  document.getElementById('btnCariKoordinat').addEventListener('click', function() {
    let alamat = document.getElementById('addressInput').value;
    let status = document.getElementById('statusPencarian');

    if (alamat.trim() === '') {
      status.innerHTML = "<span class='text-danger'><b>Silakan isi alamat terlebih dahulu. ❌</b></span>";
      return;
    }

    document.getElementById('latitude').value = '';
    document.getElementById('longitude').value = '';
    status.innerHTML = "<i>Sedang mencari koordinat ke Nominatim... ⏳</i>";

    let formData = new FormData();
    formData.append('address', alamat);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('/place/searchNominatim', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'error') {
            status.innerHTML = `<span class='text-danger'><b>${data.message} ❌</b></span>`;
        } 
        else if (data && data.length > 0) {
          let lat = parseFloat(data[0].lat);
          let lon = parseFloat(data[0].lon);

          document.getElementById('latitude').value = lat;
          document.getElementById('longitude').value = lon;
          status.innerHTML = "<span class='text-success'><b>Koordinat berhasil ditemukan! ✅</b></span>";

          setMapMarker(lat, lon);
        } 
        else {
          status.innerHTML = "<span class='text-danger'><b>Alamat tidak ditemukan di peta. ❌</b></span>";
        }
      })
      .catch(error => {
        console.error('Error:', error);
        status.innerHTML = "<span class='text-danger'><b>Gagal terhubung ke server. Periksa koneksi internetmu. ❌</b></span>";
      });
  });
</script>
<?= $this->endSection(); ?>