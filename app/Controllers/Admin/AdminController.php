<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\EmployeModel;
use App\Models\DepartementModel;
use App\Models\TypeCongeModel;
use App\Models\SoldeModel;

class AdminController extends BaseController {

    // --- 1. TABLEAU DE BORD ---
    public function dashboard() {
        $db = \Config\Database::connect();
        $data['total_emp'] = $db->table('employes')->where('actif', 1)->countAllResults();
        $data['en_attente'] = $db->table('conges')->where('statut', 'en_attente')->countAllResults();

        // Liste des absents du mois (SQLite)
        $data['absents'] = $db->table('conges d')
            ->select('d.*, e.nom as emp_nom, t.nom as type_nom')
            ->join('employes e', 'e.id = d.employe_id')
            ->join('types_conge t', 't.id = d.type_conge_id')
            ->where('d.statut', 'approuvee')
            ->where("strftime('%m', d.date_debut) =", date('m'))
            ->get()->getResultArray();

        return view('admin/dashboard', $data);
    }

    // --- 2. LISTE EMPLOYES ---
    public function listeEmployes() {
        $model = new EmployeModel();
        $data['employes'] = $model->select('employes.*, departements.nom as dept_nom')
                                  ->join('departements', 'departements.id = employes.departement_id', 'left')
                                  ->findAll();
        return view('admin/employes_liste', $data);
    }

    // --- 3. FORMULAIRE & ENREGISTREMENT ---
    public function formEmploye() {
        $data['departements'] = (new DepartementModel())->findAll();
        return view('admin/employe_form', $data);
    }

    public function saveEmploye() {
        $db = \Config\Database::connect();
        $db->transStart();

        // Insertion Employé
        $empId = (new EmployeModel())->insert([
            'nom' => $this->request->getPost('nom'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash('123456', PASSWORD_DEFAULT), // Pass par défaut
            'departement_id' => $this->request->getPost('departement_id'),
            'role' => $this->request->getPost('role'),
            'actif' => 1
        ]);

        // INITIALISATION AUTO DES SOLDES (To-Do List !)
        $types = (new TypeCongeModel())->findAll();
        foreach ($types as $t) {
            (new SoldeModel())->insert([
                'employe_id' => $empId,
                'type_conge_id' => $t['id'],
                'annee' => date('Y'),
                'total_jours' => $t['total_jours_par_an'],
                'pris_jours' => 0,
                'restant_jours' => $t['total_jours_par_an']
            ]);
        }

        $db->transComplete();
        return redirect()->to('/admin/employes')->with('message', 'Employé et soldes créés !');
    }

    public function listeTypes()
    {
        $model = new \App\Models\TypeCongeModel();
        $data['types'] = $model->findAll();
        return view('admin/types_liste', $data);
    }
}