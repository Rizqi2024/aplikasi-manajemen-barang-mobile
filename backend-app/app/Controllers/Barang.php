<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Barang extends BaseController
{
    protected BarangModel $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('cari');

        if (!empty($keyword)) {
            $barang = $this->barangModel
                ->like('nama_barang', $keyword)
                ->orLike('kode_barang', $keyword)
                ->orLike('kategori', $keyword)
                ->orderBy('id', 'DESC')
                ->findAll();
        } else {
            $barang = $this->barangModel->orderBy('id', 'DESC')->findAll();
        }

        $totalStok = array_sum(array_column($barang, 'stok'));

        $data = [
            'title'      => 'Kelola Data Barang',
            'activeMenu' => 'barang',
            'barang'     => $barang,
            'totalStok'  => $totalStok,
            'cari'       => $keyword,
        ];

        return view('admin/barang/index', $data);
    }

    public function store()
    {
        $rules = [
            'kode_barang' => 'required|max_length[30]|is_unique[barang.kode_barang]',
            'nama_barang' => 'required|max_length[150]',
            'kategori'    => 'permit_empty|max_length[100]',
            'stok'        => 'required|is_natural',
            'harga'       => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->to('/barang')->with('gagal', $errors);
        }

        $this->barangModel->insert([
            'kode_barang' => strtoupper(trim($this->request->getPost('kode_barang'))),
            'nama_barang' => trim($this->request->getPost('nama_barang')),
            'kategori'    => trim($this->request->getPost('kategori')) ?: null,
            'stok'        => (int) $this->request->getPost('stok'),
            'harga'       => (float) $this->request->getPost('harga'),
        ]);

        return redirect()->to('/barang')->with('sukses', 'Data barang berhasil ditambahkan!');
    }

    public function update($id = null)
    {
        $barang = $this->barangModel->find($id);
        if (!$barang) {
            return redirect()->to('/barang')->with('gagal', 'Data barang tidak ditemukan.');
        }

        $kodeBarangInput = strtoupper(trim($this->request->getPost('kode_barang')));

        $rules = [
            'kode_barang' => "required|max_length[30]|is_unique[barang.kode_barang,id,{$id}]",
            'nama_barang' => 'required|max_length[150]',
            'kategori'    => 'permit_empty|max_length[100]',
            'stok'        => 'required|is_natural',
            'harga'       => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            $errors = implode('<br>', $this->validator->getErrors());
            return redirect()->to('/barang')->with('gagal', $errors);
        }

        $this->barangModel->update($id, [
            'kode_barang' => $kodeBarangInput,
            'nama_barang' => trim($this->request->getPost('nama_barang')),
            'kategori'    => trim($this->request->getPost('kategori')) ?: null,
            'stok'        => (int) $this->request->getPost('stok'),
            'harga'       => (float) $this->request->getPost('harga'),
        ]);

        return redirect()->to('/barang')->with('sukses', 'Data barang berhasil diperbarui!');
    }

    public function delete($id = null)
    {
        $barang = $this->barangModel->find($id);
        if (!$barang) {
            return redirect()->to('/barang')->with('gagal', 'Data barang tidak ditemukan.');
        }

        $this->barangModel->delete($id);
        return redirect()->to('/barang')->with('sukses', "Barang '{$barang['nama_barang']}' berhasil dihapus.");
    }
}
