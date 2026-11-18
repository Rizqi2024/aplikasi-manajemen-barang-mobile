<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\SupplierModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $barangModel   = new BarangModel();
        $supplierModel = new SupplierModel();
        $userModel     = new UserModel();

        $semuaBarang = $barangModel->findAll();
        $totalBarang = count($semuaBarang);
        $totalStok   = array_sum(array_column($semuaBarang, 'stok'));

        $semuaSupplier = $supplierModel->findAll();
        $totalSupplier = count($semuaSupplier);

        $totalUser = $userModel->countAllResults();

        // Ambil 5 barang dan 5 supplier terbaru
        $barangTerbaru   = $barangModel->orderBy('id', 'DESC')->findAll(5);
        $supplierTerbaru = $supplierModel->orderBy('id', 'DESC')->findAll(5);

        $data = [
            'title'           => 'Dashboard Admin',
            'activeMenu'      => 'dashboard',
            'totalBarang'     => $totalBarang,
            'totalStok'       => $totalStok,
            'totalSupplier'   => $totalSupplier,
            'totalUser'       => $totalUser,
            'barangTerbaru'   => $barangTerbaru,
            'supplierTerbaru' => $supplierTerbaru,
        ];

        return view('admin/dashboard', $data);
    }
}
