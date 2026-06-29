<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 p-4">
                <h4 class="fw-bold mb-3 text-center">Konfirmasi Pesanan</h4>
                <div class="alert alert-info">
                    <p class="mb-1">Anda akan membeli:</p>
                    <h5 class="fw-bold"><?= esc($voucher['title']); ?></h5>
                    <p class="mb-0 text-muted">Tempat: <?= esc($place['name']); ?></p>
                </div>
                
                <div class="d-flex justify-content-between align-items-center my-3">
                    <span class="text-muted">Total Tagihan:</span>
                    <h4 class="fw-bold text-primary">Rp <?= number_format($voucher['price'], 0, ',', '.'); ?></h4>
                </div>

                <form action="/doku-checkout/process/<?= $voucher['id']; ?>" method="POST">
                    <?= csrf_field(); ?>
                    <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold">
                        Lanjut ke Pembayaran DOKU
                    </button>
                </form>
                <a href="/place/detail/<?= $place['id']; ?>" class="btn btn-link w-100 text-muted mt-2">Batal</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>