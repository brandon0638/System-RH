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
            <li><a href="<?= base_url('/rh/historique') ?>"><i class="bi bi-archive"></i> Historique</a></li>
            <li><a href="<?= base_url('/rh/soldes') ?>" class="active"><i class="bi bi-people"></i> Soldes employés</a></li>
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
                <div class="topbar-title">Soldes des employés</div>
                <div class="topbar-breadcrumb"><a href="<?= base_url('/rh/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right"></i> Soldes</div>
            </div>
        </div>

        <div class="content">
            <div class="data-card">
                <div class="data-card-head">
                    <h3>Soldes de congés 2025</h3>
                </div>
                <div class="table-responsive">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Département</th>
                                <?php foreach($types as $type): ?>
                                    <th><?= esc($type['nom']) ?></th>
                                <?php endforeach; ?>
                                <th>Total restant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($soldes_employes as $data): ?>
                                <?php 
                                $emp = $data['employe']; 
                                $soldes = $data['soldes']; 
                                $total_restant = 0;
                                foreach($soldes as $s) { $total_restant += $s['restant_jours']; }
                                ?>
                                <tr>
                                    <td>
                                        <div class="profile-row">
                                            <div class="avatar av-green">
                                                <?= strtoupper(substr($emp['nom'], 0, 2)) ?>
                                            </div>
                                            <div class="profile-info">
                                                <div class="pname"><?= esc($emp['nom']) ?></div>
                                                <div class="pdept"><?= esc($emp['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-muted"><?= esc($departements[$emp['departement_id']] ?? '-') ?></td>
                                    <?php foreach($types as $type): ?>
                                        <?php
                                        $restant = 0;
                                        $total = 0;
                                        foreach($soldes as $s) {
                                            if($s['type_conge_id'] == $type['id']) {
                                                $restant = $s['restant_jours'];
                                                $total = $s['total_jours'];
                                                break;
                                            }
                                        }
                                        $pourcentage = $total > 0 ? ($restant / $total) * 100 : 0;
                                        ?>
                                        <td>
                                            <div class="solde-cell">
                                                <div class="solde-numbers"><?= $restant ?> / <?= $total ?></div>
                                                <div class="solde-bar">
                                                    <div class="solde-fill <?= $pourcentage < 20 ? 'danger' : ($pourcentage < 50 ? 'warn' : '') ?>" style="width: <?= $pourcentage ?>%"></div>
                                                </div>
                                            </div>
                                        </td>
                                    <?php endforeach; ?>
                                    <td>
                                        <span class="total-restant <?= $total_restant < 5 ? 'danger-text' : 'success-text' ?>">
                                            <?= $total_restant ?> j
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>