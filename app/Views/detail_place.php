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
                
                <?php 
                // Logika Pengecekan: Apakah ada pengunjung yang upload foto?
                $adaFotoUlasan = false;
                foreach ($reviews as $rev) {
                    if (!empty($rev['photo'])) {
                        $adaFotoUlasan = true;
                        break;
                    }
                }
                ?>

                <?php if(empty($photos) && !$adaFotoUlasan) : ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-muted fst-italic mb-0">Belum ada foto untuk tempat ini.</p>
                    </div>
                
                <?php else : ?>
                    <div class="row g-3">
                        
                        <?php foreach($photos as $foto): ?>
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
                        
                        <?php foreach($reviews as $rev): ?>
                            <?php if(!empty($rev['photo'])): ?>
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
        
        <div class="card shadow-sm border-0 rounded-4 mb-4" style="background-color: #f8f9fa;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">✍️ Tulis Ulasanmu</h5>
                
<<<<<<< HEAD
                <?php if(session()->get('logged_in')) : ?>
=======
                <?php if(session()->get('isLoggedIn')) : ?>
>>>>>>> d3b33dfa51d49f28e6050712030ca60f4a87111b
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
                
                <?php if(empty($reviews)) : ?>
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Belum ada ulasan. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                <?php else : ?>
                    <div class="list-group list-group-flush">
                        <?php foreach($reviews as $rev): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-primary fw-bold mb-0"><?= esc($rev['name']); ?></h6>
                                                            
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted"><?= date('d M Y, H:i', strtotime($rev['created_at'])); ?></small>
                                                            
                                            <?php if(session()->get('user_id') == $rev['user_id']): ?>
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
                                            
                                            <?php elseif(session()->get('role') === 'admin'): ?>
                                                <a href="/review/delete/<?= esc($rev['id']); ?>" 
                                                   class="btn btn-sm btn-danger text-white shadow-sm px-3 rounded-3 d-flex align-items-center gap-1" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus ulasan pengguna lain ini? (Aksi Moderasi Admin)');">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                                
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="text-warning mb-2">
                                        <?php for($i=0; $i < $rev['rating']; $i++) { echo '⭐'; } ?>
                                    </div>
                                            
                                    <p class="mb-3"><?= esc($rev['comment']); ?></p>
                                            
                                    <?php if(!empty($rev['photo'])): ?>
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