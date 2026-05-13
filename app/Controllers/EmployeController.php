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
        
        // Récupérer TOUTES les demandes
        $demandes = $congeModel->getDemandesByEmploye($user['id']);
        
        // Compter les demandes par statut
        $en_attente = 0;
        $approuvees = 0;
        $refusees = 0;
        
        foreach ($demandes as $d) {
            switch ($d['statut']) {
                case 'en_attente': $en_attente++; break;
                case 'approuvee': $approuvees++; break;
                case 'refusee': $refusees++; break;
            }
        }
        
        // Total des demandes
        $total_demandes = count($demandes);
        
        // Soldes
        $soldes = $soldeModel->getSoldesByEmploye($user['id']);
        $total_restant = array_sum(array_column($soldes, 'restant_jours'));
        
        // Dernières demandes (3 plus récentes)
        $dernieres = array_slice($demandes, 0, 3);
        
        return view('employe/dashboard', [
            'en_attente' => $en_attente,
            'approuvees' => $approuvees,
            'refusees' => $refusees,
            'total_restant' => $total_restant,
            'total_demandes' => $total_demandes,
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
        
        $type_conge_id = (int)$type_conge_id;
        
        if ($date_debut >= $date_fin) {
            return redirect()->back()->with('error', 'La date de fin doit être postérieure à la date de début.')->withInput();
        }
        
        // Calculer nombre de jours
        $debut = new \DateTime($date_debut);
        $fin = new \DateTime($date_fin);
        $nb_jours = $debut->diff($fin)->days + 1;
        
        // IMPORTANT: Utiliser l'année de la date de début
        $annee_demande = date('Y', strtotime($date_debut));
        
        // Récupérer le solde avec l'année de la demande
        $solde = $soldeModel->where('employe_id', $user['id'])
                            ->where('type_conge_id', $type_conge_id)
                            ->where('annee', $annee_demande)
                            ->first();
        
        if (!$solde) {
            return redirect()->back()->with('error', 'Aucun solde trouvé pour l\'année ' . $annee_demande . '. Contactez les RH.')->withInput();
        }
        
        if ($solde['restant_jours'] < $nb_jours) {
            return redirect()->back()->with('error', 'Solde insuffisant. Disponible: ' . $solde['restant_jours'] . ' jours, Demandé: ' . $nb_jours . ' jours.')->withInput();
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
            'statut' => 'en_attente',
            'created_at' => date('Y-m-d H:i:s')
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
        $congeModel = new CongeModel();
        
        $employe = $employeModel->find($user['id']);
        
        // Récupérer le nombre total de demandes
        $demandes = $congeModel->getDemandesByEmploye($user['id']);
        $total_demandes = count($demandes);
        
        return view('employe/profil', [
            'employe' => $employe,
            'total_demandes' => $total_demandes,
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

    public function modifierNom()
    {
        $user = session()->get('user');
        $employeModel = new \App\Models\EmployeModel();
        
        $nouveau_nom = trim($this->request->getPost('nom'));
        
        if (empty($nouveau_nom)) {
            return redirect()->back()->with('error', 'Le nom ne peut pas être vide.');
        }
        
        if (strlen($nouveau_nom) < 3) {
            return redirect()->back()->with('error', 'Le nom doit contenir au moins 3 caractères.');
        }
        
        // Mettre à jour le nom
        $employeModel->update($user['id'], [
            'nom' => $nouveau_nom
        ]);
        
        // Mettre à jour la session
        session()->set('user', array_merge($user, ['nom' => $nouveau_nom]));
        
        return redirect()->back()->with('success', 'Nom modifié avec succès. Nouveau nom : ' . $nouveau_nom);
    }
}