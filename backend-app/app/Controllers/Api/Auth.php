<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status'  => false,
                    'message' => 'Data login tidak valid'
                ]);
        }

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if ($username === '' || $password === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status'  => false,
                    'message' => 'Username dan password wajib diisi'
                ]);
        }

        $userModel = new UserModel();

        $user = $userModel
            ->where('username', $username)
            ->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => false,
                    'message' => 'Username atau password salah'
                ]);
        }

        $token = base64_encode($user['id'] . '|' . $user['username']);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => [
                'id'       => $user['id'],
                'nama'     => $user['nama'],
                'username' => $user['username'],
                'role'     => $user['role'],
                'token'    => $token,
            ]
        ]);
    }
}
