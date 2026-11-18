<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="app-content-header px-4 pt-3 pb-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-speedometer2 text-primary me-2"></i> Dashboard Admin
                </h3>
                <p class="text-muted mb-0 small">Ringkasan inventaris barang dan mitra supplier terdaftar</p>
            </div>
            <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                <a href="<?= base_url('barang') ?>" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-box-seam me-1"></i> Kelola Barang
                </a>
                <a href="<?= base_url('supplier') ?>" class="btn btn-teal btn-sm text-white" style="background-color: #0d9488;">
                    <i class="bi bi-truck me-1"></i> Kelola Supplier
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content px-4 py-2">
    <div class="container-fluid">

        <!-- KARTU STATISTIK RINGKASAN -->
        <div class="row g-3 mb-4">

            <!-- TOTAL BARANG -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50 small text-uppercase fw-bold">Jenis Barang</span>
                                <h2 class="my-1 fw-bold"><?= esc($totalBarang) ?></h2>
                                <span class="small text-white-50">Produk Terdaftar</span>
                            </div>
                            <div class="fs-1 text-white-50">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-black bg-opacity-10 border-0 p-2 text-center">
                        <a href="<?= base_url('barang') ?>" class="text-white text-decoration-none small fw-semibold">
                            Buka Data Barang <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- TOTAL STOK AKUMULASI -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 bg-success text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50 small text-uppercase fw-bold">Akumulasi Stok</span>
                                <h2 class="my-1 fw-bold"><?= number_format($totalStok, 0, ',', '.') ?></h2>
                                <span class="small text-white-50">Total Fisik Unit</span>
                            </div>
                            <div class="fs-1 text-white-50">
                                <i class="bi bi-layers"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-black bg-opacity-10 border-0 p-2 text-center">
                        <a href="<?= base_url('barang') ?>" class="text-white text-decoration-none small fw-semibold">
                            Cek Rincian Stok <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- TOTAL SUPPLIER -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 text-white" style="background-color: #0d9488;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50 small text-uppercase fw-bold">Mitra Supplier</span>
                                <h2 class="my-1 fw-bold"><?= esc($totalSupplier) ?></h2>
                                <span class="small text-white-50">Pemasok Aktif</span>
                            </div>
                            <div class="fs-1 text-white-50">
                                <i class="bi bi-truck"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-black bg-opacity-10 border-0 p-2 text-center">
                        <a href="<?= base_url('supplier') ?>" class="text-white text-decoration-none small fw-semibold">
                            Buka Data Supplier <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- TOTAL AKUN USER -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 bg-dark text-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50 small text-uppercase fw-bold">Akun Terdaftar</span>
                                <h2 class="my-1 fw-bold"><?= esc($totalUser) ?></h2>
                                <span class="small text-white-50">User Database</span>
                            </div>
                            <div class="fs-1 text-white-50">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-black bg-opacity-25 border-0 p-2 text-center">
                        <span class="text-white-50 small">Autentikasi API Login</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- TABEL RINGKASAN BARANG & SUPPLIER TERBARU -->
        <div class="row g-4">

            <!-- BARANG TERBARU -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-box-seam text-primary me-2"></i> Barang Terbaru
                        </h5>
                        <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Stok</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($barangTerbaru)) : ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                                Belum ada data barang di database.
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($barangTerbaru as $b) : ?>
                                            <tr>
                                                <td><span class="badge text-bg-light border text-primary fw-bold"><?= esc($b['kode_barang']) ?></span></td>
                                                <td class="fw-semibold text-dark"><?= esc($b['nama_barang']) ?></td>
                                                <td><span class="badge bg-secondary-subtle text-secondary"><?= esc($b['kategori'] ?? '-') ?></span></td>
                                                <td>
                                                    <?php if ($b['stok'] <= 0) : ?>
                                                        <span class="badge badge-stok-habis px-2 py-1">Habis (0)</span>
                                                    <?php elseif ($b['stok'] <= 5) : ?>
                                                        <span class="badge badge-stok-tipis px-2 py-1">Sisa <?= esc($b['stok']) ?></span>
                                                    <?php else : ?>
                                                        <span class="badge badge-stok-aman px-2 py-1"><?= esc($b['stok']) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="fw-bold text-primary">Rp <?= number_format($b['harga'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUPPLIER TERBARU -->
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-truck text-success me-2" style="color: #0d9488 !important;"></i> Mitra Supplier Terbaru
                        </h5>
                        <a href="<?= base_url('supplier') ?>" class="btn btn-sm btn-outline-success" style="border-color: #0d9488; color: #0d9488;">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Supplier</th>
                                        <th>Kontak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($supplierTerbaru)) : ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                                Belum ada data supplier di database.
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($supplierTerbaru as $s) : ?>
                                            <tr>
                                                <td><span class="badge bg-teal-subtle text-dark border fw-bold"><?= esc($s['kode_supplier']) ?></span></td>
                                                <td>
                                                    <div class="fw-semibold text-dark"><?= esc($s['nama_supplier']) ?></div>
                                                    <small class="text-muted d-block text-truncate" style="max-width: 180px;">
                                                        <?= esc($s['alamat'] ?: '-') ?>
                                                    </small>
                                                </td>
                                                <td class="small">
                                                    <div><i class="bi bi-telephone text-muted me-1"></i> <?= esc($s['no_telp'] ?: '-') ?></div>
                                                    <div><i class="bi bi-envelope text-muted me-1"></i> <?= esc($s['email'] ?: '-') ?></div>
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

    </div>
</div>

<?= $this->endSection() ?>
