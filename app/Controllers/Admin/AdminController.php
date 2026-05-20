<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\EmployeModel;
use App\Models\DepartementModel;
use App\Models\TypeCongeModel;
use App\Models\SoldeModel;

class AdminController extends BaseController {

    public function dashboard()
{
    $db = \Config\Database::connect();

    // ==============================
    // STATISTIQUES
    // ==============================
    $data['total_emp'] = $db->table('employes')
        ->where('actif', 1)
        ->countAllResults();

    $data['en_attente'] = $db->table('conges')
        ->where('statut', 'en_attente')
        ->countAllResults();

    // ==============================
    // ABSENCES DU MOIS (CORRIGÉ)
    // ==============================
    // Récupère toutes les absences approuvées (toutes les dates)
    $data['absents'] = $db->table('conges d')
        ->select('d.*, e.nom as emp_nom, t.nom as type_nom')
        ->join('employes e', 'e.id = d.employe_id')
        ->join('types_conge t', 't.id = d.type_conge_id')
        ->where('d.statut', 'approuvee')
        ->orderBy('d.date_debut', 'DESC')
        ->get()
        ->getResultArray();

    // ==============================
    // CONGÉS PAR MOIS
    // ==============================
    $resultMois = $db->query("
        SELECT
            strftime('%m', date_debut) AS mois_num,
            COUNT(*) AS total
        FROM conges
        WHERE statut = 'approuvee'
        GROUP BY mois_num
        ORDER BY mois_num
    ")->getResultArray();

    $nomsMois = [
        '01' => 'Jan', '02' => 'Fév', '03' => 'Mar',
        '04' => 'Avr', '05' => 'Mai', '06' => 'Juin',
        '07' => 'Juil', '08' => 'Août', '09' => 'Sep',
        '10' => 'Oct', '11' => 'Nov', '12' => 'Déc'
    ];

    $data['congesParMois'] = [];

    foreach ($resultMois as $row) {
        $data['congesParMois'][] = [
            'mois_num' => $row['mois_num'],
            'mois'     => $nomsMois[$row['mois_num']] ?? $row['mois_num'],
            'total'    => (int) $row['total']
        ];
    }

    // ==============================
    // CONGÉS PAR JOUR
    // ==============================
    $resultJours = $db->query("
        SELECT
            CAST(strftime('%w', date_debut) AS INTEGER) AS jour_num,
            COUNT(*) AS total
        FROM conges
        WHERE statut = 'approuvee'
        GROUP BY jour_num
        ORDER BY jour_num
    ")->getResultArray();

    $nomsJours = [
        0 => 'Dimanche',
        1 => 'Lundi',
        2 => 'Mardi',
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi'
    ];

    $data['congesParJour'] = [];

    foreach ($resultJours as $row) {
        $data['congesParJour'][] = [
            'jour'  => $nomsJours[$row['jour_num']] ?? 'Inconnu',
            'total' => (int) $row['total']
        ];
    }

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