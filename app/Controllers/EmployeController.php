<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class EmployeController extends BaseController
{
    public function dashboard()
    {
        $user = session()->get('user');
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();
        
        // Statistiques
        $demandes = $congeModel->getDemandesByEmploye($user['id']);
        $en_attente = count(array_filter($demandes, fn($d) => $d['statut'] === 'en_attente'));
        $approuvees = count(array_filter($demandes, fn($d) => $d['statut'] === 'approuvee'));
        $refusees = count(array_filter($demandes, fn($d) => $d['statut'] === 'refusee'));
        
        // Soldes
        $soldes = $soldeModel->getSoldesByEmploye($user['id']);
        $total_restant = array_sum(array_column($soldes, 'restant_jours'));
        
        // Dernières demandes
        $dernieres = array_slice($demandes, 0, 3);
        
        return view('employe/dashboard', [
            'en_attente' => $en_attente,
            'approuvees' => $approuvees,
            'refusees' => $refusees,
            'total_restant' => $total_restant,
            'soldes' => $soldes,
            'dernieres' => $dernieres,
            'user' => $user
        ]);
    }
    
    public function create()
    {
        $user = session()->get('user');
        $typeCongeModel = new TypeCongeModel();
        $soldeModel = new SoldeModel();
        
        $types = $typeCongeModel->where('actif', 1)->findAll();
        
        // Récupérer les soldes pour chaque type
        foreach ($types as &$type) {
            $solde = $soldeModel->getSoldeByType($user['id'], $type['id']);
            $type['restant'] = $solde['restant_jours'] ?? 0;
        }
        
        $soldes = $soldeModel->getSoldesByEmploye($user['id']);
        
        return view('employe/create', [
            'types' => $types,
            'soldes' => $soldes,
            'user' => $user
        ]);
    }
    
    public function store()
    {
        $user = session()->get('user');
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();
        
        $type_conge_id = $this->request->getPost('type_conge_id');
        $date_debut = $this->request->getPost('date_debut');
        $date_fin = $this->request->getPost('date_fin');
        $motif = $this->request->getPost('motif');
        
        // Validation
        if (!$type_conge_id || !$date_debut || !$date_fin) {
            return redirect()->back()->with('error', 'Tous les champs obligatoires doivent être remplis.')->withInput();
        }
        
        // Vérifier que date_debut >= date_fin
        if ($date_debut >= $date_fin) {
            return redirect()->back()->with('error', 'La date de fin doit être postérieure à la date de début.')->withInput();
        }
        
        // Calculer nombre de jours
        $debut = new \DateTime($date_debut);
        $fin = new \DateTime($date_fin);
        $nb_jours = $debut->diff($fin)->days + 1;
        
        // Vérifier solde
        $solde = $soldeModel->getSoldeByType($user['id'], $type_conge_id);
        if (!$solde || $solde['restant_jours'] < $nb_jours) {
            return redirect()->back()->with('error', 'Solde insuffisant pour ce type de congé.')->withInput();
        }
        
        // Vérifier chevauchement
        if ($congeModel->verifierChevauchement($user['id'], $date_debut, $date_fin)) {
            return redirect()->back()->with('error', 'Vous avez déjà une demande de congé sur cette période.')->withInput();
        }
        
        // Créer la demande
        $data = [
            'employe_id' => $user['id'],
            'type_conge_id' => $type_conge_id,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'nb_jours' => $nb_jours,
            'motif' => $motif,
            'statut' => 'en_attente'
        ];
        
        if ($congeModel->insert($data)) {
            return redirect()->to('/employe/dashboard')->with('success', 'Votre demande de congé a été soumise avec succès.');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la création de la demande.')->withInput();
        }
    }
    
    public function index()
    {
        $user = session()->get('user');
        $congeModel = new CongeModel();
        $demandes = $congeModel->getDemandesByEmploye($user['id']);
        
        return view('employe/index', [
            'demandes' => $demandes,
            'user' => $user
        ]);
    }
    
    public function annuler($id)
    {
        $user = session()->get('user');
        $congeModel = new CongeModel();
        
        $demande = $congeModel->where('id', $id)->where('employe_id', $user['id'])->first();
        
        if (!$demande) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }
        
        if ($demande['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les demandes en attente peuvent être annulées.');
        }
        
        if ($congeModel->update($id, ['statut' => 'annulee'])) {
            return redirect()->back()->with('success', 'Demande annulée avec succès.');
        }
        
        return redirect()->back()->with('error', 'Erreur lors de l\'annulation.');
    }
    
    public function profil()
    {
        $user = session()->get('user');
        $employeModel = new \App\Models\EmployeModel();
        $employe = $employeModel->find($user['id']);
        
        return view('employe/profil', [
            'employe' => $employe,
            'user' => $user
        ]);
    }

   public function changerMotDePasse()
    {
        $user = session()->get('user');
        $employeModel = new \App\Models\EmployeModel();
        
        $password = $this->request->getPost('password');
        $password_confirm = $this->request->getPost('password_confirm');
        
        if (empty($password)) {
            return redirect()->back()->with('error', 'Le mot de passe ne peut pas être vide.');
        }
        
        if ($password !== $password_confirm) {
            return redirect()->back()->with('error', 'Les mots de passe ne correspondent pas.');
        }
        
        if (strlen($password) < 4) {
            return redirect()->back()->with('error', 'Le mot de passe doit contenir au moins 4 caractères.');
        }
        
        // STOCKER LE MOT DE PASSE EN CLAIR (sans hash)
        $employeModel->update($user['id'], [
            'password' => $password  // ← Directement en clair
        ]);
        
        return redirect()->back()->with('success', 'Mot de passe modifié avec succès. Nouveau mot de passe : ' . $password);
    }
}