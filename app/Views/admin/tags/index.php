<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<?php
// Mengambil flashdata dengan nama baru agar tidak bentrok
$validationErrors = session()->getFlashdata('tag_errors');
$modalAction      = session()->getFlashdata('modal_action');
$errorId          = session()->getFlashdata('error_id');
?>

<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">
      <h3 class="fw-bold mb-3">🏷️ Kelola Tag</h3>

      <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= session()->getFlashdata('success'); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">Daftar Tag</h5>
          <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            + Tambah Tag
          </button>
        </div>
        <div class="card-body">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th width="5%">No</th>
                <th>Nama Tag</th>
                <th width="20%" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1;
              foreach ($tags as $tag) : ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td class="fw-semibold">
                    <?= esc($tag['name']); ?>
                  </td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $tag['id']; ?>">Edit</button>
                    <a href="/admin/tags/delete/<?= $tag['id']; ?>" class="btn btn-sm btn-danger rounded-pill px-3" onclick="return confirm('Yakin ingin menghapus tag ini? Tempat kuliner yang memakai tag ini akan kehilangan label karakteristik tersebut.');">Hapus</a>
                  </td>
                </tr>

                <div class="modal fade" id="modalEdit<?= $tag['id']; ?>" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-4">
                      <div class="modal-header">
                        <h5 class="modal-title fw-bold">Edit Tag</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <form action="/admin/tags/update/<?= $tag['id']; ?>" method="post">
                        <?= csrf_field(); ?>
                        <div class="modal-body p-4">

                          <?php if (!empty($validationErrors) && $modalAction === 'edit' && $errorId == $tag['id']) : ?>
                            <div class="alert alert-danger" role="alert">
                              <h6 class="alert-heading fw-bold mb-1">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat Kesalahan:
                              </h6>
                              <hr class="mt-1 mb-2">
                              <ul class="mb-0 ps-3">
                                <?php foreach ($validationErrors as $error) : ?>
                                  <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                              </ul>
                            </div>
                          <?php endif; ?>

                          <div class="mb-3">
                            <label class="form-label fw-bold">Nama Tag</label>
                            <?php
                            $currentValue = esc($tag['name']);
                            if ($modalAction === 'edit' && $errorId == $tag['id'] && old('name') !== null) {
                              $currentValue = old('name');
                            }
                            ?>
                            <input type="text" name="name" class="form-control" value="<?= $currentValue; ?>">
                            <small class="text-muted d-block mt-1">Ganti namanya langsung tanpa memakai tanda pagar (#).</small>
                          </div>
                        </div>
                        <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                          <button type="submit" class="btn btn-success rounded-pill px-4">Simpan Perubahan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
              <?php if (empty($tags)) : ?>
                <tr>
                  <td colspan="3" class="text-center text-muted py-4 italic">Belum ada data tag. Silakan klik tombol Tambah Tag di atas.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header bg-primary text-white py-3 rounded-top-4">
        <h5 class="modal-title fw-bold">Tambah Tag Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="/admin/tags/store" method="post">
        <?= csrf_field(); ?>
        <div class="modal-body p-4">

          <?php if (!empty($validationErrors) && $modalAction === 'tambah') : ?>
            <div class="alert alert-danger" role="alert">
              <ul class="mb-0 ps-3">
                <?php foreach ($validationErrors as $error) : ?>
                  <li><?= esc($error) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label fw-bold">Nama Tag / Karakteristik</label>
            <?php
            $addValue = '';
            if ($modalAction === 'tambah' && old('name') !== null) {
              $addValue = old('name');
            }
            ?>
            <input type="text" name="name" class="form-control py-2" value="<?= $addValue; ?>" placeholder="Contoh: Aesthetic, Lesehan, Outdoor">
          </div>
        </div>
        <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
          <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Tag</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    <?php if ($modalAction === 'tambah') : ?>
      var modalTambah = new bootstrap.Modal(document.getElementById('modalTambah'));
      modalTambah.show();
    <?php elseif ($modalAction === 'edit' && !empty($errorId)) : ?>
      var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit<?= $errorId ?>'));
      modalEdit.show();
    <?php endif; ?>
  });
</script>

<?= $this->endSection(); ?>