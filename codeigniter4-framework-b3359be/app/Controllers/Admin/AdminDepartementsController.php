<?php

namespace App\Controllers\Admin;

use App\Models\DepartementModel;
use App\Models\EmployeModel;

class AdminDepartementsController extends AdminBaseController
{
    public function index()
    {
        $model = new DepartementModel();
        $departements = $model->orderBy('nom', 'ASC')->findAll();

        return $this->render('admin/departements/index', [
            'departements' => $departements,
            'title'        => 'Gestion des départements',
        ]);
    }

    public function create()
    {
        return $this->render('admin/departements/form', [
            'title'    => 'Ajouter un département',
            'errors'   => session()->getFlashdata('errors') ?? [],
            'oldInput' => session()->getFlashdata('oldInput') ?? [],
        ]);
    }

    public function store()
    {
        $model = new DepartementModel();

        $rules = [
            'nom'        => 'required|min_length[2]|max_length[255]|is_unique[departements.nom]',
            'description'=> 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to('/admin/departements/create');
        }

        $data = [
            'nom'        => $this->request->getPost('nom'),
            'description'=> $this->request->getPost('description') ?: null,
        ];

        $model->insert($data);

        return redirect()->to('/admin/departements')->with('success', 'Département créé avec succès.');
    }

    public function edit(int $id)
    {
        $model = new DepartementModel();
        $departement = $model->find($id);

        if (! $departement) {
            return redirect()->to('/admin/departements')->with('error', 'Département introuvable.');
        }

        return $this->render('admin/departements/form', [
            'departement' => $departement,
            'title'       => "Modifier le département #{$id}",
            'errors'      => session()->getFlashdata('errors') ?? [],
            'oldInput'    => session()->getFlashdata('oldInput') ?? [],
        ]);
    }

    public function update(int $id)
    {
        $model = new DepartementModel();
        $departement = $model->find($id);

        if (! $departement) {
            return redirect()->to('/admin/departements')->with('error', 'Département introuvable.');
        }

        $rules = [
            'nom'        => "required|min_length[2]|max_length[255]|is_unique[departements.nom,id,{$id}]",
            'description'=> 'permit_empty|max_length[1000]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to("/admin/departements/{$id}/edit");
        }

        $data = [
            'nom'        => $this->request->getPost('nom'),
            'description'=> $this->request->getPost('description') ?: null,
        ];

        $model->update($id, $data);

        return redirect()->to('/admin/departements')->with('success', 'Département mis à jour avec succès.');
    }

    public function confirmDelete(int $id)
    {
        $model = new DepartementModel();
        $departement = $model->find($id);

        if (! $departement) {
            return redirect()->to('/admin/departements')->with('error', 'Département introuvable.');
        }

        $employeModel = new EmployeModel();
        $nbEmployes = $employeModel->where('departement_id', $id)->countAllResults();

        return $this->render('admin/departements/confirm_delete', [
            'departement' => $departement,
            'nbEmployes'  => $nbEmployes,
            'title'       => "Supprimer le département #{$id}",
        ]);
    }

    public function delete(int $id)
    {
        $model = new DepartementModel();
        $departement = $model->find($id);

        if (! $departement) {
            return redirect()->to('/admin/departements')->with('error', 'Département introuvable.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Réaffecter les employés rattachés à NULL avant de supprimer
        $employeModel = new EmployeModel();
        $employeModel->where('departement_id', $id)->set(['departement_id' => null])->update();

        $model->delete($id);

        $db->transComplete();

        return redirect()->to('/admin/departements')->with('success', 'Département supprimé avec succès.');
    }
}