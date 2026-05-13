<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['nom', 'email', 'password', 'role', 'departement_id', 'date_embauche', 'actif'];

    protected $validationRules = [
        'nom' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[employes.email,id,{id}]',
        'password' => 'permit_empty|min_length[4]',
        'role' => 'required|in_list[employe,rh,admin]',
    ];

    protected $validationMessages = [
        'nom' => [
            'required' => 'Le nom est requis.',
            'min_length' => 'Le nom doit contenir au moins 3 caractères.'
        ],
        'email' => [
            'required' => 'L\'email est requis.',
            'valid_email' => 'Email invalide.',
            'is_unique' => 'Cet email est déjà utilisé.'
        ]
    ];
    
    // PAS DE beforeInsert / beforeUpdate / hashPassword
}