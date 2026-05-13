<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartementsSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            ['nom' => 'IT', 'description' => 'Informatique', 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'RH', 'description' => 'Ressources humaines', 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'Finance', 'description' => 'Finance', 'created_at' => $now, 'updated_at' => $now],
            ['nom' => 'Marketing', 'description' => 'Marketing', 'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('departements')->insertBatch($data);
    }
}
