<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="row mt-3 mb-5">
    <div class="col-md-12">

        <div class="d-flex justify-content-between align-items-start mb-4 gap-3">

            <div class="flex-grow-1">
                <a href="/" class="btn btn-outline-secondary btn-sm mb-3 fw-bold rounded-pill px-3">
                    &larr; Kembali ke Peta
                </a>
                <h2 class="fw-bold text-primary mb-1"><?= esc($place['name']); ?></h2>
                <p class="text-muted fs-6 mb-0">
                    📍 <?= esc($place['address']); ?>
                </p>
            </div>

            <div class="mt-4 pt-2">
                <?php if (session()->get('logged_in') && session()->get('role') !== 'admin'): ?>
                    <?php
                    // Cek ke database apakah tempat ini sudah difavoritkan oleh user yang sedang login
                    $favModel = new \App\Models\FavoriteModel();
                    $isFavorited = $favModel->where(['user_id' => session()->get('user_id'), 'place_id' => $place['id']])->first();
                    ?>

                    <?php if ($isFavorited): ?>
                        <button class="btn btn-secondary btn-sm rounded-pill fw-bold shadow-sm px-3" disabled>
                            <i class="bi bi-check-circle-fill text-success"></i> Tersimpan di Favorit
                        </button>
                    <?php else: ?>
                        <form action="<?= base_url('favorit/add/' . $place['id']); ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill fw-bold shadow-sm px-3">
                                <i class="bi bi-heart-fill"></i> Tambah ke Favorit
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">📸 Galeri Foto</h5>

                <?php
                $adaFotoUlasan = false;
                foreach ($reviews as $rev) {
                    if (!empty($rev['photo'])) {
                        $adaFotoUlasan = true;
                        break;
                    }
                }
                ?>

                <?php if (empty($photos) && !$adaFotoUlasan) : ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-muted fst-italic mb-0">Belum ada foto untuk tempat ini.</p>
                    </div>

                <?php else : ?>
                    <div class="row g-3">

                        <?php foreach ($photos as $foto): ?>
                            <div class="col-md-3 col-6 position-relative">
                                <img src="<?= base_url('uploads/' . esc($foto['photo'])); ?>"
                                    class="img-fluid rounded-3 shadow-sm w-100"
                                    style="height: 200px; object-fit: cover;"
                                    alt="Galeri Admin">
                                <span class="badge bg-dark position-absolute top-0 end-0 m-2 shadow-sm">
                                    Official
                                </span>
                            </div>
                        <?php endforeach; ?>

                        <?php foreach ($reviews as $rev): ?>
                            <?php if (!empty($rev['photo'])): ?>
                                <div class="col-md-3 col-6 position-relative">
                                    <img src="<?= base_url('uploads/reviews/' . esc($rev['photo'])); ?>"
                                        class="img-fluid rounded-3 shadow-sm border border-primary w-100"
                                        style="height: 200px; object-fit: cover;"
                                        alt="Foto dari <?= esc($rev['name']); ?>">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <?php if (!empty($vouchers)): ?>
        <div class="card shadow-sm border-0 rounded-4 mb-4" style="background: linear-gradient(135deg, #0C7779, #249E94);">
            <div class="card-body p-4 text-white">
                <h5 class="fw-bold mb-3 border-bottom border-light pb-2"><i class="bi bi-ticket-perforated-fill"></i> Promo Spesial!</h5>
                
                <div class="row g-3">
                    <?php foreach ($vouchers as $v): ?>
                        <div class="col-md-6">
                            <div class="card bg-white text-dark rounded-3 shadow-sm h-100">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-bold text-primary mb-1"><?= esc($v['title']); ?></h6>
                                    <p class="small text-muted mb-2"><?= esc($v['description']); ?></p>
                                    
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted d-block">Harga Beli</small>
                                            <span class="fw-bold text-danger fs-5">Rp <?= number_format($v['price'], 0, ',', '.'); ?></span>
                                        </div>
                                        
                                        <?php if(session()->get('logged_in')): ?>
    <!-- Arahkan ke halaman konfirmasi -->
    <a href="/checkout/confirm/<?= $v['id']; ?>" class="btn btn-warning fw-bold btn-sm rounded-pill px-3 shadow-sm">
        Beli Sekarang
    </a>
