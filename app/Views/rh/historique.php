<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo-icon"><i class="bi bi-person-check"></i></div>
            <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
        </div>
        <ul class="sidebar-nav" style="margin-top:1rem">
            <li><a href="<?= base_url('/rh/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= base_url('/rh/demandes') ?>"><i class="bi bi-inbox"></i> Demandes à traiter</a></li>
            <li><a href="<?= base_url('/rh/historique') ?>" class="active"><i class="bi bi-archive"></i> Historique</a></li>
            <li><a href="<?= base_url('/rh/soldes') ?>"><i class="bi bi-people"></i> Soldes employés</a></li>
        </ul>
        <div class="sidebar-user">
            <div class="s-user-row">
                <div class="avatar av-blue"><?= strtoupper(substr($user['nom'], 0, 2)) ?></div>
                <div><div class="user-name"><?= esc($user['nom']) ?></div><div class="user-role">Responsable RH</div></div>
                <a href="<?= base_url('/logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Historique des demandes</div>
                <div class="topbar-breadcrumb"><a href="<?= base_url('/rh/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right"></i> Historique</div>
            </div>
        </div>

        <div class="content">
            <div class="data-card">
                <div class="data-card-head">
                    <h3>Toutes les demandes traitées</h3>
                    <div class="filter-group">
                        <select id="filter-statut" class="f-select filter-select">
                            <option value="">Tous les statuts</option>
                            <option value="approuvee">Approuvées</option>
                            <option value="refusee">Refusées</option>
                            <option value="annulee">Annulées</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="tbl" id="historique-table">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Type</th>
                                <th>Période</th>
                                <th>Durée</th>
                                <th>Statut</th>
                                <th>Commentaire RH</th>
                                <th>Traité le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($demandes as $demande): ?>
                            <tr data-statut="<?= $demande['statut'] ?>">
                                <td>
                                    <div class="profile-row">
                                        <div class="avatar av-green">
                                            <?= strtoupper(substr($demande['employe_nom'], 0, 2)) ?>
                                        </div>
                                        <div class="profile-info">
                                            <div class="pname"><?= esc($demande['employe_nom']) ?></div>
                                            <div class="pdept"><?= esc($demande['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="type-badge t-annuel"><?= esc($demande['type_nom']) ?></span></td>
                                <td class="td-muted"><?= date('d/m/Y', strtotime($demande['date_debut'])) ?> – <?= date('d/m/Y', strtotime($demande['date_fin'])) ?></td>
                                <td class="td-mono"><?= $demande['nb_jours'] ?> j</td>
                                <td>
                                    <?php if($demande['statut'] === 'approuvee'): ?>
                                        <span class="statut s-approuvee">approuvée</span>
                                    <?php elseif($demande['statut'] === 'refusee'): ?>
                                        <span class="statut s-refusee">refusée</span>
                                    <?php else: ?>
                                        <span class="statut s-annulee">annulée</span>
                                    <?php endif; ?>
                                </td>
                                <td class="td-muted"><?= esc($demande['commentaire_rh']) ?: '—' ?></td>
                                <td class="td-muted td-mono"><?= date('d/m/Y H:i', strtotime($demande['updated_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($demandes)): ?>
                            <tr>
                                <td colspan="7" class="empty">Aucune demande traitée</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>