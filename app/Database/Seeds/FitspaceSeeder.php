<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FitspaceSeeder extends Seeder
{
    public function run()
    {
        // 1. Départements
        $this->db->table('departements')->insertBatch([
            ['nom' => 'IT', 'created_at' => date('Y-m-d H:i:s')],
            ['nom' => 'RH', 'created_at' => date('Y-m-d H:i:s')],
            ['nom' => 'Finance', 'created_at' => date('Y-m-d H:i:s')],
            ['nom' => 'Marketing', 'created_at' => date('Y-m-d H:i:s')],
        ]);

        // 2. Types de congé
        $this->db->table('types_conge')->insertBatch([
            ['nom' => 'Congé annuel', 'total_jours_par_an' => 30, 'couleur' => '#2d5a3d', 'actif' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['nom' => 'Congé maladie', 'total_jours_par_an' => 10, 'couleur' => '#1a4f7a', 'actif' => 1, 'created_at' => date('Y-m-d H:i:s')],
            ['nom' => 'Congé spécial', 'total_jours_par_an' => 5, 'couleur' => '#5a2d82', 'actif' => 1, 'created_at' => date('Y-m-d H:i:s')],
        ]);

        // 3. Employés - MOTS DE PASSE EN CLAIR (sans password_hash)
        $this->db->table('employes')->insertBatch([
            [
                'nom' => 'Administrateur',
                'email' => 'admin@techmada.mg',
                'password' => 'admin123',  // ← En clair
                'role' => 'admin',
                'departement_id' => 2,
                'date_embauche' => '2020-01-01',
                'actif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nom' => 'Marie Rabe',
                'email' => 'rh@techmada.mg',
                'password' => 'rh123',  // ← En clair
                'role' => 'rh',
                'departement_id' => 2,
                'date_embauche' => '2020-01-15',
                'actif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'nom' => 'Soa Rakoto',
                'email' => 'employe@techmada.mg',
                'password' => 'emp123',  // ← En clair
                'role' => 'employe',
                'departement_id' => 1,
                'date_embauche' => '2022-03-01',
                'actif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);

        // 4. Récupérer les IDs
        $employes = $this->db->table('employes')->get()->getResultArray();
        $types = $this->db->table('types_conge')->get()->getResultArray();
        $annee = 2025;

        // 5. Soldes initiaux
        foreach ($employes as $employe) {
            foreach ($types as $type) {
                $this->db->table('soldes')->insert([
                    'employe_id' => $employe['id'],
                    'type_conge_id' => $type['id'],
                    'annee' => $annee,
                    'total_jours' => $type['total_jours_par_an'],
                    'pris_jours' => 0,
                    'restant_jours' => $type['total_jours_par_an'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        echo "========================================\n";
        echo "SEED EXÉCUTÉ AVEC SUCCÈS !\n";
        echo "========================================\n";
        echo "Comptes créés (mots de passe en clair) :\n";
        echo "- admin@techmada.mg / admin123\n";
        echo "- rh@techmada.mg / rh123\n";
        echo "- employe@techmada.mg / emp123\n";
        echo "========================================\n";
    }
}