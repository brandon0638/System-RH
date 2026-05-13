<?php
// install.php - Exécuter une seule fois pour initialiser la base

echo "=== INSTALLATION DE LA BASE DE DONNÉES ===\n\n";

// 1. Supprimer l'ancienne base
$db_file = 'writable/fitspace.db';
if (file_exists($db_file)) {
    unlink($db_file);
    echo "✅ Ancienne base supprimée\n";
}

// 2. Exécuter les migrations
echo "📦 Exécution des migrations...\n";
exec('php spark migrate');
echo "✅ Migrations terminées\n";

// 3. Exécuter le seeder
echo "🌱 Exécution du seeder...\n";
exec('php spark db:seed FitspaceSeeder');
echo "✅ Seed terminé\n";

// 4. Ajouter les soldes 2026
echo "📅 Ajout des soldes 2026...\n";
$db = new SQLite3($db_file);

// Vérifier si les soldes 2026 existent déjà
$check = $db->querySingle("SELECT COUNT(*) FROM soldes WHERE annee = 2026");

if ($check == 0) {
    $db->exec("
        INSERT INTO soldes (employe_id, type_conge_id, annee, total_jours, pris_jours, restant_jours, created_at, updated_at)
        SELECT e.id, t.id, 2026, t.total_jours_par_an, 0, t.total_jours_par_an, datetime('now'), datetime('now')
        FROM employes e, types_conge t
        WHERE e.actif = 1
    ");
    echo "✅ Soldes 2026 ajoutés\n";
} else {
    echo "⚠️ Soldes 2026 existent déjà\n";
}

$db->close();

echo "\n========================================\n";
echo "✅ INSTALLATION TERMINÉE !\n";
echo "========================================\n";
echo "Comptes de test :\n";
echo "- admin@techmada.mg / admin123\n";
echo "- rh@techmada.mg / rh123\n";
echo "- employe@techmada.mg / emp123\n";
echo "========================================\n";