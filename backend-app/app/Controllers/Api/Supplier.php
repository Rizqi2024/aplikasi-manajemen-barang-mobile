<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SupplierModel;

class Supplier extends BaseController
{
    public function index()
    {
        try {
            $supplierModel = new SupplierModel();
            $suppliers = $supplierModel->findAll();

            return $this->response->setJSON([
                'status' => true,
                'data'   => $suppliers,
            ]);
        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status'  => false,
                    'message' => 'Gagal mengambil data supplier: ' . $e->getMessage(),
                ]);
        }
    }
}
