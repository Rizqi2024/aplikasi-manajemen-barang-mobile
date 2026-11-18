<?php

namespace App\Controllers;

use App\Models\SupplierModel;

class Supplier extends BaseController
{
    protected SupplierModel $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('cari');

        if (!empty($keyword)) {
            $suppliers = $this->supplierModel
                ->like('nama_supplier', $keyword)
                ->orLike('kode_supplier', $keyword)
                ->orLike('alamat', $keyword)
                ->orLike('no_telp', $keyword)
                ->orLike('email', $keyword)
                ->orderBy('id', 'DESC')
                ->findAll();
        } else {
            $suppliers = $this->supplierModel->orderBy('id', 'DESC')->findAll();
        }

        $data = [
            'title'      => 'Kelola Data Supplier',
            'activeMenu' => 'supplier',
            'suppliers'  => $suppliers,
            'cari'       => $keyword,
        ];

        return view('admin/supplier/index', $data);
    }

    public function store()
    {
        $rules = [
            'kode_supplier' => 'required|max_length[20]|is_unique[supplier.kode_supplier]',
            'nama_supplier' => 'required|max_length[150]',
            'alamat'        => 'permit_empty',
            'no_telp'       => 'permit_empty|max_length[20]',
            'email'         => 'permit_empty|valid_email|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->to('/supplier')->with('gagal', $errors);
        }

        $this->supplierModel->insert([
            'kode_supplier' => strtoupper(trim($this->request->getPost('kode_supplier'))),
            'nama_supplier' => trim($this->request->getPost('nama_supplier')),
            'alamat'        => trim($this->request->getPost('alamat')) ?: null,
            'no_telp'       => trim($this->request->getPost('no_telp')) ?: null,
            'email'         => trim($this->request->getPost('email')) ?: null,
        ]);

        return redirect()->to('/supplier')->with('sukses', 'Data mitra supplier berhasil ditambahkan!');
    }

    public function update($id = null)
    {
        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('/supplier')->with('gagal', 'Data supplier tidak ditemukan.');
        }

        $kodeSupplierInput = strtoupper(trim($this->request->getPost('kode_supplier')));

        $rules = [
            'kode_supplier' => "required|max_length[20]|is_unique[supplier.kode_supplier,id,{$id}]",
            'nama_supplier' => 'required|max_length[150]',
            'alamat'        => 'permit_empty',
            'no_telp'       => 'permit_empty|max_length[20]',
            'email'         => 'permit_empty|valid_email|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->to('/supplier')->with('gagal', $errors);
        }

        $this->supplierModel->update($id, [
            'kode_supplier' => $kodeSupplierInput,
            'nama_supplier' => trim($this->request->getPost('nama_supplier')),
            'alamat'        => trim($this->request->getPost('alamat')) ?: null,
            'no_telp'       => trim($this->request->getPost('no_telp')) ?: null,
            'email'         => trim($this->request->getPost('email')) ?: null,
        ]);

        return redirect()->to('/supplier')->with('sukses', 'Data mitra supplier berhasil diperbarui!');
    }

    public function delete($id = null)
    {
        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('/supplier')->with('gagal', 'Data supplier tidak ditemukan.');
        }

        $this->supplierModel->delete($id);
        return redirect()->to('/supplier')->with('sukses', "Supplier '{$supplier['nama_supplier']}' berhasil dihapus.");
    }
}
