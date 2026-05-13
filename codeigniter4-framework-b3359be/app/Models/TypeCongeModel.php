<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table = 'types_conge';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'libelle',
        'jours_annuels',
        'deductible',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
}
