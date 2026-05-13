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
            <li><a href="<?= base_url('/employe/dashboard') ?>" class="active"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= base_url('/employe/create') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= base_url('/employe/mes-demandes') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
            <li><a href="<?= base_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user">
            <div class="s-user-row">
                <div class="avatar av-green"><?= strtoupper(substr($user['nom'], 0, 2)) ?></div>
                <div>
                    <div class="user-name"><?= esc($user['nom']) ?></div>
                    <div class="user-role">Employé</div>
                </div>
                <a href="<?= base_url('/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Tableau de bord</div>
                <div class="topbar-breadcrumb">Accueil</div>
            </div>
            <div class="topbar-actions">
                <a href="<?= base_url('/employe/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
                    <i class="bi bi-plus-lg"></i> Nouvelle demande
                </a>
            </div>
        </div>

        <div class="content">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash flash-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash flash-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="metrics">
                <div class="metric">
                    <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
                    <div class="metric-val"><?= $en_attente ?></div>
                    <div class="metric-label">En attente</div>
                </div>
                <div class="metric">
                    <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
                    <div class="metric-val"><?= $approuvees ?></div>
                    <div class="metric-label">Approuvées</div>
                </div>
                <div class="metric">
                    <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
                    <div class="metric-val"><?= $total_restant ?></div>
                    <div class="metric-label">Jours restants</div>
                </div>
                <div class="metric">
                    <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
                    <div class="metric-val"><?= $refusees ?></div>
                    <div class="metric-label">Refusée</div>
                </div>
            </div>

            <div class="data-card">
                <div class="data-card-head"><h3>Mes soldes de congés — 2025</h3></div>
                <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
                    <?php foreach($soldes as $solde): ?>
                    <div class="solde-card" style="margin:0">
                        <div class="solde-header">
                            <span class="solde-type"><?= esc($solde['type_nom']) ?></span>
                            <span class="solde-nums"><strong><?= $solde['restant_jours'] ?></strong> / <?= $solde['total_jours'] ?> j</span>
                        </div>
                        <div class="solde-bar"><div class="solde-fill" style="width: <?= ($solde['total_jours'] > 0 ? ($solde['restant_jours'] / $solde['total_jours']) * 100 : 0) ?>%"></div></div>
                        <div class="solde-label"><?= $solde['restant_jours'] ?> jours restants · <?= $solde['pris_jours'] ?> pris</div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="data-card">
                <div class="data-card-head">
                    <h3>Mes dernières demandes</h3>
                    <a href="<?= base_url('/employe/mes-demandes') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Voir tout →</a>
                </div>
                <table class="tbl">
                    <thead>
                        <tr><th>Type</th><th>Du</th><th>Au</th><th>Durée</th><th>Statut</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($dernieres as $demande): ?>
                        <tr>
                            <td><span class="type-badge t-annuel"><?= esc($demande['type_nom']) ?></span></td>
                            <td class="td-muted"><?= date('d M Y', strtotime($demande['date_debut'])) ?></td>
                            <td class="td-muted"><?= date('d M Y', strtotime($demande['date_fin'])) ?></td>
                            <td class="td-mono"><?= $demande['nb_jours'] ?> j</td>
                            <td>
                                <?php if($demande['statut'] === 'en_attente'): ?>
                                    <span class="statut s-attente">en attente</span>
                                <?php elseif($demande['statut'] === 'approuvee'): ?>
                                    <span class="statut s-approuvee">approuvée</span>
                                <?php elseif($demande['statut'] === 'refusee'): ?>
                                    <span class="statut s-refusee">refusée</span>
                                <?php else: ?>
                                    <span class="statut s-annulee">annulée</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($demande['statut'] === 'en_attente'): ?>
                                    <form action="<?= base_url('/employe/annuler/' . $demande['id']) ?>" method="post" style="display:inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-sm btn-cancel" onclick="return confirm('Annuler cette demande ?')"><i class="bi bi-x"></i> Annuler</button>
                                    </form>
                                <?php else: ?>
                                    <span class="td-muted" style="font-size:.75rem">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($dernieres)): ?>
                        <tr><td colspan="6" class="empty">Aucune demande trouvée</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Gestion des congés CI4</div>
    </div>
</div>
<?= $this->endSection() ?>