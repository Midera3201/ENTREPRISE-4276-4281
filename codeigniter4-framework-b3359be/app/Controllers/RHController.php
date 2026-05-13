<?php

namespace App\Controllers;

class RHController extends BaseController
{
    public function demandes()
    {
        $demandes = [
            [
                'id' => 1,
                'employe' => [
                    'avatar' => '/assets/images/avatar1.png',
                    'nom' => 'Rakoto Jean',
                    'departement' => 'Informatique',
                ],
                'type' => 'annuel',
                'date_debut' => '2026-05-10',
                'date_fin' => '2026-05-15',
                'duree' => 6,
                'solde' => 12,
            ],
            [
                'id' => 2,
                'employe' => [
                    'avatar' => '/assets/images/avatar2.png',
                    'nom' => 'Rasoa Marie',
                    'departement' => 'RH',
                ],
                'type' => 'maladie',
                'date_debut' => '2026-05-12',
                'date_fin' => '2026-05-14',
                'duree' => 3,
                'solde' => 5,
            ],
        ];

        return view('rh/index', ['demandes' => $demandes]);
    }

    public function approuver($id)
    {
        // Logique pour approuver une demande
        session()->setFlashdata('success', 'La demande a été approuvée avec succès.');
        return redirect()->to('/rh/demandes');
    }

    public function refuser($id)
    {
        $commentaire = $this->request->getPost('commentaire');
        // Logique pour refuser une demande avec un commentaire
        session()->setFlashdata('error', 'La demande a été refusée avec succès.');
        return redirect()->to('/rh/demandes');
    }

    public function soldes()
    {
        $employes = [
            [
                'nom' => 'Rakoto',
                'prenom' => 'Jean',
                'departement' => 'Informatique',
                'soldes' => [
                    'annuel' => ['restant' => 15, 'attribue' => 30],
                    'maladie' => ['restant' => 5, 'attribue' => 10],
                    'special' => ['restant' => 2, 'attribue' => 5],
                ],
            ],
            [
                'nom' => 'Rasoa',
                'prenom' => 'Marie',
                'departement' => 'RH',
                'soldes' => [
                    'annuel' => ['restant' => 25, 'attribue' => 30],
                    'maladie' => ['restant' => 8, 'attribue' => 10],
                    'special' => ['restant' => 4, 'attribue' => 5],
                ],
            ],
        ];

        return view('rh/soldes', ['employes' => $employes]);
    }
}