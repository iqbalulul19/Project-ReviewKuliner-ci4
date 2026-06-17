<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary mb-4">📝 Edit Profil</h4>

                    <form action="/profile/update" method="post">
                        <?= csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Username</label>
                            <input type="text" class="form-control" value="<?= esc($user['username']); ?>" readonly>
                            <small class="text-muted fst-italic mt-1 d-block">
                                Username adalah identitas unik akun dan tidak dapat diubah.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="<?= esc($user['name']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Password Baru (Opsional)</label>
                            <input type="password" class="form-control" name="password" placeholder="Isi hanya jika ingin ganti password">
                            <small class="text-muted fst-italic">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>

                        <div class="row g-2">
                            <div class="col-8">
                                <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3">
                                    Simpan Perubahan
                                </button>
                            </div>
                            <div class="col-4">
                                <a href="/profile" class="btn btn-light border w-100 fw-bold rounded-3">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>