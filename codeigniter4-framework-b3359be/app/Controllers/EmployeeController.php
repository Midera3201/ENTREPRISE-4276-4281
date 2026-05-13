<?php

namespace App\Controllers;

class EmployeeController extends BaseController
{
    public function dashboard()
    {
        $data = [
            'soldeRestant' => 12, // Jours restants
            'demandesEnAttente' => 3,
            'demandesApprouvees' => 5,
            'demandesRefusees' => 1,
            'soldes' => [
                'annuel' => 75, // Pourcentage
                'maladie' => 50,
                'special' => 15,
            ],
        ];

        return view('employee/dashboard', $data);
    }

    public function index()
    {
        $demandes = [
            [
                'id' => 1,
                'type' => 'annuel',
                'date_debut' => '2026-05-01',
                'date_fin' => '2026-05-05',
                'duree' => 5,
                'statut' => 'en_attente',
                'commentaire' => '',
            ],
            [
                'id' => 2,
                'type' => 'maladie',
                'date_debut' => '2026-04-15',
                'date_fin' => '2026-04-16',
                'duree' => 2,
                'statut' => 'approuvee',
                'commentaire' => 'Validé par RH.',
            ],
            [
                'id' => 3,
                'type' => 'special',
                'date_debut' => '2026-03-20',
                'date_fin' => '2026-03-20',
                'duree' => 1,
                'statut' => 'refusee',
                'commentaire' => 'Non justifié.',
            ],
        ];

        return view('employee/index', ['demandes' => $demandes]);
    }

    public function annuler($id)
    {
        // Logique pour annuler la demande (exemple statique)
        // En production, il faudrait mettre à jour la base de données

        session()->setFlashdata('success', 'La demande a été annulée avec succès.');
        return redirect()->to('/employee/demandes');
    }

    public function profil()
    {
        $user = [
            'nom' => 'Rakoto',
            'prenom' => 'Jean',
            'email' => 'jean.rakoto@example.com',
        ];

        return view('employee/profil', ['user' => $user]);
    }

    public function updateProfil()
    {
        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');
        $email = $this->request->getPost('email');

        // Logique de mise à jour des informations personnelles
        // Exemple statique : succès
        session()->setFlashdata('success', 'Vos informations personnelles ont été mises à jour avec succès.');

        return redirect()->to('/employee/profil');
    }

    public function updatePassword()
    {
        $ancienMdp = $this->request->getPost('ancien_mdp');
        $nouveauMdp = $this->request->getPost('nouveau_mdp');
        $confirmationMdp = $this->request->getPost('confirmation_mdp');

        if ($nouveauMdp !== $confirmationMdp) {
            session()->setFlashdata('error', 'Les nouveaux mots de passe ne correspondent pas.');
            return redirect()->to('/employee/profil');
        }

        // Logique de vérification de l'ancien mot de passe et mise à jour
        // Exemple statique : succès
        session()->setFlashdata('success', 'Votre mot de passe a été mis à jour avec succès.');

        return redirect()->to('/employee/profil');
    }
}