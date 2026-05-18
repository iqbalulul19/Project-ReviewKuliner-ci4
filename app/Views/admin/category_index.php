<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">➕ Tambah Kategori</h5>
                    <form action="/admin/category/store" method="post">
                        <?= csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Kategori</label>
                            <input type="text" class="form-control rounded-3" name="name" placeholder="Contoh: Cafe, Angkringan" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success border-0 rounded-3 mb-3"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-3">🗂️ Daftar Kategori Saat Ini</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">No</th>
                                    <th>Nama Kategori</th>
                                    <th>Slug URL</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach($categories as $c): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="fw-bold text-secondary"><?= esc($c['name']); ?></td>
                                    <td><code><?= esc($c['slug']); ?></code></td>
                                    <td class="text-center">
                                        <a href="/admin/category/delete/<?= $c['id']; ?>" class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Hapus kategori ini?')">
                                            <i class="bi bi-trash"></i>
                                        </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>