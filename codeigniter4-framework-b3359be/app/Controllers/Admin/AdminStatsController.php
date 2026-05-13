<?php

namespace App\Controllers\Admin;

use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\DepartementModel;
use CodeIgniter\Database\ConnectionInterface;

class AdminStatsController extends AdminBaseController
{
    public function index()
    {
        $congeModel   = new CongeModel();
        $employeModel = new EmployeModel();
        $soldeModel   = new SoldeModel();
        $db           = \Config\Database::connect();

        // Statistiques générales
        $stats['totalEmployes']    = $employeModel->countAllResults();
        $stats['totalDepartements']= (new DepartementModel())->countAllResults();
        $stats['totalConges']      = $congeModel->countAllResults();
        $stats['totalSoldes']      = $soldeModel->countAllResults();

        // Congés par statut
        $stats['enAttente']  = $congeModel->where('statut', 'en_attente')->countAllResults();
        $stats['approuves']  = $congeModel->where('statut', 'approuve')->countAllResults();
        $stats['refuses']    = $congeModel->where('statut', 'refuse')->countAllResults();
        $stats['annules']    = $congeModel->where('statut', 'annule')->countAllResults();

        // Congés récents (5 derniers)
        $stats['congesRecents'] = $congeModel
            ->select('conges.*, employes.nom, employes.prenom, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('conges.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Congés en attente de traitement (5 derniers)
        $stats['congesEnAttente'] = $congeModel
            ->select('conges.*, employes.nom, employes.prenom, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.statut', 'en_attente')
            ->orderBy('conges.date_soumission', 'ASC')
            ->limit(5)
            ->findAll();

        // Employés par département
        $builder = $db->table('employes');
        $builder->select('departements.nom as departement_nom, COUNT(employes.id) as nb_employes');
        $builder->join('departements', 'departements.id = employes.departement_id', 'left');
        $builder->groupBy('departements.nom');
        $builder->orderBy('nb_employes', 'DESC');
        $stats['employesParDepartement'] = $builder->get()->getResultArray();

        // Congés par mois (année en cours)
        $anneeEnCours = date('Y');
        $builderMois = $db->table('conges');
        $builderMois->select("EXTRACT(MONTH FROM date_soumission) as mois, COUNT(*) as nb");
        $builderMois->where("EXTRACT(YEAR FROM date_soumission)", $anneeEnCours);
        $builderMois->groupBy("EXTRACT(MONTH FROM date_soumission)");
        $builderMois->orderBy('mois', 'ASC');
        $stats['congesParMois'] = $builderMois->get()->getResultArray();

        // Soldes faibles (< 2 jours)
        $stats['soldesFaibles'] = $soldeModel
            ->select('soldes.*, employes.nom, employes.prenom, types_conge.libelle as type_libelle')
            ->join('employes', 'employes.id = soldes.employe_id')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('(soldes.jours_attribues - soldes.jours_pris) <', 2)
            ->where('soldes.jours_attribues >', 0)
            ->findAll();

        return $this->render('admin/stats/index', [
            'stats'  => $stats,
            'annee'  => $anneeEnCours,
            'title'  => 'Tableau de bord administrateur',
        ]);
    }
}