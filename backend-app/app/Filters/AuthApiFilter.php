<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthApiFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Try Authorization header first: "Bearer <token>"
        $token = null;

        $authHeader = $request->getHeaderLine('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
        }

        // Fallback: X-Auth-Token header
        if (empty($token)) {
            $token = $request->getHeaderLine('X-Auth-Token');
        }

        if (empty($token)) {
            return $this->unauthorized();
        }

        // Decode token: base64(id|username)
        $decoded = base64_decode($token, true);
        if ($decoded === false || !str_contains($decoded, '|')) {
            return $this->unauthorized();
        }

        [$id, $username] = explode('|', $decoded, 2);

        if (empty($id) || empty($username)) {
            return $this->unauthorized();
        }

        $userModel = new UserModel();
        $user = $userModel
            ->where('id', (int) $id)
            ->where('username', $username)
            ->first();

        if (!$user) {
            return $this->unauthorized();
        }

        // Token valid — continue
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do after the request
    }

    private function unauthorized()
    {
        $response = service('response');
        $response->setStatusCode(401);
        $response->setContentType('application/json');
        $response->setBody(json_encode([
            'status'  => false,
            'message' => 'Unauthorized',
        ]));

        return $response;
    }
}
