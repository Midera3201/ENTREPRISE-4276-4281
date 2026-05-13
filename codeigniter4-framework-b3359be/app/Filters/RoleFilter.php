<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Merci de vous connecter.');
        }

        if ($arguments === null || $arguments === []) {
            return;
        }

        $role = (string) session()->get('user_role');
        if (! in_array($role, $arguments, true)) {
            return redirect()->to('/dashboard')->with('error', 'Acces refuse.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
