<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypesCongeTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
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
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addField('deductible INTEGER NOT NULL DEFAULT 1 CHECK (deductible IN (0, 1))');

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('libelle');
        $this->forge->createTable('types_conge');
    }

    public function down(): void
    {
        $this->forge->dropTable('types_conge', true);
    }
}
