<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Tambah Tempat Kuliner Baru</h5>
      </div>
      <div class="card-body">
        <form action="/place/store" method="post" enctype="multipart/form-data"> 
          <?= csrf_field(); ?>

          <div class="mb-3">
            <label class="form-label fw-bold">Nama Tempat Kuliner</label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: Nasi Goreng Babat Pak Karmin">
          </div>

          <div class="mb-3">
              <label class="form-label fw-bold">Kategori Tempat</label>
              <select name="category_id" class="form-select rounded-3" required>
                  <option value="">-- Pilih Kategori --</option>
                  <?php 
                      $catModel = new \App\Models\CategoryModel();
                      $categories = $catModel->findAll();
                      foreach($categories as $cat): 
                  ?>
                      <option value="<?= $cat['id']; ?>"><?= esc($cat['name']); ?></option>
                  <?php endforeach; ?>
              </select>
          </div>
                      
          <div class="mb-3">
            <label class="form-label fw-bold">Alamat Lengkap</label>
            <div class="input-group">
              <input type="text" id="addressInput" name="address" class="form-control" required placeholder="Contoh: Jl. Pemuda Semarang">
              <button class="btn btn-warning" type="button" id="btnCariKoordinat">Cari Koordinat 📍</button>
            </div>
            <small id="statusPencarian" class="text-muted"></small>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Latitude</label>
              <input type="text" id="latitude" name="latitude" class="form-control" readonly required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Longitude</label>
              <input type="text" id="longitude" name="longitude" class="form-control" readonly required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Upload Foto (Maksimal 3)</label>
            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
            <small class="text-muted">Bisa pilih lebih dari 1 foto. Otomatis di-resize maks 800px.</small>
          </div>
          
          <button type="submit" class="btn btn-success w-100">Simpan Tempat Kuliner</button>
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
    
    // 👇 KUNCI PERBAIKAN 1: Masukkan Token CSRF agar tidak diblokir CodeIgniter
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('/place/searchNominatim', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data && data.length > 0) {
          // Berhasil! Ambil array indeks ke-0 sesuai spesifikasi D.4
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