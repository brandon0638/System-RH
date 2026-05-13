<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['employe_id', 'type_conge_id', 'date_debut', 'date_fin', 'nb_jours', 'motif', 'statut', 'commentaire_rh'];
    
    // TIMESTAMPS AUTOMATIQUES
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'employe_id' => 'required|integer',
        'type_conge_id' => 'required|integer',
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
        'motif' => 'permit_empty|max_length[500]',
        'statut' => 'required|in_list[en_attente,approuvee,refusee,annulee]'
    ];
    
    public function getDemandesByEmploye($employe_id)
    {
        return $this->select('conges.*, types_conge.nom as type_nom')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->where('employe_id', $employe_id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
    
    public function getDemandesEnAttente()
    {
        return $this->select('conges.*, employes.nom as employe_nom, employes.email, employes.departement_id, types_conge.nom as type_nom')
                    ->join('employes', 'employes.id = conges.employe_id')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->where('statut', 'en_attente')
                    ->orderBy('date_debut', 'ASC')
                    ->findAll();
    }
    
    public function getDemandesHistorique()
    {
        return $this->select('conges.*, employes.nom as employe_nom, employes.email, employes.departement_id, types_conge.nom as type_nom')
                    ->join('employes', 'employes.id = conges.employe_id')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->where('statut !=', 'en_attente')
                    ->orderBy('updated_at', 'DESC')
                    ->findAll();
    }
    
    public function verifierChevauchement($employe_id, $date_debut, $date_fin, $exclude_id = null)
    {
        $builder = $this->where('employe_id', $employe_id)
                        ->where('statut !=', 'refusee')
                        ->where('statut !=', 'annulee')
                        ->groupStart()
                            ->where('date_debut <=', $date_fin)
                            ->where('date_fin >=', $date_debut)
                        ->groupEnd();
        
        if ($exclude_id) {
            $builder->where('id !=', $exclude_id);
        }
        
        return $builder->countAllResults() > 0;
    }
}