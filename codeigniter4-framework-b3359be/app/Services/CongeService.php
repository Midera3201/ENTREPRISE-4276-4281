<?php

namespace App\Services;

use CodeIgniter\Database\ConnectionInterface;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class CongeService
{
    private CongeModel $congeModel;
    private SoldeModel $soldeModel;
    private TypeCongeModel $typeCongeModel;
    private \CodeIgniter\Database\ConnectionInterface $db;

    public function __construct()
    {
        $this->congeModel    = new CongeModel();
        $this->soldeModel    = new SoldeModel();
        $this->typeCongeModel = new TypeCongeModel();
        $this->db            = \Config\Database::connect();
    }

    /**
     * Calculer le nombre de jours ouvrables entre deux dates.
     * Exclut les samedis et dimanches.
     */
    public function calculerNbJours(string $dateDebut, string $dateFin): float
    {
        $debut = new \DateTime($dateDebut);
        $fin = new \DateTime($dateFin);
        $fin->modify('+1 day'); // Inclure le dernier jour

        $jours = 0;
        $interval = new \DateInterval('P1D');
        $periode = new \DatePeriod($debut, $interval, $fin);

        foreach ($periode as $date) {
            $jourSemaine = (int) $date->format('N'); // 1=Lundi ... 7=Dimanche
            if ($jourSemaine <= 5) {
                $jours++;
            }
        }

        return (float) $jours;
    }

    /**
     * Calculer le nombre de jours calendaires (week-end inclus).
     */
    public function calculerNbJoursCalendaires(string $dateDebut, string $dateFin): int
    {
        $debut = new \DateTime($dateDebut);
        $fin = new \DateTime($dateFin);

        return (int) $fin->diff($debut)->days + 1;
    }

    /**
     * Vérifier le solde avant approbation.
     * Retourne un tableau avec 'insuffisant' (bool) et 'solde_restant' (float).
     */
    public function verifierSoldeAvantApprobation(int $employeId, int $typeCongeId, int $annee, float $nbJours): array
    {
        $typeConge = $this->typeCongeModel->find($typeCongeId);

        // Si non déductible, pas de vérification de solde
        if (! $typeConge || ! $typeConge['deductible']) {
            return ['insuffisant' => false, 'solde_restant' => null];
        }

        $solde = $this->soldeModel
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->first();

        if (! $solde) {
            return ['insuffisant' => true, 'solde_restant' => 0];
        }

        $soldeRestant = $solde['jours_attribues'] - $solde['jours_pris'];

        return [
            'insuffisant' => $soldeRestant < $nbJours,
            'solde_restant' => $soldeRestant,
        ];
    }

    /**
     * Déduire le solde après approbation.
     * Utilise une transaction SQL.
     */
    public function deduireSoldeApresApprobation(int $employeId, int $typeCongeId, int $annee, float $nbJours): bool
    {
        $solde = $this->soldeModel
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->first();

        $this->db->transStart();

        if ($solde) {
            $nouveauPris = $solde['jours_pris'] + $nbJours;

            if ($nouveauPris > $solde['jours_attribues']) {
                $this->db->transRollback();
                return false;
            }

            $this->soldeModel->update($solde['id'], ['jours_pris' => $nouveauPris]);
        } else {
            // Créer le solde si inexistant (avec déduction)
            $this->soldeModel->insert([
                'employe_id'     => $employeId,
                'type_conge_id'  => $typeCongeId,
                'annee'          => $annee,
                'jours_attribues'=> 0,
                'jours_pris'     => $nbJours,
            ]);
        }

        $this->db->transComplete();

        return $this->db->transStatus() !== false;
    }

    /**
     * Recréditer le solde après annulation d'une demande approuvée.
     */
    public function recrediterSoldeApresAnnulation(int $employeId, int $typeCongeId, int $annee, float $nbJours): bool
    {
        $solde = $this->soldeModel
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->first();

        if (! $solde) {
            return false;
        }

        $nouveauPris = max(0, $solde['jours_pris'] - $nbJours);

        return $this->soldeModel->update($solde['id'], ['jours_pris' => $nouveauPris]);
    }

    /**
     * Vérifier les chevauchements de congés existants.
     * Retourne le congé en conflit ou null.
     */
    public function verifierChevauchement(int $employeId, string $dateDebut, string $dateFin, ?int $exclureId = null): ?array
    {
        $query = $this->congeModel
            ->where('employe_id', $employeId)
            ->where('statut !=', 'refuse')
            ->where("date_debut <= ", $dateFin)
            ->where("date_fin >= ", $dateDebut);

        if ($exclureId !== null) {
            $query->where('id !=', $exclureId);
        }

        return $query->first();
    }

    /**
     * Calculer le solde restant pour un employé/type/année.
     */
    public function calculerSoldeRestant(int $employeId, int $typeCongeId, int $annee): float
    {
        $solde = $this->soldeModel
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->first();

        if (! $solde) {
            return 0.0;
        }

        return (float) ($solde['jours_attribues'] - $solde['jours_pris']);
    }

    /**
     * Générer un message de solde insuffisant détaillé.
     */
    public function verifierSoldeInsuffisantMessage(int $employeId, int $typeCongeId, int $annee, float $nbJours): string
    {
        $typeConge = $this->typeCongeModel->find($typeCongeId);
        $solde = $this->soldeModel
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->first();

        $typeLibelle = $typeConge ? $typeConge['libelle'] : 'Congé';
        $soldeRestant = $solde ? ($solde['jours_attribues'] - $solde['jours_pris']) : 0;
        $manquants = $nbJours - $soldeRestant;

        if ($soldeRestant <= 0) {
            return "Solde insuffisant. Vous n'avez plus de {$typeLibelle} disponible pour l'année {$annee}. Demandez à votre RH d'ajouter des jours.";
        }

        return "Solde insuffisant. Il vous manque {$manquants} jours de {$typeLibelle} pour l'année {$annee} (restant : {$soldeRestant} jours, demandé : {$nbJours} jours).";
    }

    /**
     * Initialiser les soldes pour un employé (typiquement à l'embauche ou début d'année).
     */
    public function initialiserSoldes(int $employeId, int $annee): bool
    {
        $typeCongeModel = new TypeCongeModel();
        $typesConge = $typeCongeModel->findAll();

        $this->db->transStart();

        foreach ($typesConge as $tc) {
            $existe = $this->soldeModel
                ->where('employe_id', $employeId)
                ->where('type_conge_id', $tc['id'])
                ->where('annee', $annee)
                ->first();

            if (! $existe) {
                $this->soldeModel->insert([
                    'employe_id'     => $employeId,
                    'type_conge_id'  => $tc['id'],
                    'annee'          => $annee,
                    'jours_attribues'=> $tc['deductible'] ? $tc['jours_annuels'] : 0,
                    'jours_pris'     => 0,
                ]);
            }
        }

        $this->db->transComplete();

        return $this->db->transStatus() !== false;
    }
}