<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">📝 Daftar Akun</h3>
                    <p class="text-muted">Buat akun untuk mulai memberikan ulasan tempat kuliner favoritmu!</p>
                </div>

                <?php if (session()->has('errors')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 ps-3">
                            <?php foreach (session('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="/auth/saveRegister" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control bg-light border-0"
                            placeholder="Contoh: Budi Santoso" value="<?= old('name') ?>" autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control bg-light border-0"
                            placeholder="Pilih username tanpa spasi" value="<?= old('username') ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control bg-light border-0"
                            placeholder="Buat password yang aman">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill py-2 shadow-sm">Daftar Sekarang</button>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted small">Sudah punya akun? <a href="/login" class="text-decoration-none fw-bold">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>