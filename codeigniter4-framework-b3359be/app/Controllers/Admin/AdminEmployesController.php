<?php

namespace App\Controllers\Admin;

use App\Models\EmployeModel;
use App\Models\DepartementModel;

class AdminEmployesController extends AdminBaseController
{
    public function index()
    {
        $model = new EmployeModel();
        $employes = $model->select('employes.*, departements.nom as departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->orderBy('employes.nom', 'ASC')
            ->findAll();

        return $this->render('admin/employes/index', [
            'employes' => $employes,
            'title'    => 'Gestion des employés',
        ]);
    }

    public function create()
    {
        $departementModel = new DepartementModel();
        $departements = $departementModel->orderBy('nom', 'ASC')->findAll();

        return $this->render('admin/employes/form', [
            'departements' => $departements,
            'title'        => 'Ajouter un employé',
            'errors'       => session()->getFlashdata('errors') ?? [],
            'oldInput'     => session()->getFlashdata('oldInput') ?? [],
        ]);
    }

    public function store()
    {
        $model = new EmployeModel();

        $rules = [
            'nom'           => 'required|min_length[2]|max_length[100]',
            'prenom'        => 'required|min_length[2]|max_length[100]',
            'email'         => 'required|valid_email|is_unique[employes.email]',
            'password_hash' => 'required|min_length[6]',
            'role'          => 'required|in_list[employe,rh,admin]',
            'departement_id'=> 'permit_empty|integer',
            'date_embauche' => 'permit_empty|valid_date',
            'actif'         => 'required|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to('/admin/employes/create');
        }

        $data = [
            'nom'           => $this->request->getPost('nom'),
            'prenom'        => $this->request->getPost('prenom'),
            'email'         => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password_hash'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
            'departement_id'=> $this->request->getPost('departement_id') ?: null,
            'date_embauche' => $this->request->getPost('date_embauche') ?: null,
            'actif'         => (int) $this->request->getPost('actif'),
        ];

        $model->insert($data);

        return redirect()->to('/admin/employes')->with('success', 'Employé créé avec succès.');
    }

    public function edit(int $id)
    {
        $model = new EmployeModel();
        $employe = $model->find($id);

        if (! $employe) {
            return redirect()->to('/admin/employes')->with('error', 'Employé introuvable.');
        }

        $departementModel = new DepartementModel();
        $departements = $departementModel->orderBy('nom', 'ASC')->findAll();

        return $this->render('admin/employes/form', [
            'employe'      => $employe,
            'departements' => $departements,
            'title'        => "Modifier l'employé #{$id}",
            'errors'       => session()->getFlashdata('errors') ?? [],
            'oldInput'     => session()->getFlashdata('oldInput') ?? [],
        ]);
    }

    public function update(int $id)
    {
        $model = new EmployeModel();
        $employe = $model->find($id);

        if (! $employe) {
            return redirect()->to('/admin/employes')->with('error', 'Employé introuvable.');
        }

        $rules = [
            'nom'           => 'required|min_length[2]|max_length[100]',
            'prenom'        => 'required|min_length[2]|max_length[100]',
            'email'         => "required|valid_email|is_unique[employes.email,id,{$id}]",
            'role'          => 'required|in_list[employe,rh,admin]',
            'departement_id'=> 'permit_empty|integer',
            'date_embauche' => 'permit_empty|valid_date',
            'actif'         => 'required|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to("/admin/employes/{$id}/edit");
        }

        $data = [
            'nom'           => $this->request->getPost('nom'),
            'prenom'        => $this->request->getPost('prenom'),
            'email'         => $this->request->getPost('email'),
            'role'          => $this->request->getPost('role'),
            'departement_id'=> $this->request->getPost('departement_id') ?: null,
            'date_embauche' => $this->request->getPost('date_embauche') ?: null,
            'actif'         => (int) $this->request->getPost('actif'),
        ];

        $password = $this->request->getPost('password_hash');
        if (! empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);

        return redirect()->to('/admin/employes')->with('success', 'Employé mis à jour avec succès.');
    }

    public function confirmDelete(int $id)
    {
        $model = new EmployeModel();
        $employe = $model->find($id);

        if (! $employe) {
            return redirect()->to('/admin/employes')->with('error', 'Employé introuvable.');
        }

        return $this->render('admin/employes/confirm_delete', [
            'employe' => $employe,
            'title'   => "Supprimer l'employé #{$id}",
        ]);
    }

    public function delete(int $id)
    {
        $model = new EmployeModel();
        $employe = $model->find($id);

        if (! $employe) {
            return redirect()->to('/admin/employes')->with('error', 'Employé introuvable.');
        }

        $model->delete($id);

        return redirect()->to('/admin/employes')->with('success', 'Employé supprimé avec succès.');
    }
}