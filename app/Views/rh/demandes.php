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
            <li><a href="<?= base_url('/rh/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="<?= base_url('/rh/demandes') ?>" class="active"><i class="bi bi-inbox"></i> Demandes à traiter</a></li>
            <li><a href="<?= base_url('/rh/historique') ?>"><i class="bi bi-archive"></i> Historique</a></li>
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
                <div class="topbar-title">Demandes à traiter</div>
                <div class="topbar-breadcrumb"><a href="<?= base_url('/rh/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right"></i> Demandes</div>
            </div>
            <div class="topbar-actions">
                <span class="badge-attente">
                    <i class="bi bi-hourglass-split"></i> <?= count($demandes) ?> en attente
                </span>
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
                    <h3>Toutes les demandes en attente</h3>
                    <div class="filter-group">
                        <select id="filter-employe" class="f-select filter-select">
                            <option value="">Tous les employés</option>
                            <?php foreach($employes as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= esc($emp['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <table class="tbl" id="demandes-table">
                    <thead>
                        <tr>
                            <th>Employé</th>
                            <th>Type</th>
                            <th>Période</th>
                            <th>Durée</th>
                            <th>Solde dispo</th>
                            <th>Motif</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($demandes as $demande): ?>
                        <tr data-employe-id="<?= $demande['employe_id'] ?>">
                            <td>
                                <div class="profile-row">
                                    <div class="avatar av-green">
                                        <?= strtoupper(substr($demande['employe_nom'], 0, 2)) ?>
                                    </div>
                                    <div class="profile-info">
                                        <div class="pname"><?= esc($demande['employe_nom']) ?></div>
                                        <div class="pdept"><?= date('d/m', strtotime($demande['date_debut'])) ?> → <?= date('d/m', strtotime($demande['date_fin'])) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="type-badge t-annuel"><?= esc($demande['type_nom']) ?></span></td>
                            <td class="td-muted"><?= date('d/m/Y', strtotime($demande['date_debut'])) ?> – <?= date('d/m/Y', strtotime($demande['date_fin'])) ?></td>
                            <td class="td-mono"><?= $demande['nb_jours'] ?> j</td>
                            <td>
                                <?php if($demande['solde_restant'] >= $demande['nb_jours']): ?>
                                    <span class="solde-dispo"><?= $demande['solde_restant'] ?> j dispo</span>
                                <?php else: ?>
                                    <span class="solde-insuffisant"><?= $demande['solde_restant'] ?> j ⚠ insuffisant</span>
                                <?php endif; ?>
                            </td>
                            <td class="td-muted"><?= esc($demande['motif']) ?: '—' ?></td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn-sm btn-approve" data-id="<?= $demande['id'] ?>" data-employe="<?= esc($demande['employe_nom']) ?>" data-jours="<?= $demande['nb_jours'] ?>" onclick="showApproveModal(<?= $demande['id'] ?>, '<?= esc($demande['employe_nom']) ?>', <?= $demande['nb_jours'] ?>)">
                                        <i class="bi bi-check-lg"></i> Approuver
                                    </button>
                                    <button class="btn-sm btn-refuse" data-id="<?= $demande['id'] ?>" data-employe="<?= esc($demande['employe_nom']) ?>" onclick="showRefuseModal(<?= $demande['id'] ?>, '<?= esc($demande['employe_nom']) ?>')">
                                        <i class="bi bi-x-lg"></i> Refuser
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($demandes)): ?>
                        <tr>
                            <td colspan="7" class="empty">
                                <i class="bi bi-inbox"></i>
                                <p>Aucune demande en attente</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Approuver -->
<div id="approveModal" class="modal" style="display:none">
    <div class="modal-content">
        <h3>Approuver la demande</h3>
        <p>Voulez-vous approuver la demande de <strong id="approve-employe"></strong> ?</p>
        <p class="info-text">Le solde sera automatiquement déduit.</p>
        <form id="approveForm" method="post">
            <?= csrf_field() ?>
            <div class="form-actions">
                <button type="submit" class="btn-sm btn-approve">Confirmer</button>
                <button type="button" class="btn-sm btn-cancel" onclick="closeModals()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Refuser -->
<div id="refuseModal" class="modal" style="display:none">
    <div class="modal-content">
        <h3>Refuser la demande</h3>
        <p>Voulez-vous refuser la demande de <strong id="refuse-employe"></strong> ?</p>
        <form id="refuseForm" method="post">
            <?= csrf_field() ?>
            <div class="f-group">
                <label class="f-label">Commentaire (obligatoire)</label>
                <textarea name="commentaire" class="f-textarea" rows="3" placeholder="Expliquez la raison du refus..."></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-sm btn-refuse">Confirmer le refus</button>
                <button type="button" class="btn-sm btn-cancel" onclick="closeModals()">Annuler</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>