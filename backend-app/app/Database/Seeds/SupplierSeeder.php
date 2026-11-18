<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_supplier' => 'SUP001',
                'nama_supplier' => 'PT Maju Jaya Elektronik',
                'alamat'        => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'no_telp'       => '021-5551001',
                'email'         => 'info@majujaya.co.id',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'kode_supplier' => 'SUP002',
                'nama_supplier' => 'CV Berkah Sejahtera',
                'alamat'        => 'Jl. Gatot Subroto No. 12, Bandung',
                'no_telp'       => '022-7778800',
                'email'         => 'sales@berkahsejahtera.com',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'kode_supplier' => 'SUP003',
                'nama_supplier' => 'UD Sumber Rejeki',
                'alamat'        => 'Jl. Pahlawan No. 88, Surabaya',
                'no_telp'       => '031-3334455',
                'email'         => 'sumberrejeki@gmail.com',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($data as $row) {
            $exists = $this->db->table('supplier')
                ->where('kode_supplier', $row['kode_supplier'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('supplier')->insert($row);
            }
        }
    }
}
