<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-5">
        <div class="card shadow-lg border-0.5 rounded-4">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">📝 Daftar Akun</h3>
                    <p class="text-muted">Buat akun untuk mulai memberikan ulasan tempat kuliner favoritmu!</p>
                </div>
                
<form action="/auth/saveRegister" method="post">
    <?= csrf_field() ?>
    
    <div class="mb-3">
        <label class="form-label fw-semibold">Nama Lengkap</label>
        <input type="text" name="name" 
               class="form-control bg-light <?= session('errors.name') ? 'is-invalid' : 'border-0' ?>" 
               placeholder="Contoh: Budi Santoso" value="<?= old('name') ?>" autofocus>
               
        <?php if(session('errors.name')) : ?>
            <div class="invalid-feedback"><?= session('errors.name') ?></div>
        <?php endif; ?>
    </div>
    
    <div class="mb-3">
        <label class="form-label fw-semibold">Username</label>
        <input type="text" name="username" 
               class="form-control bg-light <?= session('errors.username') ? 'is-invalid' : 'border-0' ?>" 
               placeholder="Pilih username tanpa spasi" value="<?= old('username') ?>">
               
        <?php if(session('errors.username')) : ?>
            <div class="invalid-feedback"><?= session('errors.username') ?></div>
        <?php endif; ?>
    </div>
    
    <div class="mb-4">
        <label class="form-label fw-semibold">Password</label>
        <input type="password" name="password" 
               class="form-control bg-light <?= session('errors.password') ? 'is-invalid' : 'border-0' ?>" 
               placeholder="Buat password yang aman">
               
        <?php if(session('errors.password')) : ?>
            <div class="invalid-feedback"><?= session('errors.password') ?></div>
        <?php endif; ?>
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