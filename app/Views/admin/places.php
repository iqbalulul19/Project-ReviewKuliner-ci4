<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3 class="fw-bold mb-3">🏪 Kelola Tempat Kuliner</h3>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                    <?= session()->getFlashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Daftar Tempat Kuliner</h5>
                    <!-- Tombol ini mengarah ke halaman form tambah tempat yang sudah ada -->
                    <a href="/tambah-kuliner" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                        + Tambah Tempat
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="45%">Nama Tempat</th>
                                    <th width="30%">Alamat</th>
                                    <th width="20%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($places as $place) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="fw-semibold text-primary">
                                            <?= esc($place['name']); ?>
                                        </td>
                                        <td class="text-muted" style="font-size: 0.9em;">
                                            <?= esc($place['address']); ?>
                                        </td>
                                        <td class="text-center">
                                            <!-- Asumsi detail menggunakan ID atau Slug -->
                                            <a href="/tempat/edit/<?= $place['id']; ?>" class="btn btn-sm btn-warning rounded-pill px-3 text-white shadow-sm">Edit</a>
                                            <a href="/tempat/delete/<?= $place['id']; ?>" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Yakin ingin menghapus tempat kuliner ini secara permanen?');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($places)) : ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4 fst-italic">Belum ada data tempat kuliner. Silakan tambah tempat baru.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<?= $this->endSection(); ?>