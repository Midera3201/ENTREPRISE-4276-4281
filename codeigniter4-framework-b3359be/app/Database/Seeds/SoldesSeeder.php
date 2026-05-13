<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SoldesSeeder extends Seeder
{
    public function run(): void
    {
        $employes = $this->db->table('employes')->get()->getResultArray();
        $types = $this->db->table('types_conge')->get()->getResultArray();

        $employeIds = array_column($employes, 'id');
        $typeByLibelle = [];
        foreach ($types as $type) {
            $typeByLibelle[$type['libelle']] = (int) $type['id'];
        }

        $annee = (int) date('Y');
        $now = date('Y-m-d H:i:s');

        $data = [];
        foreach ($employeIds as $employeId) {
            $data[] = [
                'employe_id' => $employeId,
                'type_conge_id' => $typeByLibelle['annuel'],
                'annee' => $annee,
                'jours_attribues' => 30,
                'jours_pris' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $data[] = [
                'employe_id' => $employeId,
                'type_conge_id' => $typeByLibelle['maladie'],
                'annee' => $annee,
                'jours_attribues' => 10,
                'jours_pris' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $data[] = [
                'employe_id' => $employeId,
                'type_conge_id' => $typeByLibelle['special'],
                'annee' => $annee,
                'jours_attribues' => 5,
                'jours_pris' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $data[] = [
                'employe_id' => $employeId,
                'type_conge_id' => $typeByLibelle['sans solde'],
                'annee' => $annee,
                'jours_attribues' => 0,
                'jours_pris' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->db->table('soldes')->insertBatch($data);
    }
}
