<?php

namespace App\Controllers\RH;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class RHBaseController extends Controller
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['form', 'url']);

        if (! session()->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Merci de vous connecter.')->send();
            exit;
        }

        $role = session()->get('user_role');
        if ($role !== 'rh' && $role !== 'admin') {
            return redirect()->to('/login')->with('error', 'Accès réservé aux RH et administrateurs.')->send();
            exit;
        }
    }

    protected function render(string $view, array $data = []): string
    {
        $data['title'] = $data['title'] ?? 'Espace RH';

        return view('layouts/rh', $data);
    }
}