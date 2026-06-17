<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center mt-10 mb-5">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">🔐 Login</h3>
                
                <?php if(session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger text-center">
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

<form action="/auth/process" method="post">
    <?= csrf_field() ?>
    
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" 
               class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" 
               value="<?= old('username') ?>" autofocus>
               
        <?php if(session('errors.username')) : ?>
            <div class="invalid-feedback"><?= session('errors.username') ?></div>
        <?php endif; ?>
    </div>
    
    <div class="mb-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" 
               class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>">
               
        <?php if(session('errors.password')) : ?>
            <div class="invalid-feedback"><?= session('errors.password') ?></div>
        <?php endif; ?>
    </div>
    
    <button type="submit" class="btn btn-primary w-100 fw-bold">Login</button>
</form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>