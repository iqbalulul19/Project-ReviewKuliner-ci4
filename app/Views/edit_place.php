<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-warning text-dark rounded-top-4">
                <h5 class="mb-0 fw-bold">✏️ Edit Tempat Kuliner</h5>
            </div>
            <div class="card-body p-4">
                <form action="/tempat/update/<?= $place['id']; ?>" method="post">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Tempat Kuliner</label>
                        <input type="text" name="name" class="form-control" value="<?= esc($place['name']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Tempat</label>
                        <select name="category_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id']; ?>" <?= $place['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                    <?= esc($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Lengkap</label>
                        <div class="input-group">
                            <input type="text" id="addressInput" name="address" class="form-control" value="<?= esc($place['address']); ?>" required>
                            <button class="btn btn-primary fw-bold" type="button" onclick="searchLocation()">Cari Koordinat</button>
                        </div>
                        <small id="statusPencarian" class="text-muted"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tag Karakteristik</label>
                        <div class="d-flex flex-wrap gap-3">
                            <?php if (!empty($tags)) : ?>
                                <?php foreach ($tags as $tag) : ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tags[]" value="<?= $tag['id']; ?>" id="tag_<?= $tag['id']; ?>"
                                            <?= in_array($tag['id'], $currentTags) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="tag_<?= $tag['id']; ?>">
                                            <?= esc($tag['name']); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p class="text-muted small"><em>Belum ada master data tag di database.</em></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Latitude</label>
                            <input type="text" id="latInput" name="latitude" class="form-control bg-light" value="<?= $place['latitude']; ?>" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Longitude</label>
                            <input type="text" id="lngInput" name="longitude" class="form-control bg-light" value="<?= $place['longitude']; ?>" readonly required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/admin/places" class="btn btn-outline-secondary rounded-pill px-4">Batal</a>
                        <button type="submit" class="btn btn-success fw-bold rounded-pill px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    function searchLocation() {
        let address = document.getElementById('addressInput').value;
        let status = document.getElementById('statusPencarian');

        if (address == '') {
            alert('Masukkan alamat dulu!');
            return;
        }

        status.innerHTML = "<i>Sedang mencari koordinat... ⏳</i>";

        fetch('/place/searchNominatim', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'address=' + encodeURIComponent(address)
            })
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    document.getElementById('latInput').value = data[0].lat;
                    document.getElementById('lngInput').value = data[0].lon;
                    status.innerHTML = "<span class='text-success fw-bold'>✅ Koordinat berhasil diperbarui!</span>";
                } else {
                    status.innerHTML = "<span class='text-danger fw-bold'>❌ Alamat tidak ditemukan. Coba lebih spesifik.</span>";
                }
            })
            .catch(error => {
                status.innerHTML = "<span class='text-danger fw-bold'>❌ Terjadi kesalahan jaringan.</span>";
            });
    }
</script>
<?= $this->endSection(); ?>