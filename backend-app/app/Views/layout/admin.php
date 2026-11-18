<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Admin Panel') ?> - Sistem Manajemen Barang & Supplier</title>

    <!-- Bootstrap Icons (Lokal + Fallback CDN) -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/assets/bootstrap-icons/font/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/overlayscrollbars/styles/overlayscrollbars.min.css') ?>">

    <!-- AdminLTE 4 CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">

    <style>
        .table-action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.825rem;
        }
        .badge-stok-aman {
            background-color: #d1e7dd;
            color: #0f5132;
            font-weight: 600;
        }
        .badge-stok-tipis {
            background-color: #fff3cd;
            color: #664d03;
            font-weight: 600;
        }
        .badge-stok-habis {
            background-color: #f8d7da;
            color: #842029;
            font-weight: 600;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    <!-- Navbar -->
    <nav class="app-header navbar navbar-expand bg-body shadow-sm">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list fs-5"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <span class="nav-link fw-semibold text-primary">
                        <i class="bi bi-boxes me-1"></i> Sistem Manajemen Inventaris & Pemasok
                    </span>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-2">
                    <span class="badge text-bg-primary py-2 px-3">
                        <i class="bi bi-shield-check me-1"></i> Admin Panel
                    </span>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('api/barang') ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Cek JSON API Barang">
                        <i class="bi bi-code-slash me-1"></i> API Barang
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
        <div class="sidebar-brand py-3 border-bottom border-secondary">
            <a href="<?= base_url('dashboard') ?>" class="brand-link text-decoration-none d-flex align-items-center px-3">
                <span class="brand-text fw-bold fs-5 text-white">
                    <i class="bi bi-box-seam-fill text-primary me-2"></i> Inventaris Pro
                </span>
            </a>
        </div>

        <div class="sidebar-wrapper">
            <nav class="mt-3">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">

                    <li class="nav-header text-uppercase text-secondary fs-7 px-3 mb-1">Menu Utama</li>

                    <li class="nav-item">
                        <a href="<?= base_url('dashboard') ?>"
                           class="nav-link <?= (isset($activeMenu) && $activeMenu === 'dashboard') ? 'active bg-primary text-white' : '' ?>">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('barang') ?>"
                           class="nav-link <?= (isset($activeMenu) && $activeMenu === 'barang') ? 'active bg-primary text-white' : '' ?>">
                            <i class="nav-icon bi bi-box-seam"></i>
                            <p>Data Barang</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('supplier') ?>"
                           class="nav-link <?= (isset($activeMenu) && $activeMenu === 'supplier') ? 'active bg-primary text-white' : '' ?>">
                            <i class="nav-icon bi bi-truck"></i>
                            <p>Data Supplier</p>
                        </a>
                    </li>

                    <li class="nav-header text-uppercase text-secondary fs-7 px-3 mt-3 mb-1">Integrasi Android API</li>

                    <li class="nav-item">
                        <a href="<?= base_url('api/barang') ?>" target="_blank" class="nav-link">
                            <i class="nav-icon bi bi-phone"></i>
                            <p>API Barang (JSON)</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('api/supplier') ?>" target="_blank" class="nav-link">
                            <i class="nav-icon bi bi-shield-lock"></i>
                            <p>API Supplier (Protected)</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    <!-- Konten Utama -->
    <main class="app-main">

        <!-- Flash Message Notifikasi -->
        <div class="container-fluid pt-3 px-4">
            <?php if (session()->getFlashdata('sukses')) : ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
                    <strong>Berhasil!</strong> <?= session()->getFlashdata('sukses') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('gagal')) : ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                    <strong>Gagal!</strong> <?= session()->getFlashdata('gagal') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>

        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="app-footer text-muted border-top py-3 px-4 bg-white">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span><strong>Aplikasi Mobile &amp; Web Admin</strong> &copy; <?= date('Y') ?></span>
            <span class="d-none d-sm-inline">CodeIgniter 4 + AdminLTE</span>
        </div>
    </footer>

</div>

<!-- JavaScript Dependencies -->
<script src="<?= base_url('assets/adminlte/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/overlayscrollbars/browser/overlayscrollbars.browser.es6.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
