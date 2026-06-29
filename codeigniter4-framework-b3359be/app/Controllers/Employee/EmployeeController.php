<?php

namespace App\Controllers\Employee;

use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use App\Services\CongeService;

class EmployeeController extends EmployeeBaseController
{
    private CongeService $congeService;

    public function __construct()
    {
        $this->congeService = new CongeService();
    }

    public function dashboard()
    {
        $userId = session()->get('user_id');

        $congeModel = new CongeModel();
        $mesConges = $congeModel
            ->select('conges.*, types_conge.libelle as type_libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $userId)
            ->orderBy('conges.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $stats = [
            'total'      => $congeModel->where('employe_id', $userId)->countAllResults(),
            'en_attente' => $congeModel->where('employe_id', $userId)->where('statut', 'en_attente')->countAllResults(),
            'approuves'  => $congeModel->where('employe_id', $userId)->where('statut', 'approuve')->countAllResults(),
            'refuses'    => $congeModel->where('employe_id', $userId)->where('statut', 'refuse')->countAllResults(),
            'annules'    => $congeModel->where('employe_id', $userId)->where('statut', 'annule')->countAllResults(),
        ];

        return $this->render('employee/dashboard', [
            'mesConges' => $mesConges,
            'stats'     => $stats,
            'title'     => 'Mon tableau de bord',
        ]);
    }

    public function createDemande()
    {
        $typeCongeModel = new TypeCongeModel();
        $typesConge = $typeCongeModel->where('deductible', 1)->orderBy('libelle', 'ASC')->findAll();

        return $this->render('employee/demande_form', [
            'typesConge' => $typesConge,
            'title'      => 'Nouvelle demande de congé',
            'errors'     => session()->getFlashdata('errors') ?? [],
            'oldInput'   => session()->getFlashdata('oldInput') ?? [],
        ]);
    }

    public function storeDemande()
    {
        $userId = session()->get('user_id');
        $congeModel = new CongeModel();
        $typeCongeModel = new TypeCongeModel();

        $rules = [
            'type_conge_id' => 'required|integer',
            'date_debut'    => 'required|valid_date',
            'date_fin'      => 'required|valid_date|greater_than[date_debut]',
            'motif'         => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to('/employee/demande/create');
        }

        $typeCongeId = (int) $this->request->getPost('type_conge_id');
        $dateDebut = $this->request->getPost('date_debut');
        $dateFin = $this->request->getPost('date_fin');
        $motif = $this->request->getPost('motif') ?: null;

        // Récupérer le type de congé pour vérification
        $typeConge = $typeCongeModel->find($typeCongeId);
        if (! $typeConge) {
            return redirect()->to('/employee/demande/create')
                ->with('error', 'Type de congé invalide.');
        }

        // Calculer le nombre de jours ouvrables
        $nbJours = $this->congeService->calculerNbJours($dateDebut, $dateFin);

        if ($nbJours <= 0) {
            return redirect()->to('/employee/demande/create')
                ->with('error', 'La période de congé est invalide.');
        }

        // Vérifier les chevauchements
        $chevauche = $this->congeService->verifierChevauchement($userId, $dateDebut, $dateFin);
        if ($chevauche) {
            return redirect()->to('/employee/demande/create')
                ->with('error', "Vous avez déjà une demande de congé chevauchant cette période (du {$chevauche['date_debut']} au {$chevauche['date_fin']}).");
        }

        // Si le congé est déductible, vérifier le solde
        if ($typeConge['deductible']) {
            $annee = (int) date('Y', strtotime($dateDebut));
            $result = $this->congeService->verifierSoldeAvantApprobation($userId, $typeCongeId, $annee, $nbJours);

            if ($result['insuffisant']) {
                return redirect()->to('/employee/demande/create')
                    ->with('error', $this->congeService->verifierSoldeInsuffisantMessage($userId, $typeCongeId, $annee, $nbJours));
            }
        }

        // Déterminer le statut initial
        $statut = $typeConge['deductible'] ? 'en_attente' : 'en_attente';

        $db = \Config\Database::connect();
        $db->transStart();

        $congeId = $congeModel->insert([
            'employe_id'     => $userId,
            'type_conge_id'  => $typeCongeId,
            'date_debut'     => $dateDebut,
            'date_fin'       => $dateFin,
            'nb_jours'       => $nbJours,
            'statut'         => 'en_attente',
            'motif'          => $motif,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/employee/demande/create')
                ->with('error', 'Erreur lors de la soumission. Veuillez réessayer.');
        }

        return redirect()->to('/employee/mes-demandes')
            ->with('success', 'Demande de congé soumise avec succès. En attente de validation.');
    }

    public function mesDemandes()
    {
        $userId = session()->get('user_id');
        $congeModel = new CongeModel();

        $filtre = $this->request->getGet('filtre') ?? 'tous';

        $query = $congeModel
            ->select('conges.*, types_conge.libelle as type_libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $userId);

        if ($filtre !== 'tous') {
            $query->where('conges.statut', $filtre);
        }

        $mesConges = $query->orderBy('conges.created_at', 'DESC')->findAll();

        return $this->render('employee/mes_demandes', [
            'mesConges' => $mesConges,
            'filtre'    => $filtre,
            'title'     => 'Mes demandes de congé',
        ]);
    }

    public function annulerDemande(int $id)
    {
        $userId = session()->get('user_id');
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();
        $conge = $congeModel->find($id);

        if (! $conge) {
            return redirect()->to('/employee/mes-demandes')
                ->with('error', 'Demande introuvable.');
        }

        if ($conge['employe_id'] !== $userId) {
            return redirect()->to('/employee/mes-demandes')
                ->with('error', 'Vous ne pouvez pas annuler cette demande.');
        }

        if (! in_array($conge['statut'], ['en_attente', 'approuve'])) {
            return redirect()->to('/employee/mes-demandes')
                ->with('error', 'Seules les demandes en attente ou approuvées peuvent être annulées.');
        }

        // Si approuvé, recréditer le solde
        if ($conge['statut'] === 'approuve') {
            $annee = (int) date('Y', strtotime($conge['date_debut']));
            $result = $this->congeService->recrediterSoldeApresAnnulation(
                $userId,
                $conge['type_conge_id'],
                $annee,
                $conge['nb_jours']
            );

            if (! $result) {
                return redirect()->to('/employee/mes-demandes')
                    ->with('error', 'Erreur lors du recrédit du solde.');
            }
        }

        $congeModel->update($id, ['statut' => 'annule']);

        return redirect()->to('/employee/mes-demandes')
            ->with('success', 'Demande annulée avec succès.' . ($conge['statut'] === 'approuve' ? ' Le solde a été recrédité.' : ''));
    }

    public function profil()
    {
        $userId = session()->get('user_id');
        $employeModel = new EmployeModel();
        $employe = $employeModel->find($userId);

        if (! $employe) {
            return redirect()->to('/employee/dashboard')
                ->with('error', 'Profil introuvable.');
        }

        $congeModel = new CongeModel();
        $stats = [
            'total'      => $congeModel->where('employe_id', $userId)->countAllResults(),
            'approuves'  => $congeModel->where('employe_id', $userId)->where('statut', 'approuve')->countAllResults(),
        ];

        return $this->render('employee/profil', [
            'employe' => $employe,
            'stats'   => $stats,
            'title'   => 'Mon profil',
        ]);
    }
}

// Alias pour compatibilité
class_alias('App\Controllers\Employee\EmployeeController', 'EmployeeController');