<?php else: ?>
                                            <a href="/login" class="btn btn-outline-secondary btn-sm rounded-pill">Login untuk beli</a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-2 text-end">
                                        <small class="text-success fw-bold"><i class="bi bi-fire"></i> Sisa: <?= $v['stock']; ?> voucher</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-4 mb-4" style="background-color: #f8f9fa;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">✍️ Tulis Ulasanmu</h5>

                <?php if (session()->get('logged_in')) : ?>
                    <form action="/review/store" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="place_id" value="<?= $place['id']; ?>">

                        <div class="mb-3">
                            <label>Lampirkan Foto (Opsional)</label>
                            <input type="file" class="form-control" name="review_photo" accept="image/*">
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-dark">Berikan Rating:</label>
                                <select name="rating" class="form-select shadow-sm border-0" required>
                                    <option value="5">⭐⭐⭐⭐⭐ (5) - Sangat Enak!</option>
                                    <option value="4">⭐⭐⭐⭐ (4) - Enak</option>
                                    <option value="3">⭐⭐⭐ (3) - Biasa Saja</option>
                                    <option value="2">⭐⭐ (2) - Kurang</option>
                                    <option value="1">⭐ (1) - Kecewa</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold text-dark">Komentar:</label>
                                <textarea name="comment" class="form-control shadow-sm border-0" rows="2" placeholder="Bagaimana rasa makanan dan suasananya?" required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm">Kirim Ulasan</button>
                    </form>
                <?php else : ?>
                    <div class="alert alert-warning mb-0 border-0 shadow-sm text-center">
                        Silakan <a href="/login" class="fw-bold text-decoration-none">Login</a> atau <a href="/register" class="fw-bold text-decoration-none">Daftar</a> terlebih dahulu untuk memberikan ulasan.
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-5">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">💬 Ulasan Pengunjung</h5>
                <div class="d-flex align-items-center mb-3">
                    <?php if ($avg_rating > 0) : ?>
                        <div class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-star-fill text-dark me-1"></i> <?= $avg_rating; ?> / 5.0
                        </div>
                    <?php else : ?>
                        <div class="badge bg-secondary text-white fs-6 px-3 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-star text-white me-1"></i> Belum ada ulasan
                        </div>
                    <?php endif; ?>
                </div>


                
                <?php if(empty($reviews)) : ?>

                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Belum ada ulasan. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                <?php else : ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($reviews as $rev): ?>
                            <div class="card mb-3 border-0 shadow-sm rounded-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-primary fw-bold mb-0"><?= esc($rev['name']); ?></h6>

                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted"><?= date('d M Y, H:i', strtotime($rev['created_at'])); ?></small>

                                            <?php if (session()->get('user_id') == $rev['user_id']): ?>
                                                <div class="btn-group shadow-sm">
                                                    <a href="/review/edit/<?= esc($rev['id']); ?>" class="btn btn-sm btn-outline-primary px-2 rounded-start-3">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                    <a href="/review/user-delete/<?= esc($rev['id']); ?>"
                                                        class="btn btn-sm btn-danger text-white px-2 rounded-end-3"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus ulasan Anda?');">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </a>
                                                </div>

                                            <?php elseif (session()->get('role') === 'admin'): ?>
                                                <a href="/review/delete/<?= esc($rev['id']); ?>"
                                                    class="btn btn-sm btn-danger text-white shadow-sm px-3 rounded-3 d-flex align-items-center gap-1"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus ulasan pengguna lain ini? (Aksi Moderasi Admin)');">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>

                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="text-warning mb-2">
                                        <?php for ($i = 0; $i < $rev['rating']; $i++) {
                                            echo '⭐';
                                        } ?>
                                    </div>

                                    <p class="mb-3"><?= esc($rev['comment']); ?></p>

                                    <?php if (!empty($rev['photo'])): ?>
                                        <img src="<?= base_url('uploads/reviews/' . esc($rev['photo'])); ?>"
                                            alt="Foto dari <?= esc($rev['name']); ?>"
                                            class="img-fluid rounded shadow-sm"
                                            style="max-width: 250px; object-fit: cover;">
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>