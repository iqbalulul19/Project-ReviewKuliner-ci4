<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">
      <h3 class="fw-bold mb-3">✅ Validasi Tempat Kuliner Baru</h3>

      <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
          <?= session()->getFlashdata('success'); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold">Daftar Menunggu Validasi</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th width="5%">No</th>
                  <th width="35%">Nama Tempat</th>
                  <th width="35%">Alamat</th>
                  <th width="25%" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1;
                foreach ($places as $place) : ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td class="fw-semibold text-dark">
                      <?= esc($place['name']); ?> <span class="badge bg-warning text-white ms-1" style="font-size: 0.75em;">Pending</span>
                    </td>
                    <td class="text-muted" style="font-size: 0.9em;">
                      <?= esc($place['address']); ?>
                    </td>
                    <td class="text-center">
                      <a href="/admin/validasi/approve/<?= $place['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3 text-white shadow-sm me-1" onclick="return confirm('Apakah Anda yakin ingin menyetujui tempat kuliner ini?');">
                        <i class="bi bi-check-lg"></i> Setuju
                      </a>
                      <a href="/admin/validasi/reject/<?= $place['id']; ?>" class="btn btn-sm btn-danger rounded-pill px-3 text-white shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini? Data akan dihapus.');">
                        <i class="bi bi-x-lg"></i> Tolak
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>

                <?php if (empty($places)) : ?>
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4 fst-italic">Tidak ada pengajuan tempat baru yang perlu divalidasi.</td>
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