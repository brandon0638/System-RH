<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\EmployeModel;
use App\Models\TypeCongeModel;

class RhController extends BaseController
{
    public function dashboard()
    {
        $user = session()->get('user');
        
        // Vérifier le rôle
        if (!in_array($user['role'], ['rh', 'admin'])) {
            return redirect()->to('/employe/dashboard')->with('error', 'Accès non autorisé.');
        }
        
        $congeModel = new CongeModel();
        $employeModel = new EmployeModel();
        
        // Statistiques
        $en_attente = count($congeModel->where('statut', 'en_attente')->findAll());
        $approuvees_mois = count($congeModel->where('statut', 'approuvee')
            ->where('updated_at >=', date('Y-m-01 00:00:00'))
            ->findAll());
        $employes_actifs = count($employeModel->where('actif', 1)->findAll());
        $absents_aujourdhui = count($congeModel->where('statut', 'approuvee')
            ->where('date_debut <=', date('Y-m-d'))
            ->where('date_fin >=', date('Y-m-d'))
            ->findAll());
        
        // Demandes récentes
        $demandes_recentes = $congeModel->select('conges.*, employes.nom as employe_nom, employes.email, types_conge.nom as type_nom')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();
        
        // Demandes par type
        $types = (new TypeCongeModel())->findAll();
        $demandes_par_type = [];
        foreach ($types as $type) {
            $demandes_par_type[$type['nom']] = count($congeModel->where('type_conge_id', $type['id'])->findAll());
        }
        
        return view('rh/dashboard', [
            'en_attente' => $en_attente,
            'approuvees_mois' => $approuvees_mois,
            'employes_actifs' => $employes_actifs,
            'absents_aujourdhui' => $absents_aujourdhui,
            'demandes_recentes' => $demandes_recentes,
            'demandes_par_type' => $demandes_par_type,
            'user' => $user
        ]);
    }
    
    public function index()
    {
        $user = session()->get('user');
        
        if (!in_array($user['role'], ['rh', 'admin'])) {
            return redirect()->to('/employe/dashboard')->with('error', 'Accès non autorisé.');
        }
        
        $congeModel = new CongeModel();
        $employeModel = new EmployeModel();
        $soldeModel = new SoldeModel();
        
        // Demandes en attente
        $demandes = $congeModel->select('conges.*, employes.nom as employe_nom, employes.email, employes.departement_id, types_conge.nom as type_nom, types_conge.total_jours_par_an')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('statut', 'en_attente')
            ->orderBy('date_debut', 'ASC')
            ->findAll();
        
        // Récupérer les soldes pour chaque demande
        foreach ($demandes as &$demande) {
            $solde = $soldeModel->getSoldeByType($demande['employe_id'], $demande['type_conge_id']);
            $demande['solde_restant'] = $solde['restant_jours'] ?? 0;
        }
        
        // Liste des employés pour filtres
        $employes = $employeModel->where('actif', 1)->findAll();
        
        return view('rh/demandes', [
            'demandes' => $demandes,
            'employes' => $employes,
            'user' => $user
        ]);
    }
    
    public function approuver($id)
    {
        $user = session()->get('user');
        
        if (!in_array($user['role'], ['rh', 'admin'])) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }
        
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();
        
        $demande = $congeModel->find($id);
        
        if (!$demande) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }
        
        if ($demande['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }
        
        // Vérifier le solde
        $solde = $soldeModel->getSoldeByType($demande['employe_id'], $demande['type_conge_id']);
        
        if (!$solde || $solde['restant_jours'] < $demande['nb_jours']) {
            return redirect()->back()->with('error', 'Solde insuffisant pour approuver cette demande.');
        }
        
        // DÉDUIRE LE SOLDE (logique métier importante)
        $soldeModel->decrementerSolde($demande['employe_id'], $demande['type_conge_id'], $demande['nb_jours']);
        
        // Approuver la demande
        $congeModel->update($id, [
            'statut' => 'approuvee',
            'commentaire_rh' => $this->request->getPost('commentaire') ?? 'Approuvé par ' . $user['nom'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->back()->with('success', 'Demande approuvée. Le solde a été déduit automatiquement.');
    }
    
    public function refuser($id)
    {
        $user = session()->get('user');
        
        if (!in_array($user['role'], ['rh', 'admin'])) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }
        
        $congeModel = new CongeModel();
        
        $demande = $congeModel->find($id);
        
        if (!$demande) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }
        
        if ($demande['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }
        
        $commentaire = $this->request->getPost('commentaire');
        
        if (empty($commentaire)) {
            return redirect()->back()->with('error', 'Veuillez fournir un commentaire pour justifier le refus.');
        }
        
        $congeModel->update($id, [
            'statut' => 'refusee',
            'commentaire_rh' => $commentaire,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->back()->with('success', 'Demande refusée. Un commentaire a été envoyé à l\'employé.');
    }
    
    public function historique()
    {
        $user = session()->get('user');
        
        if (!in_array($user['role'], ['rh', 'admin'])) {
            return redirect()->to('/employe/dashboard')->with('error', 'Accès non autorisé.');
        }
        
        $congeModel = new CongeModel();
        
        $demandes = $congeModel->select('conges.*, employes.nom as employe_nom, employes.email, employes.departement_id, types_conge.nom as type_nom')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('statut !=', 'en_attente')
            ->orderBy('updated_at', 'DESC')
            ->findAll();
        
        return view('rh/historique', [
            'demandes' => $demandes,
            'user' => $user
        ]);
    }
    
   public function soldes()
    {
        $user = session()->get('user');
        
        if (!in_array($user['role'], ['rh', 'admin'])) {
            return redirect()->to('/employe/dashboard')->with('error', 'Accès non autorisé.');
        }
        
        $employeModel = new EmployeModel();
        $soldeModel = new SoldeModel();
        $typeCongeModel = new TypeCongeModel();
        
        // Récupérer directement les départements sans modèle
        $db = \Config\Database::connect();
        $departementsRaw = $db->table('departements')->get()->getResultArray();
        $departementsById = [];
        foreach ($departementsRaw as $d) {
            $departementsById[$d['id']] = $d['nom'];
        }
        
        $employes = $employeModel->where('actif', 1)->findAll();
        $types = $typeCongeModel->findAll();
        
        $soldes_employes = [];
        foreach ($employes as $employe) {
            $soldes = $soldeModel->getSoldesByEmploye($employe['id']);
            $soldes_employes[] = [
                'employe' => $employe,
                'soldes' => $soldes
            ];
        }
        
        return view('rh/soldes', [
            'soldes_employes' => $soldes_employes,
            'types' => $types,
            'departements' => $departementsById,
            'user' => $user
        ]);
    }
}