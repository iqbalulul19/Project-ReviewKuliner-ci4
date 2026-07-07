<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    .btn-pay {
        background-color: #38C193; 
        color: #ffffff !important; 
        border: none;
        transition: all 0.3s ease;
    }
    .btn-pay:hover {
        background-color: #2A9D76;
        color: #ffffff !important;
        transform: translateY(-2px); 
        box-shadow: 0 6px 15px rgba(56, 193, 147, 0.4);
    }

    .btn-cancel {
        background-color: #fcfcfc;
        color: #dc3545 !important; 
        border: 1px solid #f5c2c7;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        background-color: #dc3545;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(220, 53, 69, 0.2);
    }
</style>

<div class="container-fluid pt-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                
                <div class="card-header border-0 text-center pt-4 pb-3" style="background-color: #0C7779;">
                    <h4 class="fw-bold text-white mb-1">Konfirmasi Pesanan</h4>
                    <p class="text-white-50 small mb-0">Periksa detail pesanan sebelum melanjutkan</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <div class="p-4 mb-4 rounded-3" style="background-color: #f8f9fa; border: 1px solid #e2e8f0;">
                        <span class="badge bg-success mb-2 px-3 py-2 rounded-pill shadow-sm">Detail Item</span>
                        
                        <h5 class="fw-bold text-dark mb-1">
                            <?= esc($voucher['title']); ?>
                        </h5>
                        
                        <p class="text-muted mb-0 small">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= esc($place['name']); ?>
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <span class="fw-semibold text-secondary">Total Tagihan</span>
                        <h3 class="fw-bold text-success mb-0">
                            Rp <?= number_format($voucher['price'], 0, ',', '.'); ?>
                        </h3>
                    </div>

                    <div class="d-grid gap-3 mt-4">
                        <a href="<?= base_url('checkout/process/' . $voucher['id']) ?>" class="btn btn-pay btn-lg rounded-pill fw-bold">
                            <i class="bi bi-shield-check me-1"></i> Bayar via DOKU
                        </a>
                        
                        <a href="<?= base_url('tempat/' . $voucher['place_id']) ?>" class="btn btn-cancel btn-lg rounded-pill fw-bold">
                            Batalkan
                        </a>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</div>

<?= $this->endSection(); ?>