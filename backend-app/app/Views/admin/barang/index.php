<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="app-content-header px-4 pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-box-seam text-primary me-2"></i> Data Barang Inventaris
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Barang</li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Barang Baru
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
                    <span class="text-muted">Total Produk:</span> <strong class="text-primary"><?= count($barang) ?> Jenis</strong>
                </div>
                <div class="badge text-bg-light border p-2 px-3 fs-7">
                    <span class="text-muted">Total Stok:</span> <strong class="text-success"><?= number_format($totalStok, 0, ',', '.') ?> Unit</strong>
                </div>
            </div>
            <div class="col-md-6">
                <form action="<?= base_url('barang') ?>" method="get" class="d-flex justify-content-md-end gap-2">
                    <div class="input-group input-group-sm" style="max-width: 320px;">
                        <input type="text" name="cari" class="form-control" placeholder="Cari nama / kode / kategori..." value="<?= esc($cari ?? '') ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        <?php if (!empty($cari)) : ?>
                            <a href="<?= base_url('barang') ?>" class="btn btn-outline-secondary" title="Reset Pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABEL DATA BARANG -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th style="width: 140px;">Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th style="width: 110px;">Stok</th>
                                <th style="width: 150px;">Harga Satuan</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($barang)) : ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block text-secondary mb-2"></i>
                                        <?php if (!empty($cari)) : ?>
                                            Tidak ditemukan barang dengan kata kunci "<strong><?= esc($cari) ?></strong>".
                                            <div class="mt-2">
                                                <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-outline-primary">Tampilkan Semua</a>
                                            </div>
                                        <?php else : ?>
                                            Belum ada data barang di database. Silakan klik tombol <strong>Tambah Barang Baru</strong>.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; foreach ($barang as $b) : ?>
                                    <tr>
                                        <td class="text-center text-muted small"><?= $no++ ?></td>
                                        <td>
                                            <span class="badge text-bg-light border text-primary font-monospace fw-bold">
                                                <?= esc($b['kode_barang']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark"><?= esc($b['nama_barang']) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary border">
                                                <?= esc($b['kategori'] ?: 'Umum') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($b['stok'] <= 0) : ?>
                                                <span class="badge badge-stok-habis px-2 py-1">Habis (0)</span>
                                            <?php elseif ($b['stok'] <= 5) : ?>
                                                <span class="badge badge-stok-tipis px-2 py-1">Sisa <?= esc($b['stok']) ?></span>
                                            <?php else : ?>
                                                <span class="badge badge-stok-aman px-2 py-1">Stok: <?= esc($b['stok']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-primary">
                                            Rp <?= number_format($b['harga'], 0, ',', '.') ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button"
                                                        class="btn btn-outline-warning table-action-btn btn-edit-barang"
                                                        data-id="<?= esc($b['id']) ?>"
                                                        data-kode="<?= esc($b['kode_barang']) ?>"
                                                        data-nama="<?= esc($b['nama_barang']) ?>"
                                                        data-kategori="<?= esc($b['kategori'] ?? '') ?>"
                                                        data-stok="<?= esc($b['stok']) ?>"
                                                        data-harga="<?= esc($b['harga']) ?>"
                                                        title="Edit Barang">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <a href="<?= base_url('barang/hapus/' . $b['id']) ?>"
                                                   class="btn btn-outline-danger table-action-btn"
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus barang <?= esc($b['nama_barang']) ?> (<?= esc($b['kode_barang']) ?>)?')"
                                                   title="Hapus Barang">
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

<!-- MODAL TAMBAH BARANG -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-labelledby="modalTambahBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= base_url('barang/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalTambahBarangLabel">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Data Barang
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" name="kode_barang" class="form-control" placeholder="Contoh: BRG001" required maxlength="30">
                        <div class="form-text">Kode unik pengenal barang (harus belum pernah dipakai).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Mouse Wireless Logitech" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Contoh: Elektronik, ATK, dsb." maxlength="100">
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Jumlah Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" placeholder="0" min="0" required value="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control" placeholder="0" min="0" step="100" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT BARANG -->
<div class="modal fade" id="modalEditBarang" tabindex="-1" aria-labelledby="modalEditBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formEditBarang" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="modalEditBarangLabel">
                        <i class="bi bi-pencil-square me-1"></i> Edit Data Barang
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" id="edit_kode_barang" name="kode_barang" class="form-control" required maxlength="30">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" id="edit_nama_barang" name="nama_barang" class="form-control" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kategori</label>
                        <input type="text" id="edit_kategori" name="kategori" class="form-control" maxlength="100">
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Jumlah Stok <span class="text-danger">*</span></label>
                            <input type="number" id="edit_stok" name="stok" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" id="edit_harga" name="harga" class="form-control" min="0" step="100" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm px-3 fw-semibold">
                        <i class="bi bi-check-lg me-1"></i> Perbarui Barang
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
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEditBarang'));
        const formEdit = document.getElementById('formEditBarang');

        document.querySelectorAll('.btn-edit-barang').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const kode = this.getAttribute('data-kode');
                const nama = this.getAttribute('data-nama');
                const kategori = this.getAttribute('data-kategori');
                const stok = this.getAttribute('data-stok');
                const harga = this.getAttribute('data-harga');

                formEdit.action = '<?= base_url('barang/update') ?>/' + id;
                document.getElementById('edit_kode_barang').value = kode;
                document.getElementById('edit_nama_barang').value = nama;
                document.getElementById('edit_kategori').value = kategori;
                document.getElementById('edit_stok').value = stok;
                document.getElementById('edit_harga').value = Math.round(harga);

                modalEdit.show();
            });
        });
    });
</script>
<?= $this->endSection() ?>
