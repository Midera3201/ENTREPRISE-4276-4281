<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCongesTable extends Migration
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
            'date_debut' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'date_fin' => [
                'type' => 'DATE',
                'null' => false,
            ],
            "nb_jours REAL NOT NULL CHECK (nb_jours > 0)",
            'motif' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            "statut TEXT NOT NULL DEFAULT 'en_attente' CHECK (statut IN ('en_attente', 'approuve', 'refuse', 'annule'))",
            'commentaire_rh' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            "date_soumission DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP",
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            "CHECK (date(date_fin) >= date(date_debut))",
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('employe_id');
        $this->forge->addKey('type_conge_id');
        $this->forge->addKey('statut');
        $this->forge->addKey('date_debut');
        $this->forge->addKey('date_fin');
        $this->forge->addForeignKey('employe_id', 'employes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('conges', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('conges', true);
    }
}
