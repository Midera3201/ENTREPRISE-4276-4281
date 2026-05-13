<?php

namespace App\Controllers\Admin;

use App\Models\TypeCongeModel;

class AdminTypesCongeController extends AdminBaseController
{
    public function index()
    {
        $model = new TypeCongeModel();
        $typesConge = $model->orderBy('libelle', 'ASC')->findAll();

        return $this->render('admin/types_conge/index', [
            'typesConge' => $typesConge,
            'title'      => 'Types de congé',
        ]);
    }

    public function create()
    {
        return $this->render('admin/types_conge/form', [
            'title'    => 'Ajouter un type de congé',
            'errors'   => session()->getFlashdata('errors') ?? [],
            'oldInput' => session()->getFlashdata('oldInput') ?? [],
        ]);
    }

    public function store()
    {
        $model = new TypeCongeModel();

        $rules = [
            'libelle'     => 'required|min_length[2]|max_length[255]|is_unique[types_conge.libelle]',
            'deductible'  => 'required|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to('/admin/types-conge/create');
        }

        $model->insert([
            'libelle'    => $this->request->getPost('libelle'),
            'deductible' => (int) $this->request->getPost('deductible'),
        ]);

        return redirect()->to('/admin/types-conge')->with('success', 'Type de congé créé avec succès.');
    }

    public function edit(int $id)
    {
        $model = new TypeCongeModel();
        $typeConge = $model->find($id);

        if (! $typeConge) {
            return redirect()->to('/admin/types-conge')->with('error', 'Type de congé introuvable.');
        }

        return $this->render('admin/types_conge/form', [
            'typeConge' => $typeConge,
            'title'     => "Modifier le type de congé #{$id}",
            'errors'    => session()->getFlashdata('errors') ?? [],
            'oldInput'  => session()->getFlashdata('oldInput') ?? [],
        ]);
    }

    public function update(int $id)
    {
        $model = new TypeCongeModel();
        $typeConge = $model->find($id);

        if (! $typeConge) {
            return redirect()->to('/admin/types-conge')->with('error', 'Type de congé introuvable.');
        }

        $rules = [
            'libelle'    => "required|min_length[2]|max_length[255]|is_unique[types_conge.libelle,id,{$id}]",
            'deductible' => 'required|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('oldInput', $this->request->getPost());
            return redirect()->to("/admin/types-conge/{$id}/edit");
        }

        $model->update($id, [
            'libelle'    => $this->request->getPost('libelle'),
            'deductible' => (int) $this->request->getPost('deductible'),
        ]);

        return redirect()->to('/admin/types-conge')->with('success', 'Type de congé mis à jour avec succès.');
    }

    public function confirmDelete(int $id)
    {
        $model = new TypeCongeModel();
        $typeConge = $model->find($id);

        if (! $typeConge) {
            return redirect()->to('/admin/types-conge')->with('error', 'Type de congé introuvable.');
        }

        return $this->render('admin/types_conge/confirm_delete', [
            'typeConge' => $typeConge,
            'title'     => "Supprimer le type de congé #{$id}",
        ]);
    }

    public function delete(int $id)
    {
        $model = new TypeCongeModel();
        $typeConge = $model->find($id);

        if (! $typeConge) {
            return redirect()->to('/admin/types-conge')->with('error', 'Type de congé introuvable.');
        }

        $model->delete($id);

        return redirect()->to('/admin/types-conge')->with('success', 'Type de congé supprimé avec succès.');
    }
}