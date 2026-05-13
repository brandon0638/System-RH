<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table = 'departements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['nom', 'created_at'];

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[100]',
    ];

    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom du département est requis.',
            'min_length' => 'Le nom doit contenir au moins 2 caractères.',
            'max_length' => 'Le nom ne peut pas dépasser 100 caractères.'
        ]
    ];
}