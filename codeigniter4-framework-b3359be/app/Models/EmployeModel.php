<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'departement_id',
        'nom',
        'prenom',
        'email',
        'password_hash',
        'role',
        'actif',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;

    public function findByEmail(string $email): ?array
    {
        $result = $this->where('email', $email)->first();

        return $result ?: null;
    }
}
