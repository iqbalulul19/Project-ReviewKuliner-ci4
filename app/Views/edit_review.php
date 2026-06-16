<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-1">📝 Edit Ulasan Anda</h4>
                    <p class="text-muted mb-4">Tempat: <strong><?= esc($place['name']); ?></strong></p>

                    <form action="/review/update/<?= $review['id']; ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rating Bintang</label>
                            <select name="rating" class="form-select rounded-3" required>
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <option value="<?= $i; ?>" <?= $review['rating'] == $i ? 'selected' : ''; ?>>
                                        <?= str_repeat('⭐', $i); ?> (<?= $i; ?> Bintang)
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ulasan Anda</label>
                            <textarea name="comment" class="form-control rounded-3" rows="4" required><?= esc($review['comment']); ?></textarea>
                        </div>

                        <?php if(!empty($review['photo'])): ?>
                            <div class="mb-2">
                                <label class="form-label d-block text-muted small">Foto Saat Ini:</label>
                                <img src="<?= base_url('uploads/reviews/' . $review['photo']); ?>" class="img-fluid rounded-3" style="max-height: 120px;">
                            </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Ganti Foto (Opsional)</label>
                            <input type="file" class="form-control rounded-3" name="review_photo" accept="image/*">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3">Simpan Perubahan</button>
                            <a href="/tempat/<?= $review['place_id']; ?>" class="btn btn-light w-100 fw-bold rounded-3">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>