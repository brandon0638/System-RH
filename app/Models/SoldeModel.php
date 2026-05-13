<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table = 'soldes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['employe_id', 'type_conge_id', 'annee', 'total_jours', 'pris_jours', 'restant_jours'];
    
    public function getSoldesByEmploye($employe_id, $annee = null)
    {
        $annee = $annee ?? date('Y');
        return $this->select('soldes.*, types_conge.nom as type_nom, types_conge.couleur as type_couleur')
                    ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                    ->where('employe_id', $employe_id)
                    ->where('annee', $annee)
                    ->findAll();
    }
    
    public function getSoldeByType($employe_id, $type_conge_id, $annee = null)
    {
        $annee = $annee ?? date('Y');
        return $this->where('employe_id', $employe_id)
                    ->where('type_conge_id', $type_conge_id)
                    ->where('annee', $annee)
                    ->first();
    }
    
    public function decrementerSolde($employe_id, $type_conge_id, $nb_jours, $annee = null)
    {
        $annee = $annee ?? date('Y');
        $solde = $this->getSoldeByType($employe_id, $type_conge_id, $annee);
        
        if ($solde && $solde['restant_jours'] >= $nb_jours) {
            return $this->update($solde['id'], [
                'pris_jours' => $solde['pris_jours'] + $nb_jours,
                'restant_jours' => $solde['restant_jours'] - $nb_jours
            ]);
        }
        return false;
    }
    
    public function incrementerSolde($employe_id, $type_conge_id, $nb_jours, $annee = null)
    {
        $annee = $annee ?? date('Y');
        $solde = $this->getSoldeByType($employe_id, $type_conge_id, $annee);
        
        if ($solde) {
            return $this->update($solde['id'], [
                'pris_jours' => max(0, $solde['pris_jours'] - $nb_jours),
                'restant_jours' => $solde['restant_jours'] + $nb_jours
            ]);
        }
        return false;
    }
}