<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmployesSeeder extends Seeder
{
    public function run(): void
    {
        $departements = $this->db->table('departements')->get()->getResultArray();
        $departementByName = [];
        foreach ($departements as $departement) {
            $departementByName[$departement['nom']] = (int) $departement['id'];
        }

        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'nom' => 'Admin',
                'prenom' => 'Super',
                'email' => 'admin@techmada.mg',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'departement_id' => $departementByName['IT'] ?? null,
                'date_embauche' => '2022-01-10',
                'actif' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nom' => 'Rakoto',
                'prenom' => 'Soa',
                'email' => 'rh@techmada.mg',
                'password' => password_hash('rh12345', PASSWORD_DEFAULT),
                'role' => 'rh',
                'departement_id' => $departementByName['RH'] ?? null,
                'date_embauche' => '2021-05-22',
                'actif' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nom' => 'Rabe',
                'prenom' => 'Hery',
                'email' => 'hery@techmada.mg',
                'password' => password_hash('employe123', PASSWORD_DEFAULT),
                'role' => 'employe',
                'departement_id' => $departementByName['IT'] ?? null,
                'date_embauche' => '2023-03-14',
                'actif' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nom' => 'Andri',
                'prenom' => 'Mina',
                'email' => 'mina@techmada.mg',
                'password' => password_hash('employe123', PASSWORD_DEFAULT),
                'role' => 'employe',
                'departement_id' => $departementByName['Finance'] ?? null,
                'date_embauche' => '2022-09-01',
                'actif' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nom' => 'Rasoanaivo',
                'prenom' => 'Lala',
                'email' => 'lala@techmada.mg',
                'password' => password_hash('employe123', PASSWORD_DEFAULT),
                'role' => 'employe',
                'departement_id' => $departementByName['Marketing'] ?? null,
                'date_embauche' => '2024-02-05',
                'actif' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('employes')->insertBatch($data);
    }
}
