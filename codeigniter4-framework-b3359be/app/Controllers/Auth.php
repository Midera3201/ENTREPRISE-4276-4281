<?php

namespace App\Controllers;

use App\Models\EmployeModel;

class Auth extends BaseController
{
    public function login()
    {
        helper(['form']);

        return view('auth/login');
    }

    public function doLogin()
    {
        helper(['form']);

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/login')->withInput()->with('error', 'Identifiants invalides.');
        }

        $email = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $model = new EmployeModel();
        $user = $model->where('email', $email)->where('actif', 1)->first();

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            return redirect()->to('/login')->withInput()->with('error', 'Echec de connexion.');
        }

        session()->set([
            'user_id'    => $user['id'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
            'user_name'  => trim($user['prenom'] . ' ' . $user['nom']),
            'is_logged_in' => true,
        ]);

        // Redirection selon le rôle
        switch ($user['role']) {
            case 'admin':
                return redirect()->to('/admin');
            case 'rh':
                return redirect()->to('/rh/demandes');
            case 'employe':
            default:
                return redirect()->to('/employee/dashboard');
        }
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Deconnexion reussie.');
    }
}
