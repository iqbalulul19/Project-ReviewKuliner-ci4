<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-4">
                
                <div style="height: 130px; background: linear-gradient(135deg, #0d6efd, #6610f2);"></div>
                
                <div class="card-body p-4 text-center position-relative">
                    
                    <div class="d-flex justify-content-center">
                        <div class="bg-white rounded-circle d-flex justify-content-center align-items-center shadow text-primary fw-bold border border-4 border-white" 
                             style="width: 110px; height: 110px; margin-top: -95px; background-color: #f8f9fa !important; font-size: 3rem;">
                            <?= strtoupper(substr($user['name'], 0, 1)); ?>
                        </div>
                    </div>

                    <div class="mt-3 mb-4">
                        <h3 class="fw-bold mb-1"><?= esc($user['name']); ?></h3>
                        <p class="text-muted mb-3">@<?= esc($user['username']); ?></p>
                        
                        <?php 
                            $roleClass = $user['role'] == 'admin' ? 'danger' : 'primary';
                            $roleIcon  = $user['role'] == 'admin' ? 'bi-shield-lock-fill' : 'bi-person-badge-fill';
                        ?>
                        <span class="badge bg-<?= $roleClass; ?> bg-opacity-10 text-<?= $roleClass; ?> border border-<?= $roleClass; ?> px-4 py-2 rounded-pill fs-6 shadow-sm">
                            <i class="bi <?= $roleIcon; ?> me-1"></i> <?= strtoupper($user['role']); ?>
                        </span>
                    </div>

                    <hr class="text-muted opacity-25 mb-4">

                    <div class="d-grid gap-3">
                        <a href="/profile/edit" class="btn btn-primary fw-bold rounded-4 py-2 shadow-sm d-flex justify-content-center align-items-center gap-2">
                            <i class="bi bi-pencil-square fs-5"></i> Edit Profil & Password
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>