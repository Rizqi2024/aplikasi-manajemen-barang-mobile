<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="app-content-header px-4 pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-truck text-success me-2" style="color: #0d9488 !important;"></i> Data Mitra Supplier
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Supplier</li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                <button type="button" class="btn btn-teal btn-sm text-white px-3 shadow-sm" style="background-color: #0d9488;" data-bs-toggle="modal" data-bs-target="#modalTambahSupplier">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Supplier Baru
                </button>
            </div>
        </div>
    </div>
</div>

<div class="app-content px-4 py-2">
    <div class="container-fluid">

        <!-- KARTU METRIK & PENCARIAN -->
        <div class="row g-3 mb-3 align-items-center">
            <div class="col-md-6 d-flex gap-2">
                <div class="badge text-bg-light border p-2 px-3 fs-7">
                    <span class="text-muted">Total Mitra:</span> <strong style="color: #0d9488;"><?= count($suppliers) ?> Pemasok</strong>
                </div>
            </div>
            <div class="col-md-6">
                <form action="<?= base_url('supplier') ?>" method="get" class="d-flex justify-content-md-end gap-2">
                    <div class="input-group input-group-sm" style="max-width: 340px;">
                        <input type="text" name="cari" class="form-control" placeholder="Cari nama / kode / alamat / kontak..." value="<?= esc($cari ?? '') ?>">
                        <button class="btn btn-outline-success" style="border-color: #0d9488; color: #0d9488;" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        <?php if (!empty($cari)) : ?>
                            <a href="<?= base_url('supplier') ?>" class="btn btn-outline-secondary" title="Reset Pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABEL DATA SUPPLIER -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th style="width: 140px;">Kode</th>
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th style="width: 160px;">No. Telepon</th>
                                <th style="width: 200px;">Email</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($suppliers)) : ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block text-secondary mb-2"></i>
                                        <?php if (!empty($cari)) : ?>
                                            Tidak ditemukan supplier dengan kata kunci "<strong><?= esc($cari) ?></strong>".
                                            <div class="mt-2">
                                                <a href="<?= base_url('supplier') ?>" class="btn btn-sm btn-outline-success" style="color: #0d9488; border-color: #0d9488;">Tampilkan Semua</a>
                                            </div>
                                        <?php else : ?>
                                            Belum ada data supplier di database. Silakan klik tombol <strong>Tambah Supplier Baru</strong>.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; foreach ($suppliers as $s) : ?>
                                    <tr>
                                        <td class="text-center text-muted small"><?= $no++ ?></td>
                                        <td>
                                            <span class="badge border font-monospace fw-bold" style="background-color: #ccfbf1; color: #0f766e;">
                                                <?= esc($s['kode_supplier']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark"><?= esc($s['nama_supplier']) ?></span>
                                        </td>
                                        <td>
                                            <small class="text-secondary"><?= esc($s['alamat'] ?: '-') ?></small>
                                        </td>
                                        <td>
                                            <?php if (!empty($s['no_telp'])) : ?>
                                                <a href="tel:<?= esc($s['no_telp']) ?>" class="text-decoration-none text-dark small">
                                                    <i class="bi bi-telephone text-success me-1"></i> <?= esc($s['no_telp']) ?>
                                                </a>
                                            <?php else : ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($s['email'])) : ?>
                                                <a href="mailto:<?= esc($s['email']) ?>" class="text-decoration-none text-primary small">
                                                    <i class="bi bi-envelope text-primary me-1"></i> <?= esc($s['email']) ?>
                                                </a>
                                            <?php else : ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button"
                                                        class="btn btn-outline-warning table-action-btn btn-edit-supplier"
                                                        data-id="<?= esc($s['id']) ?>"
                                                        data-kode="<?= esc($s['kode_supplier']) ?>"
                                                        data-nama="<?= esc($s['nama_supplier']) ?>"
                                                        data-alamat="<?= esc($s['alamat'] ?? '') ?>"
                                                        data-telp="<?= esc($s['no_telp'] ?? '') ?>"
                                                        data-email="<?= esc($s['email'] ?? '') ?>"
                                                        title="Edit Supplier">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <a href="<?= base_url('supplier/hapus/' . $s['id']) ?>"
                                                   class="btn btn-outline-danger table-action-btn"
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus supplier <?= esc($s['nama_supplier']) ?> (<?= esc($s['kode_supplier']) ?>)?')"
                                                   title="Hapus Supplier">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL TAMBAH SUPPLIER -->
<div class="modal fade" id="modalTambahSupplier" tabindex="-1" aria-labelledby="modalTambahSupplierLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= base_url('supplier/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header text-white" style="background-color: #0d9488;">
                    <h5 class="modal-title fw-bold" id="modalTambahSupplierLabel">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Mitra Supplier
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kode Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="kode_supplier" class="form-control" placeholder="Contoh: SUP001" required maxlength="20">
                        <div class="form-text">Kode unik pemasok (harus unik/belum terdaftar).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="nama_supplier" class="form-control" placeholder="Contoh: PT Sumber Rejeki Abadi" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Alamat Kantor / Gudang</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap supplier..."></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">No. Telepon / WhatsApp</label>
                            <input type="text" name="no_telp" class="form-control" placeholder="Contoh: 021-5551234 / 0812..." maxlength="20">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="sales@supplier.com" maxlength="100">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm px-3 text-white fw-semibold" style="background-color: #0d9488;">
                        <i class="bi bi-save me-1"></i> Simpan Data Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT SUPPLIER -->
<div class="modal fade" id="modalEditSupplier" tabindex="-1" aria-labelledby="modalEditSupplierLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formEditSupplier" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="modalEditSupplierLabel">
                        <i class="bi bi-pencil-square me-1"></i> Edit Data Supplier
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kode Supplier <span class="text-danger">*</span></label>
                        <input type="text" id="edit_kode_supplier" name="kode_supplier" class="form-control" required maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" id="edit_nama_supplier" name="nama_supplier" class="form-control" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Alamat Kantor / Gudang</label>
                        <textarea id="edit_alamat" name="alamat" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">No. Telepon / WhatsApp</label>
                            <input type="text" id="edit_no_telp" name="no_telp" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Alamat Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control" maxlength="100">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm px-3 fw-semibold">
                        <i class="bi bi-check-lg me-1"></i> Perbarui Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEditSupplier'));
        const formEdit = document.getElementById('formEditSupplier');

        document.querySelectorAll('.btn-edit-supplier').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const kode = this.getAttribute('data-kode');
                const nama = this.getAttribute('data-nama');
                const alamat = this.getAttribute('data-alamat');
                const telp = this.getAttribute('data-telp');
                const email = this.getAttribute('data-email');

                formEdit.action = '<?= base_url('supplier/update') ?>/' + id;
                document.getElementById('edit_kode_supplier').value = kode;
                document.getElementById('edit_nama_supplier').value = nama;
                document.getElementById('edit_alamat').value = alamat;
                document.getElementById('edit_no_telp').value = telp;
                document.getElementById('edit_email').value = email;

                modalEdit.show();
            });
        });
    });
</script>
<?= $this->endSection() ?>
