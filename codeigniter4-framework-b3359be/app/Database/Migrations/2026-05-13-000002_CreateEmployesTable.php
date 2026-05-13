<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'prenom' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'email' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'password' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            "role TEXT NOT NULL DEFAULT 'employe' CHECK (role IN ('employe', 'rh', 'admin'))",
            'departement_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'date_embauche' => [
                'type' => 'DATE',
                'null' => true,
            ],
            "actif INTEGER NOT NULL DEFAULT 1 CHECK (actif IN (0, 1))",
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('departement_id');
        $this->forge->addForeignKey('departement_id', 'departements', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('employes', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('employes', true);
    }
}
