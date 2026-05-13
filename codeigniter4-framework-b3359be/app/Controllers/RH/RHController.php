<?php

namespace App\Controllers\RH;

use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use App\Services\CongeService;

class RHController extends RHBaseController
{
    private CongeService $congeService;

    public function __construct()
    {
        $this->congeService = new CongeService();
    }

    public function demandesEnAttente()
    {
        $congeModel = new CongeModel();

        $filtres = [
            'type'   => $this->request->getGet('type') ?? '',
            'statut' => $this->request->getGet('statut') ?? 'en_attente',
            'search' => $this->request->getGet('search') ?? '',
            'debut'  => $this->request->getGet('debut') ?? '',
            'fin'    => $this->request->getGet('fin') ?? '',
        ];

        $query = $congeModel
            ->select('conges.*, employes.nom, employes.prenom, employes.email, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id');

        $statutsValides = ['en_attente', 'approuve', 'refuse', 'annule', 'tous'];
        if (in_array($filtres['statut'], $statutsValides) && $filtres['statut'] !== 'tous') {
            $query->where('conges.statut', $filtres['statut']);
        }

        if (! empty($filtres['type'])) {
            $query->where('conges.type_conge_id', (int) $filtres['type']);
        }

        if (! empty($filtres['search'])) {
            $search = $filtres['search'];
            $query->groupStart()
                ->like('employes.nom', $search)
                ->orLike('employes.prenom', $search)
                ->orLike('employes.email', $search)
                ->groupEnd();
        }

        if (! empty($filtres['debut'])) {
            $query->where('conges.date_debut >=', $filtres['debut']);
        }

        if (! empty($filtres['fin'])) {
            $query->where('conges.date_fin <=', $filtres['fin']);
        }

        $demandes = $query->orderBy('conges.date_soumission', 'ASC')->findAll();

        $typesConge = (new TypeCongeModel())->orderBy('libelle', 'ASC')->findAll();

        return $this->render('rh/demandes_en_attente', [
            'demandes'  => $demandes,
            'typesConge'=> $typesConge,
            'filtres'   => $filtres,
            'title'     => 'Demandes en attente',
        ]);
    }

    public function approuver(int $id)
    {
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $conge = $congeModel->find($id);

        if (! $conge) {
            return redirect()->to('/rh/demandes')
                ->with('error', 'Demande introuvable.');
        }

        if ($conge['statut'] !== 'en_attente') {
            return redirect()->to('/rh/demandes')
                ->with('error', 'Cette demande ne peut pas etre approuvee (statut : ' . $conge['statut'] . ').');
        }

        $typeCongeModel = new TypeCongeModel();
        $typeConge = $typeCongeModel->find($conge['type_conge_id']);

        $commentaire = $this->request->getPost('commentaire_rh');

        $db = \Config\Database::connect();
        $db->transStart();

        $congeModel->update($id, [
            'statut'         => 'approuve',
            'commentaire_rh' => $commentaire ?: null,
        ]);

        if ($typeConge && $typeConge['deductible']) {
            $annee = (int) date('Y', strtotime($conge['date_debut']));

            $solde = $soldeModel
                ->where('employe_id', $conge['employe_id'])
                ->where('type_conge_id', $conge['type_conge_id'])
                ->where('annee', $annee)
                ->first();

            if ($solde) {
                $soldeRestant = $solde['jours_attribues'] - $solde['jours_pris'];

                if ($soldeRestant < $conge['nb_jours']) {
                    $db->transRollback();
                    return redirect()->to('/rh/demandes')
                        ->with('error', "Solde insuffisant pour approuver cette demande. Solde restant : {$soldeRestant} jours.");
                }

                $soldeModel->update($solde['id'], [
                    'jours_pris' => $solde['jours_pris'] + $conge['nb_jours'],
                ]);
            } else {
                $soldeModel->insert([
                    'employe_id'     => $conge['employe_id'],
                    'type_conge_id'  => $conge['type_conge_id'],
                    'annee'          => $annee,
                    'jours_attribues'=> 0,
                    'jours_pris'     => $conge['nb_jours'],
                ]);
            }
        }

        $db->transComplete();

        return redirect()->to('/rh/demandes')
            ->with('success', "Demande #{$id} approuvee avec succes.");
    }

