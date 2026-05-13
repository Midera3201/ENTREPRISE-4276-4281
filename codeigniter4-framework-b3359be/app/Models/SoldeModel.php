<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table = 'soldes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'annee',
        'solde_initial',
        'solde_restant',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
}
