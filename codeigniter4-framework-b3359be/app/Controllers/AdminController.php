<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\CongeModel;
use App\Models\DepartementModel;
use CodeIgniter\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin/dashboard');
    }

    public function dashboard()
    {
        $employeModel = new EmployeModel();
        $congeModel = new CongeModel();
        $departementModel = new DepartementModel();

        $totalEmployes = $employeModel->countAllResults();
        $demandesEnAttente = $congeModel->where('status', 'en attente')->countAllResults();
        $demandesApprouveesMois = $congeModel
            ->where('status', 'approuve')
            ->where('MONTH(date_approuve)', date('m'))
            ->countAllResults();
        $nbDepartements = $departementModel->countAllResults();
        $absentsAujourdhui = $congeModel
            ->where('status', 'approuve')
            ->where('DATE(date_debut) <=', date('Y-m-d'))
            ->where('DATE(date_fin) >=', date('Y-m-d'))
            ->countAllResults();

        $demandesRecentes = $congeModel
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $employesAbsents = $employeModel
            ->join('conges', 'employes.id = conges.employe_id')
            ->where('conges.status', 'approuve')
            ->where('DATE(conges.date_debut) <=', date('Y-m-d'))
            ->where('DATE(conges.date_fin) >=', date('Y-m-d'))
            ->findAll();

        return view('admin/dashboard', [
            'totalEmployes' => $totalEmployes,
            'demandesEnAttente' => $demandesEnAttente,
            'demandesApprouveesMois' => $demandesApprouveesMois,
            'nbDepartements' => $nbDepartements,
            'absentsAujourdhui' => $absentsAujourdhui,
            'demandesRecentes' => $demandesRecentes,
            'employesAbsents' => $employesAbsents,
        ]);
    }

    public function employes()
    {
        $employeModel = new EmployeModel();
        $search = $this->request->getGet('search');
        $departement = $this->request->getGet('departement');

        $query = $employeModel;

        if ($search) {
            $query = $query->like('prenom', $search)->orLike('nom', $search);
        }

        if ($departement) {
            $query = $query->where('departement', $departement);
        }

        $employes = $query->findAll();

        return view('admin/employes', ['employes' => $employes]);
    }

    public function storeEmploye()
    {
        $employeModel = new EmployeModel();

        $data = [
            'prenom' => $this->request->getPost('prenom'),
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'departement' => $this->request->getPost('departement'),
            'date_embauche' => $this->request->getPost('date_embauche'),
            'role' => 'employe',
            'statut' => 'actif',
            'solde_annuel' => 30,
        ];

        $employeModel->insert($data);

        return redirect()->to('admin/employes');
    }

    public function editEmploye($id)
    {
        $employeModel = new EmployeModel();
        $employe = $employeModel->find($id);

        return view('admin/edit_employe', ['employe' => $employe]);
    }

    public function updateEmploye($id)
    {
        $employeModel = new EmployeModel();

        $data = [
            'prenom' => $this->request->getPost('prenom'),
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'departement' => $this->request->getPost('departement'),
            'date_embauche' => $this->request->getPost('date_embauche'),
        ];

        $employeModel->update($id, $data);

        return redirect()->to('admin/employes');
    }

    public function toggleStatut($id)
    {
        $employeModel = new EmployeModel();
        $employe = $employeModel->find($id);

        $newStatut = $employe['statut'] === 'actif' ? 'inactif' : 'actif';
        $employeModel->update($id, ['statut' => $newStatut]);

        return redirect()->to('admin/employes');
    }

    public function historique()
    {
        $congeModel = new CongeModel();
        $employeModel = new EmployeModel();

        $filters = [
            'statut' => $this->request->getGet('statut'),
            'departement' => $this->request->getGet('departement'),
            'employe' => $this->request->getGet('employe'),
            'date_debut' => $this->request->getGet('date_debut'),
            'date_fin' => $this->request->getGet('date_fin'),
        ];

        $query = $congeModel
            ->select('conges.*, employes.nom as employe_nom, employes.prenom as employe_prenom, employes.departement, types_conge.libelle as type_conge')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_id');

        if ($filters['statut']) {
            $query->where('conges.statut', $filters['statut']);
        }

        if ($filters['departement']) {
            $query->where('employes.departement', $filters['departement']);
        }

        if ($filters['employe']) {
            $query->where('employes.id', $filters['employe']);
        }

        if ($filters['date_debut'] && $filters['date_fin']) {
            $query->where('conges.date_debut >=', $filters['date_debut'])
                  ->where('conges.date_fin <=', $filters['date_fin']);
        }

        $demandes = $query->paginate(10);
        $pager = $query->pager;

        $employes = $employeModel->findAll();

        return view('admin/historique', [
            'demandes' => $demandes,
            'pager' => $pager,
            'filters' => $filters,
            'employes' => $employes,
        ]);
    }

    public function departements()
    {
        $departementModel = new DepartementModel();
        $departements = $departementModel->findAll();

        return view('admin/departements', ['departements' => $departements]);
    }

    public function storeDepartement()
    {
        $departementModel = new DepartementModel();

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
        ];

        $departementModel->insert($data);

        return redirect()->to('admin/departements');
    }

    public function editDepartement($id)
    {
        $departementModel = new DepartementModel();
        $departement = $departementModel->find($id);

        return view('admin/edit_departement', ['departement' => $departement]);
    }

    public function updateDepartement($id)
    {
        $departementModel = new DepartementModel();

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
        ];

        $departementModel->update($id, $data);

        return redirect()->to('admin/departements');
    }

    public function deleteDepartement($id)
    {
        $departementModel = new DepartementModel();
        $departementModel->delete($id);

        return redirect()->to('admin/departements');
    }

    public function typesConge()
    {
        $typeCongeModel = new TypeCongeModel();
        $types = $typeCongeModel->findAll();

        return view('admin/types_conge', ['types' => $types]);
    }

    public function storeType()
    {
        $typeCongeModel = new TypeCongeModel();

        $data = [
            'libelle' => $this->request->getPost('libelle'),
            'jours_annuels' => $this->request->getPost('jours_annuels'),
            'deductible' => $this->request->getPost('deductible'),
        ];

        $typeCongeModel->insert($data);

        return redirect()->to('admin/typesConge');
    }

    public function editType($id)
    {
        $typeCongeModel = new TypeCongeModel();
        $type = $typeCongeModel->find($id);

        return view('admin/edit_type', ['type' => $type]);
    }

    public function updateType($id)
    {
        $typeCongeModel = new TypeCongeModel();

        $data = [
            'libelle' => $this->request->getPost('libelle'),
            'jours_annuels' => $this->request->getPost('jours_annuels'),
            'deductible' => $this->request->getPost('deductible'),
        ];

        $typeCongeModel->update($id, $data);

        return redirect()->to('admin/typesConge');
    }

    public function deleteType($id)
    {
        $typeCongeModel = new TypeCongeModel();
        $typeCongeModel->delete($id);

        return redirect()->to('admin/typesConge');
    }
}