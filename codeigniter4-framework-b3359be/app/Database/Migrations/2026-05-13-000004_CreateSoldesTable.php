<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSoldesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'employe_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'type_conge_id' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'annee' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            "jours_attribues REAL NOT NULL CHECK (jours_attribues >= 0)",
            "jours_pris REAL NOT NULL DEFAULT 0 CHECK (jours_pris >= 0)",
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
        $this->forge->addUniqueKey(['employe_id', 'type_conge_id', 'annee']);
        $this->forge->addKey('employe_id');
        $this->forge->addKey('type_conge_id');
        $this->forge->addForeignKey('employe_id', 'employes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('soldes', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('soldes', true);
    }
}
