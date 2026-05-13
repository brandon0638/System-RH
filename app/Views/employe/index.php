<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
            <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
        </div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= base_url('/employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= base_url('/employe/create') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= base_url('/employe/mes-demandes') ?>" class="active"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
            <li><a href="<?= base_url('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <div class="sidebar-user">
            <div class="s-user-row">
                <div class="avatar av-green"><?= strtoupper(substr($user['nom'], 0, 2)) ?></div>
                <div><div class="user-name"><?= esc($user['nom']) ?></div><div class="user-role">Employé</div></div>
            </div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Mes demandes de congé</div>
                <div class="topbar-breadcrumb"><a href="<?= base_url('/employe/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mes demandes</div>
            </div>
            <div class="topbar-actions">
                <a href="<?= base_url('/employe/create') ?>" class="btn-forest" style="padding:7px 14px;font-size:.82rem"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
            </div>
        </div>

        <div class="content">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash flash-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash flash-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            
            <div class="data-card">
                <div class="data-card-head">
                    <h3>Toutes mes demandes</h3>
                </div>
                <table class="tbl">
                    <thead>
                        <tr><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($demandes as $demande): ?>
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
                            <td class="td-muted" style="font-size:.78rem"><?= esc($demande['commentaire_rh']) ?: '—' ?></td>
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
                        <?php if(empty($demandes)): ?>
                        <tr><td colspan="7" class="empty">Aucune demande trouvée</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>