<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center mt-4">
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
                      foreach($categories as $cat): 
                  ?>
                      <option value="<?= $cat['id']; ?>" <?= old('category_id') == $cat['id'] ? 'selected' : ''; ?>>
                          <?= esc($cat['name']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
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
<script>
  document.getElementById('btnCariKoordinat').addEventListener('click', function() {
    let alamat = document.getElementById('addressInput').value;
    let status = document.getElementById('statusPencarian');

    if (alamat === '') {
      alert('Isi alamatnya dulu ya!');
      return;
    }

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
        if (data && data.length > 0) {
          document.getElementById('latitude').value = data[0].lat;
          document.getElementById('longitude').value = data[0].lon;
          status.innerHTML = "<span class='text-success'><b>Koordinat berhasil ditemukan! ✅</b></span>";
        } else {
          status.innerHTML = "<span class='text-danger'><b>Alamat tidak ditemukan, coba lebih spesifik atau cek koneksi internet. ❌</b></span>";
        }
      })
      .catch(error => {
        console.error('Error:', error);
        status.innerHTML = "<span class='text-danger'><b>Terjadi kesalahan jaringan atau token kedaluwarsa.</b></span>";
      });
  });
</script>
<?= $this->endSection(); ?>