<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypesCongeTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'libelle' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'jours_annuels' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            "deductible INTEGER NOT NULL DEFAULT 1 CHECK (deductible IN (0, 1))",
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
        $this->forge->addUniqueKey('libelle');
        $this->forge->createTable('types_conge', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('types_conge', true);
    }
}
