<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3 class="fw-bold mb-3">🛠️ Kelola Kategori Kuliner</h3>
            
            <?php if(session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Daftar Kategori</h5>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                        + Tambah Kategori
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Kategori</th>
                                <th width="20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($categories as $cat) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-semibold"><?= esc($cat['name']); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning rounded-pill px-3 text-white" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $cat['id']; ?>">Edit</button>
                                    <a href="/admin/categories/delete/<?= $cat['id']; ?>" class="btn btn-sm btn-danger rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus kategori ini? Tempat kuliner dengan kategori ini bisa kehilangan referensinya.');">Hapus</a>
                                </td>
                            </tr>
                            
                            <div class="modal fade" id="modalEdit<?= $cat['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <div class="modal-header bg-primary text-white border-0">
                                            <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="/admin/categories/update/<?= $cat['id']; ?>" method="post">
                                            <?= csrf_field(); ?>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold text-dark mb-2">Nama Kategori</label>
                                                    <input type="text" name="name" class="form-control px-3 py-2" value="<?= esc($cat['name']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                                                <button type="submit" class="btn btn-warning text-white rounded-pill px-4 fw-bold shadow-sm">Update Kategori</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if (empty($categories)) : ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4 fst-italic">Belum ada data kategori. Silakan klik tombol Tambah Kategori di atas.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/categories/store" method="post">
                <?= csrf_field(); ?>
                <div class="modal-body p-4">
                    
                    <?php $errorsCat = session()->getFlashdata('errorValidation'); ?>
                    <?php if (!empty($errorsCat)) : ?>
                        <div class="alert alert-danger pb-0 rounded-3">
                            <ul>
                                <?php foreach ($errorsCat as $error) : ?>
                                    <li><?= esc($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark mb-2">Nama Kategori</label>
                        <input type="text" name="name" class="form-control px-3 py-2" value="<?= old('name'); ?>" placeholder="Contoh: Aneka Jus & Minuman" required>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(session()->getFlashdata('errorValidation')) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // PERBAIKAN: Targetnya diubah ke #modalTambahKategori
            var myModal = new bootstrap.Modal(document.getElementById('modalTambahKategori'));
            myModal.show();
        });
    </script>
<?php endif; ?>
<?= $this->endSection(); ?>