<?php

namespace App\Controllers\Admin;

use App\Models\SoldeModel;
use App\Models\EmployeModel;
use App\Models\TypeCongeModel;
use CodeIgniter\Database\ConnectionInterface;

class AdminSoldesController extends AdminBaseController
{
    public function index()
    {
        $soldeModel = new SoldeModel();
        $soldes = $soldeModel
            ->select('soldes.*, employes.nom, employes.prenom, employes.email, types_conge.libelle as type_conge_libelle')
            ->join('employes', 'employes.id = soldes.employe_id')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->orderBy('employes.nom', 'ASC')
            ->findAll();

        return $this->render('admin/soldes/index', [
            'soldes' => $soldes,
            'title'  => 'Gestion des soldes',
        ]);
    }

    public function create()
    {
        $employeModel = new EmployeModel();
        $typeCongeModel = new TypeCongeModel();

        return $this->render('admin/soldes/form', [
            'employes'    => $employeModel->orderBy('nom', 'ASC')->findAll(),
            'typesConge'  => $typeCongeModel->orderBy('libelle', 'ASC')->findAll(),
            'title'       => 'Initialiser un solde',
            'errors'      => session()->getFlashdata('errors') ?? [],
            'oldInput'    => session()->getFlashdata('oldInput') ?? [],
        ]);
    }

    public function store()
    {
        $soldeModel = new SoldeModel();

        $rules = [
            'employe_id'    => 'required|integer',
            'type_conge_id' => 'required|integer',
            'annee'         => 'required|integer|min_length[4]|max_length[4]',
            'jours_attribues'=> 'required|decimal|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to('/admin/soldes/create');
        }

        $employeId    = (int) $this->request->getPost('employe_id');
        $typeCongeId  = (int) $this->request->getPost('type_conge_id');
        $annee        = (int) $this->request->getPost('annee');
        $joursAttribues = (float) $this->request->getPost('jours_attribues');

        // Vérifier l'unicité employé / type / année
        $existing = $soldeModel
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->first();

        if ($existing) {
            return redirect()->to('/admin/soldes/create')
                ->with('error', 'Un solde existe déjà pour cet employé, ce type de congé et cette année.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $soldeModel->insert([
            'employe_id'     => $employeId,
            'type_conge_id'  => $typeCongeId,
            'annee'          => $annee,
            'jours_attribues'=> $joursAttribues,
            'jours_pris'     => 0,
        ]);

        $db->transComplete();

        return redirect()->to('/admin/soldes')->with('success', 'Solde initialisé avec succès.');
    }

    public function edit(int $id)
    {
        $soldeModel = new SoldeModel();
        $solde = $soldeModel->find($id);

        if (! $solde) {
            return redirect()->to('/admin/soldes')->with('error', 'Solde introuvable.');
        }

        $employeModel = new EmployeModel();
        $typeCongeModel = new TypeCongeModel();

        return $this->render('admin/soldes/form', [
            'solde'        => $solde,
            'employes'     => $employeModel->orderBy('nom', 'ASC')->findAll(),
            'typesConge'   => $typeCongeModel->orderBy('libelle', 'ASC')->findAll(),
            'title'        => "Modifier le solde #{$id}",
            'errors'       => session()->getFlashdata('errors') ?? [],
            'oldInput'     => session()->getFlashdata('oldInput') ?? [],
        ]);
    }

    public function update(int $id)
    {
        $soldeModel = new SoldeModel();
        $solde = $soldeModel->find($id);

        if (! $solde) {
            return redirect()->to('/admin/soldes')->with('error', 'Solde introuvable.');
        }

        $rules = [
            'employe_id'     => 'required|integer',
            'type_conge_id'  => 'required|integer',
            'annee'          => 'required|integer|min_length[4]|max_length[4]',
            'jours_attribues'=> 'required|decimal|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to("/admin/soldes/{$id}/edit");
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $soldeModel->update($id, [
            'employe_id'     => (int) $this->request->getPost('employe_id'),
            'type_conge_id'  => (int) $this->request->getPost('type_conge_id'),
            'annee'          => (int) $this->request->getPost('annee'),
            'jours_attribues'=> (float) $this->request->getPost('jours_attribues'),
        ]);

        $db->transComplete();

        return redirect()->to('/admin/soldes')->with('success', 'Solde mis à jour avec succès.');
    }

    public function confirmDelete(int $id)
    {
        $soldeModel = new SoldeModel();
        $solde = $soldeModel->find($id);

        if (! $solde) {
            return redirect()->to('/admin/soldes')->with('error', 'Solde introuvable.');
        }

        return $this->render('admin/soldes/confirm_delete', [
            'solde' => $solde,
            'title' => "Supprimer le solde #{$id}",
        ]);
    }

    public function delete(int $id)
    {
        $soldeModel = new SoldeModel();
        $solde = $soldeModel->find($id);

        if (! $solde) {
            return redirect()->to('/admin/soldes')->with('error', 'Solde introuvable.');
        }

        $soldeModel->delete($id);

        return redirect()->to('/admin/soldes')->with('success', 'Solde supprimé avec succès.');
    }
}