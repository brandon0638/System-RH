<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo-icon"><i class="bi bi-person-check"></i></div>
            <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
        </div>
        <div class="sidebar-section">Menu</div>
        <ul class="sidebar-nav">
            <li><a href="<?= base_url('/rh/dashboard') ?>" class="active"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li>
                <a href="<?= base_url('/rh/demandes') ?>">
                    <i class="bi bi-inbox"></i> Demandes à traiter
                    <?php if ($en_attente > 0): ?>
                        <span class="nav-badge alert"><?= $en_attente ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="<?= base_url('/rh/historique') ?>"><i class="bi bi-archive"></i> Historique</a></li>
            <li><a href="<?= base_url('/rh/soldes') ?>"><i class="bi bi-people"></i> Soldes employés</a></li>
        </ul>
        <div class="sidebar-user">
            <div class="s-user-row">
                <div class="avatar av-blue"><?= strtoupper(substr($user['nom'], 0, 2)) ?></div>
                <div>
                    <div class="user-name"><?= esc($user['nom']) ?></div>
                    <div class="user-role">Responsable RH</div>
                </div>
                <a href="<?= base_url('/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Tableau de bord RH</div>
                <div class="topbar-breadcrumb">Accueil</div>
            </div>
        </div>

        <div class="content">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash flash-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash flash-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <!-- Métriques -->
            <div class="metrics">
                <div class="metric">
                    <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
                    <div class="metric-val"><?= $en_attente ?></div>
                    <div class="metric-label">Demandes en attente</div>
                </div>
                <div class="metric">
                    <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
                    <div class="metric-val"><?= $approuvees_mois ?></div>
                    <div class="metric-label">Approuvées ce mois</div>
                </div>
                <div class="metric">
                    <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div>
                    <div class="metric-val"><?= $employes_actifs ?></div>
                    <div class="metric-label">Employés actifs</div>
                </div>
                <div class="metric">
                    <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div></div>
                    <div class="metric-val"><?= $absents_aujourdhui ?></div>
                    <div class="metric-label">Absents aujourd'hui</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">
                <!-- Demandes récentes -->
                <div class="data-card" style="margin:0">
                    <div class="data-card-head">
                        <h3>Demandes récentes</h3>
                        <a href="<?= base_url('/rh/demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
                    </div>
                    <table class="tbl">
                        <thead>
                            <tr><th>Employé</th><th>Type</th><th>Du</th><th>Au</th><th>Statut</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($demandes_recentes as $demande): ?>
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:7px">
                                        <div class="avatar av-green" style="width:28px;height:28px;font-size:.62rem">
                                            <?= strtoupper(substr($demande['employe_nom'], 0, 2)) ?>
                                        </div>
                                        <span class="td-name" style="font-size:.84rem"><?= esc($demande['employe_nom']) ?></span>
                                    </div>
                                </td>
                                <td><span class="type-badge t-annuel"><?= esc($demande['type_nom']) ?></span></td>
                                <td class="td-muted"><?= date('d/m', strtotime($demande['date_debut'])) ?></td>
                                <td class="td-muted"><?= date('d/m', strtotime($demande['date_fin'])) ?></td>
                                <td>
                                    <?php if($demande['statut'] === 'en_attente'): ?>
                                        <span class="statut s-attente">en attente</span>
                                    <?php elseif($demande['statut'] === 'approuvee'): ?>
                                        <span class="statut s-approuvee">approuvée</span>
                                    <?php else: ?>
                                        <span class="statut s-refusee">refusée</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($demandes_recentes)): ?>
                            <tr><td colspan="5" class="empty">Aucune demande récente</td></tr>
                            <?php endif; ?>
                        </tbody>
                    ?>
                </div>

                <!-- Demandes par type -->
                <div class="data-card" style="margin:0">
                    <div class="data-card-head"><h3>Demandes par type</h3></div>
                    <div style="padding:1rem">
                        <?php foreach($demandes_par_type as $type => $count): ?>
                        <div style="margin-bottom:1rem">
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                                <span style="font-size:.8rem"><?= esc($type) ?></span>
                                <span class="td-mono"><?= $count ?></span>
                            </div>
                            <div class="solde-bar">
                                <div class="solde-fill" style="width: <?= min(100, $count * 10) ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Gestion des congés CI4</div>
    </div>
</div>
<?= $this->endSection() ?>