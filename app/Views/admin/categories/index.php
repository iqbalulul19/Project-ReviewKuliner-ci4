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
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
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
                                    <button class="btn btn-sm btn-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $cat['id']; ?>">Edit</button>
                                    <a href="/admin/categories/delete/<?= $cat['id']; ?>" class="btn btn-sm btn-danger rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus kategori ini? Tempat kuliner dengan kategori ini bisa kehilangan referensinya.');">Hapus</a>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalEdit<?= $cat['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="/admin/categories/update/<?= $cat['id']; ?>" method="post">
                                            <?= csrf_field(); ?>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Kategori</label>
                                                    <input type="text" name="name" class="form-control" value="<?= esc($cat['name']); ?>">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success rounded-pill px-4">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/categories/store" method="post">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    
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
                        <label class="form-label fw-bold">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" value="<?= old('name'); ?>" placeholder="Contoh: Aneka Jus & Minuman">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(session()->getFlashdata('errorValidation')) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('modalTambah'));
            myModal.show();
        });
    </script>
<?php endif; ?>
<?= $this->endSection(); ?>