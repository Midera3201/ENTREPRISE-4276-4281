<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->query('PRAGMA foreign_keys = ON');

        $this->call(DepartementsSeeder::class);
        $this->call(TypesCongeSeeder::class);
        $this->call(EmployesSeeder::class);
        $this->call(SoldesSeeder::class);
        $this->call(CongesSeeder::class);
    }
}