    public function refuser(int $id)
    {
        $congeModel = new CongeModel();

        $conge = $congeModel->find($id);

        if (! $conge) {
            return redirect()->to('/rh/demandes')
                ->with('error', 'Demande introuvable.');
        }

        if ($conge['statut'] !== 'en_attente') {
            return redirect()->to('/rh/demandes')
                ->with('error', 'Cette demande ne peut pas etre refusee (statut : ' . $conge['statut'] . ').');
        }

        $commentaire = $this->request->getPost('commentaire_rh');

        $rules = [
            'commentaire_rh' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to("/rh/demandes")
                ->with('error', 'Le commentaire est trop long.');
        }

        $congeModel->update($id, [
            'statut'         => 'refuse',
            'commentaire_rh' => $commentaire ?: null,
        ]);

        return redirect()->to('/rh/demandes')
            ->with('success', "Demande #{$id} refusee.");
    }

    public function filtres()
    {
        $typeCongeModel = new TypeCongeModel();
        $typesConge = $typeCongeModel->orderBy('libelle', 'ASC')->findAll();

        $filtres = [
            'type'   => $this->request->getGet('type') ?? '',
            'statut' => $this->request->getGet('statut') ?? 'tous',
            'search' => $this->request->getGet('search') ?? '',
            'debut'  => $this->request->getGet('debut') ?? '',
            'fin'    => $this->request->getGet('fin') ?? '',
        ];

        $congeModel = new CongeModel();
        $query = $congeModel
            ->select('conges.*, employes.nom, employes.prenom, employes.email, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id');

        if (in_array($filtres['statut'], ['en_attente', 'approuve', 'refuse', 'annule'])) {
            $query->where('conges.statut', $filtres['statut']);
        }

        if (! empty($filtres['type'])) {
            $query->where('conges.type_conge_id', (int) $filtres['type']);
        }

        if (! empty($filtres['search'])) {
            $search = $filtres['search'];
            $query->groupStart()
                ->like('employes.nom', $search)
                ->orLike('employes.prenom', $search)
                ->orLike('employes.email', $search)
                ->groupEnd();
        }

        if (! empty($filtres['debut'])) {
            $query->where('conges.date_debut >=', $filtres['debut']);
        }

        if (! empty($filtres['fin'])) {
            $query->where('conges.date_fin <=', $filtres['fin']);
        }

        $demandes = $query->orderBy('conges.created_at', 'DESC')->findAll();

        return $this->render('rh/filtres', [
            'demandes'     => $demandes,
            'typesConge'   => $typesConge,
            'filtres'      => $filtres,
            'title'        => 'Filtrer les demandes',
        ]);
    }

    public function soldesEquipe()
    {
        $employeModel = new EmployeModel();
        $soldeModel = new SoldeModel();

        $filtreDepartement = $this->request->getGet('departement_id') ?? '';

        $query = $employeModel
            ->select('employes.id, employes.nom, employes.prenom, employes.email, employes.actif, departements.nom as departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left');

        if (! empty($filtreDepartement)) {
            $query->where('employes.departement_id', (int) $filtreDepartement);
        }

        $employes = $query->orderBy('employes.nom', 'ASC')->findAll();

        foreach ($employes as &$emp) {
            $soldes = $soldeModel
                ->select('soldes.*, types_conge.libelle as type_libelle')
                ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                ->where('soldes.employe_id', $emp['id'])
                ->findAll();

            $emp['soldes'] = array_map(function ($s) {
                $s['solde_restant'] = $s['jours_attribues'] - $s['jours_pris'];
                return $s;
            }, $soldes);
        }

        $departementModel = new \App\Models\DepartementModel();
        $departements = $departementModel->orderBy('nom', 'ASC')->findAll();

        return $this->render('rh/soldes_equipe', [
            'employes'     => $employes,
            'departements' => $departements,
            'filtreDep'    => $filtreDepartement,
            'title'        => "Soldes de l'equipe",
        ]);
    }
}