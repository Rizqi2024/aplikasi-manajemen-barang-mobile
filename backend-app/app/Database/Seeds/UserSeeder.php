<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'       => 'Administrator',
                'username'   => 'admin',
                'email'      => 'admin@gmail.com',
                'password'   => password_hash('Admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Pengguna',
                'username'   => 'pengguna',
                'email'      => 'pengguna@gmail.com',
                'password'   => password_hash('User123', PASSWORD_DEFAULT),
                'role'       => 'user',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($data as $row) {
            $exists = $this->db->table('users')
                ->where('username', $row['username'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('users')->insert($row);
            }
        }
    }
}
