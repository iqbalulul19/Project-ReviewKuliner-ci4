<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center mt-3 mb-5">
    <div class="col-md-9">
        
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <a href="/" class="btn btn-outline-secondary btn-sm mb-3 fw-bold rounded-pill px-3">
                    &larr; Kembali ke Peta
                </a>
                <h2 class="fw-bold text-primary mb-1"><?= esc($place['name']); ?></h2>
                <p class="text-muted fs-6">
                    📍 <?= esc($place['address']); ?>
                </p>
            </div>

            <?php if(session()->get('role') === 'admin') : ?>
                <div>
                    <a href="/tempat/edit/<?= $place['id']; ?>" class="btn btn-warning btn-sm fw-bold rounded-pill px-3 shadow-sm me-1">
                        ✏️ Edit
                    </a>
                    <a href="/tempat/delete/<?= $place['id']; ?>" class="btn btn-danger btn-sm fw-bold rounded-pill px-3 shadow-sm" onclick="return confirm('Yakin ingin menghapus tempat ini?');">
                        🗑️ Hapus
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">📸 Galeri Foto</h5>
                <div class="row g-3">
                    <?php if(empty($photos)) : ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted fst-italic mb-0">Belum ada foto untuk tempat ini.</p>
                        </div>
                    <?php else : ?>
                        <?php foreach($photos as $foto) : ?>
                            <div class="col-md-4">
                                <img src="/uploads/<?= esc($foto['photo']); ?>" class="img-fluid rounded-3 shadow-sm w-100" style="height: 200px; object-fit: cover;" alt="Foto <?= esc($place['name']); ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm border-0 rounded-4 mb-4" style="background-color: #f8f9fa;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">✍️ Tulis Ulasanmu</h5>
                
                <?php if(session()->get('isLoggedIn')) : ?>
                    <form action="/review/store" method="post">
                        <input type="hidden" name="place_id" value="<?= $place['id']; ?>">
                        
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
                
                <?php if(empty($reviews)) : ?>
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Belum ada ulasan. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                <?php else : ?>
                    <div class="list-group list-group-flush">
                        <?php foreach($reviews as $r) : ?>
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="d-block text-primary mb-1"><?= esc($r['name']); ?></strong>
                                        <span class="fs-5"><?= str_repeat('⭐', $r['rating']); ?></span>
                                    </div>
                                    <small class="text-muted bg-light px-2 py-1 rounded-pill" style="font-size: 12px;">
                                        <?= date('d M Y, H:i', strtotime($r['created_at'])); ?>
                                    </small>
                                </div>
                                <p class="mb-0 mt-2 text-dark"><?= esc($r['comment']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>