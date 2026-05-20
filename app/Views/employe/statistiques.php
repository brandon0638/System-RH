<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
            <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
        </div>
        <div class="sidebar-section">Menu</div>
        <ul class="sidebar-nav">
            <li><a href="<?= base_url('/employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= base_url('/employe/create') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= base_url('/employe/mes-demandes') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
            <li><a href="<?= base_url('/employe/calendrier') ?>"><i class="bi bi-calendar-week"></i> Calendrier</a></li>
            <li><a href="<?= base_url('/employe/statistiques') ?>" class="active"><i class="bi bi-graph-up"></i> Statistiques</a></li>
            <li><a href="<?= base_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user">
            <div class="s-user-row">
                <div class="avatar av-green"><?= strtoupper(substr($user['nom'], 0, 2)) ?></div>
                <div>
                    <div class="user-name"><?= esc($user['nom']) ?></div>
                    <div class="user-role">Employé</div>
                </div>
                <a href="<?= base_url('/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Mes statistiques</div>
                <div class="topbar-breadcrumb">
                    <a href="<?= base_url('/employe/dashboard') ?>">Accueil</a>
                    <i class="bi bi-chevron-right"></i> Statistiques
                </div>
            </div>
        </div>

        <div class="content">
            <!-- Cartes récapitulatives -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-icon mi-blue"><i class="bi bi-calendar3"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_demandes ?></div>
                        <div class="stat-label">Total demandes</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon mi-green"><i class="bi bi-check-circle"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $approuvees ?></div>
                        <div class="stat-label">Approuvées</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon mi-amber"><i class="bi bi-hourglass-split"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $en_attente ?></div>
                        <div class="stat-label">En attente</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon mi-forest"><i class="bi bi-calendar-check"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $total_restant ?></div>
                        <div class="stat-label">Jours restants</div>
                    </div>
                </div>
            </div>

            <!-- Graphique: Demandes par type -->
            <div class="data-card">
                <div class="data-card-head">
                    <h3><i class="bi bi-pie-chart"></i> Demandes par type de congé</h3>
                </div>
                <div class="chart-container">
                    <canvas id="typeChart" 
                            data-labels='["Congé annuel", "Congé maladie", "Congé spécial"]' 
                            data-data='[<?= $demandes_par_type['Congé annuel'] ?? 0 ?>, <?= $demandes_par_type['Congé maladie'] ?? 0 ?>, <?= $demandes_par_type['Congé spécial'] ?? 0 ?>]'>
                    </canvas>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
                <!-- Graphique: Jours par mois -->
                <div class="data-card">
                    <div class="data-card-head">
                        <h3><i class="bi bi-calendar-month"></i> Jours de congé par mois</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="moisChart" 
                                data-labels='<?= $mois_labels ?>' 
                                data-data='<?= $mois_donnees ?>'>
                        </canvas>
                    </div>
                </div>

                <!-- Graphique: Jours par semaine -->
                <div class="data-card">
                    <div class="data-card-head">
                        <h3><i class="bi bi-calendar-day"></i> Jours de congé par jour de semaine</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="semaineChart" 
                                data-labels='<?= $jours_labels ?>' 
                                data-data='<?= $jours_donnees ?>'>
                        </canvas>
                    </div>
                </div>
            </div>

            <!-- Répartition des statuts -->
            <div class="data-card">
                <div class="data-card-head">
                    <h3><i class="bi bi-pie-chart"></i> Répartition des statuts</h3>
                </div>
                <div class="chart-container">
                    <canvas id="statutChart" 
                            data-labels='["Approuvées", "En attente", "Refusées", "Annulées"]' 
                            data-data='[<?= $approuvees ?>, <?= $en_attente ?>, <?= $refusees ?>, <?= $annulees ?>]'>
                    </canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>