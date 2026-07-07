<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid px-4 mt-4">
    <h3 class="fw-bold text-dark mb-4">🎟️ Kelola Voucher Kuliner</h3>
        <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold">Daftar Voucher</h5>
          <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#tambahVoucherModal">
            <i class="bi bi-plus-lg"></i> Voucher Baru
          </button>
        </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Tempat</th>
                            <th>Voucher</th>
                            <th>Harga</th>
                            <th>Terjual</th>
                            <th>Sisa</th>
                            <th>Expired</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($vouchers as $v): ?>
                        <tr>
                            <td><?= $v['place_id'] ?></td>
                            <td><?= $v['title'] ?></td>
                            <td>Rp <?= number_format($v['price'], 0, ',', '.') ?></td>
                            <td><span class="badge bg-success"><?= $v['terjual'] ?></span></td>
                            <td><span class="badge bg-info text-dark"><?= $v['sisa'] ?></span></td>
                            <td><?= $v['expired_at'] ?></td>
                            <td> 
                                <button type="button" 
                                        class="btn btn-sm btn-link text-warning p-0 me-2" 
                                        onclick="editVoucher(<?= htmlspecialchars(json_encode($v)) ?>)">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </button>
                                <a href="/admin/vouchers/delete/<?= $v['id'] ?>" 
                                   class="btn btn-sm btn-link text-danger p-0" 
                                   title="Hapus"
                                   onclick="return confirm('Yakin ingin hapus?')">
                                   <i class="bi bi-trash fs-5"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tambahVoucherModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">Tambah Voucher Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="/admin/vouchers/store" method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tempat Kuliner</label>
                            <select name="place_id" class="form-select" required>
                                <option value="">-- Pilih Tempat --</option>
                                <?php foreach($places as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Judul Voucher</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Diskon Makan" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Diskon</label>
                            <input type="number" name="discount_value" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stok</label>
                            <input type="number" name="stock" class="form-control" required>
                        </div>
<<<<<<< HEAD
=======
                        <!-- TAMBAHAN KOLOM DESKRIPSI -->
>>>>>>> 182126222d0ec10dd9f8946fc95789d2be08206a
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Jelaskan detail diskon..."></textarea>
                        </div>
<<<<<<< HEAD
=======
                        <!-- END TAMBAHAN -->
>>>>>>> 182126222d0ec10dd9f8946fc95789d2be08206a
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Tanggal Kadaluwarsa</label>
                            <input type="date" name="expired_at" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-0 pb-0 mt-3">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Voucher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editVoucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <form id="editForm" action="" method="post">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Edit Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <input type="hidden" name="id" id="edit_id">
                        <!-- Input form sama persis dengan form tambah, pastikan id nya ada -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Judul Voucher</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="number" name="price" id="edit_price" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok</label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Kadaluwarsa</label>
                            <input type="date" name="expired_at" id="edit_expired_at" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="2" placeholder="Jelaskan detail diskon..."></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Tempat Kuliner</label>
                            <select name="place_id" id="edit_place_id" class="form-select" required>
                                <option value="">-- Pilih Tempat --</option>
                                <?php foreach($places as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="submit" class="btn btn-warning rounded-pill px-4 text-white fw-bold">Update Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editVoucher(voucher) {
<<<<<<< HEAD
=======
    // Isi nilai input modal dengan data dari row tabel
>>>>>>> 182126222d0ec10dd9f8946fc95789d2be08206a
    document.getElementById('editForm').action = '/admin/vouchers/update/' + voucher.id;
    document.getElementById('edit_id').value = voucher.id;
    document.getElementById('edit_title').value = voucher.title;
    document.getElementById('edit_price').value = voucher.price;
    document.getElementById('edit_stock').value = voucher.stock;
    document.getElementById('edit_description').value = voucher.description;
    document.getElementById('edit_expired_at').value = voucher.expired_at;
    document.getElementById('edit_place_id').value = voucher.place_id;
<<<<<<< HEAD
=======
    // Tampilkan modal
>>>>>>> 182126222d0ec10dd9f8946fc95789d2be08206a
    var myModal = new bootstrap.Modal(document.getElementById('editVoucherModal'));
    myModal.show();
}
</script>
<?= $this->endSection(); ?>