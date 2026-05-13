<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypesCongeSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            ['libelle' => 'annuel', 'jours_annuels' => 30, 'deductible' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'maladie', 'jours_annuels' => 10, 'deductible' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'special', 'jours_annuels' => 5, 'deductible' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['libelle' => 'sans solde', 'jours_annuels' => 0, 'deductible' => 0, 'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('types_conge')->insertBatch($data);
    }
}
