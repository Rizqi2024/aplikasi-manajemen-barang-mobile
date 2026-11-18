<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use CodeIgniter\HTTP\ResponseInterface;

class Barang extends BaseController
{
    protected BarangModel $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        return $this->response->setJSON([
            'status' => true,
            'data' => $this->barangModel->findAll()
        ]);
    }

    public function show($id = null)
    {
        $barang = $this->barangModel->find($id);

        if (!$barang) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => false,
                    'message' => 'Barang tidak ditemukan'
                ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => $barang
        ]);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!is_array($data)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => false,
                    'message' => 'Data JSON tidak valid'
                ]);
        }

        $rules = [
            'kode_barang' => 'required|max_length[30]',
            'nama_barang' => 'required|max_length[150]',
            'stok' => 'required|is_natural',
            'harga' => 'required|decimal'
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => $this->validator->getErrors()
                ]);
        }

        $id = $this->barangModel->insert($data);

        if ($id === false) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => $this->barangModel->errors()
                ]);
        }

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                'status' => true,
                'message' => 'Barang berhasil ditambahkan',
                'id' => $id
            ]);
    }

    public function update($id = null)
    {
        if (!$this->barangModel->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => false,
                    'message' => 'Barang tidak ditemukan'
                ]);
        }

        $data = $this->request->getJSON(true);

        if (!is_array($data)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => false,
                    'message' => 'Data JSON tidak valid'
                ]);
        }

        $rules = [
            'kode_barang' => 'required|max_length[30]',
            'nama_barang' => 'required|max_length[150]',
            'stok' => 'required|is_natural',
            'harga' => 'required|decimal'
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => $this->validator->getErrors()
                ]);
        }

        if (!$this->barangModel->update($id, $data)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => $this->barangModel->errors()
                ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Barang berhasil diperbarui'
        ]);
    }

    public function delete($id = null)
    {
        if (!$this->barangModel->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => false,
                    'message' => 'Barang tidak ditemukan'
                ]);
        }

        $this->barangModel->delete($id);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}
