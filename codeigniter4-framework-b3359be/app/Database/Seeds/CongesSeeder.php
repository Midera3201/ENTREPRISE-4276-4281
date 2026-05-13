<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CongesSeeder extends Seeder
{
    public function run(): void
    {
        $employes = $this->db->table('employes')->get()->getResultArray();
        $types = $this->db->table('types_conge')->get()->getResultArray();

        $employeByEmail = [];
        foreach ($employes as $employe) {
            $employeByEmail[$employe['email']] = (int) $employe['id'];
        }

        $typeByLibelle = [];
        foreach ($types as $type) {
            $typeByLibelle[$type['libelle']] = (int) $type['id'];
        }

        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        $data = [
            [
                'employe_id' => $employeByEmail['hery@techmada.mg'],
                'type_conge_id' => $typeByLibelle['annuel'],
                'date_debut' => date('Y-m-d', strtotime($today . ' +7 days')),
                'date_fin' => date('Y-m-d', strtotime($today . ' +10 days')),
                'nb_jours' => 4,
                'motif' => 'Vacances annuelles',
                'statut' => 'en_attente',
                'commentaire_rh' => null,
                'date_soumission' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employe_id' => $employeByEmail['mina@techmada.mg'],
                'type_conge_id' => $typeByLibelle['maladie'],
                'date_debut' => date('Y-m-d', strtotime($today . ' -3 days')),
                'date_fin' => date('Y-m-d', strtotime($today . ' -1 days')),
                'nb_jours' => 3,
                'motif' => 'Repos medical',
                'statut' => 'approuve',
                'commentaire_rh' => 'Bon retablissement',
                'date_soumission' => date('Y-m-d H:i:s', strtotime($today . ' -5 days')),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employe_id' => $employeByEmail['lala@techmada.mg'],
                'type_conge_id' => $typeByLibelle['special'],
                'date_debut' => date('Y-m-d', strtotime($today . ' +2 days')),
                'date_fin' => date('Y-m-d', strtotime($today . ' +2 days')),
                'nb_jours' => 1,
                'motif' => 'Evenement familial',
                'statut' => 'refuse',
                'commentaire_rh' => 'Justificatif manquant',
                'date_soumission' => date('Y-m-d H:i:s', strtotime($today . ' -1 days')),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'employe_id' => $employeByEmail['hery@techmada.mg'],
                'type_conge_id' => $typeByLibelle['sans solde'],
                'date_debut' => date('Y-m-d', strtotime($today . ' +15 days')),
                'date_fin' => date('Y-m-d', strtotime($today . ' +17 days')),
                'nb_jours' => 3,
                'motif' => 'Conges personnels',
                'statut' => 'annule',
                'commentaire_rh' => 'Demande annulee par employe',
                'date_soumission' => date('Y-m-d H:i:s', strtotime($today . ' -10 days')),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('conges')->insertBatch($data);
    }
}
