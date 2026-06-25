<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid mt-3 mb-5">
  <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <h2 class="fw-bold text-primary mb-0"><i class="bi bi-heart-fill me-2" style="color: #249E94;" ></i> Favorit Saya</h2>
  </div>

  <?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success shadow-sm border-0">
      <?= session()->getFlashdata('message') ?>
    </div>
  <?php endif; ?>

  <div class="row g-4">
    <?php if (!empty($places)): ?>
      <?php foreach ($places as $p): ?>
        <div class="col-md-4 col-sm-6">
          <div class="card h-100 shadow-sm border-0 rounded-4">

            <?php
            // Logika untuk menentukan folder dan menampilkan foto yang benar
            $fotoUrl = '';
            $adaFoto = false;

            // 1. Cek apakah ada data foto
            if (!empty($p['photo'])) {
              // Cek apakah file ada di folder uploads/ (foto galeri)
              if (file_exists(FCPATH . 'uploads/' . $p['photo'])) {
                $fotoUrl = base_url('uploads/' . $p['photo']);
                $adaFoto = true;
              }
              // Jika tidak ada, cek apakah ada di folder uploads/reviews/ (foto ulasan)
              elseif (file_exists(FCPATH . 'uploads/reviews/' . $p['photo'])) {
                $fotoUrl = base_url('uploads/reviews/' . $p['photo']);
                $adaFoto = true;
              }
            }
            ?>

            <?php if ($adaFoto): ?>
              <img src="<?= $fotoUrl ?>" class="card-img-top" style="height: 200px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;" alt="<?= esc($p['name']) ?>">
            <?php else: ?>
              <img src="https://ui-avatars.com/api/?name=<?= urlencode($p['name']) ?>&background=0C7779&color=fff&size=300" class="card-img-top" style="height: 200px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;" alt="<?= esc($p['name']) ?>">
            <?php endif; ?>

            <div class="card-body d-flex flex-column p-4">
              <h5 class="card-title fw-bold text-primary"><?= esc($p['name']) ?></h5>
              <p class="card-text text-muted small mb-3">📍 <?= esc($p['address']) ?></p>

              <div class="mt-auto d-flex flex-column gap-2">
                <a href="<?= base_url('tempat/' . $p['id']) ?>" class="btn btn-outline-primary fw-bold rounded-pill shadow-sm">Lihat Detail</a>

                <form action="<?= base_url('favorit/delete/' . $p['id']); ?>" method="post" class="w-100">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm">
                    <i class="bi bi-trash-fill"></i> Hapus Favorit
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center py-5">
        <i class="bi bi-heart-break fs-1 text-muted mb-3 d-block"></i>
        <h5 class="fw-bold text-muted">Belum ada tempat favorit</h5>
        <p class="text-muted">Cari tempat kuliner menarik dan tambahkan ke daftar favoritmu!</p>
        <a href="<?= base_url() ?>" class="btn btn-primary rounded-pill px-4 mt-2">Mulai Eksplorasi</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection(); ?>