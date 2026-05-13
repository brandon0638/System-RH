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
            <li><a href="<?= base_url('/employe/create') ?>" class="active"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="<?= base_url('/employe/mes-demandes') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
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
                <div class="topbar-title">Nouvelle demande de congé</div>
                <div class="topbar-breadcrumb">
                    <a href="<?= base_url('/employe/dashboard') ?>">Accueil</a>
                    <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande
                </div>
            </div>
        </div>

        <div class="content">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash flash-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash flash-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="form-layout" style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start">
                <div>
                    <div class="form-section">
                        <h3>Détails de la demande</h3>
                        
                        <form action="<?= base_url('/employe/store') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="f-group" style="margin-bottom:1rem">
                                <label class="f-label">Type de congé <span style="color:var(--danger)">*</span></label>
                                <select name="type_conge_id" class="f-select" required>
                                    <option value="">-- Choisir un type --</option>
                                    <?php foreach($types as $type): ?>
                                    <option value="<?= $type['id'] ?>" <?= old('type_conge_id') == $type['id'] ? 'selected' : '' ?>>
                                        <?= esc($type['nom']) ?> (<?= $type['restant'] ?? 0 ?> j restants)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-grid-2" style="margin-bottom:1rem">
                                <div class="f-group">
                                    <label class="f-label">Date de début <span style="color:var(--danger)">*</span></label>
                                    <input type="date" name="date_debut" class="f-input" value="<?= old('date_debut') ?>" required>
                                </div>
                                <div class="f-group">
                                    <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
                                    <input type="date" name="date_fin" class="f-input" value="<?= old('date_fin') ?>" required>
                                </div>
                            </div>

                            <div class="f-group" style="margin-bottom:1rem">
                                <label class="f-label">Motif (optionnel)</label>
                                <textarea name="motif" class="f-textarea" placeholder="Précisez le motif de votre demande si nécessaire..."><?= old('motif') ?></textarea>
                                <div class="f-hint">Le motif est visible par le responsable RH.</div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-forest"><i class="bi bi-send"></i> Soumettre la demande</button>
                                <a href="<?= base_url('/employe/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:1rem">
                    <div class="data-card" style="margin:0">
                        <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div>
                        <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
                            <?php foreach($soldes as $solde): ?>
                            <div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                    <span style="font-size:.8rem;color:var(--ink)"><?= esc($solde['type_nom']) ?></span>
                                    <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500"><?= $solde['restant_jours'] ?> j</span>
                                </div>
                                <div class="solde-bar"><div class="solde-fill" style="width: <?= ($solde['total_jours'] > 0 ? ($solde['restant_jours'] / $solde['total_jours']) * 100 : 0) ?>%"></div></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="flash flash-info" style="margin:0">
                        <i class="bi bi-info-circle-fill"></i>
                        <span style="font-size:.8rem">Le solde est déduit uniquement à l'approbation de votre responsable.</span>
                    </div>
                    <div style="background:var(--cream);border:1px solid var(--border);border-radius:8px;padding:.85rem 1rem">
                        <div style="font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.5rem"><i class="bi bi-clipboard-check" style="color:var(--forest);margin-right:5px"></i>Rappel des règles</div>
                        <ul style="margin:0;padding-left:1rem;font-size:.75rem;color:var(--muted);line-height:1.7">
                            <li>Préavis minimum : 48h avant la date de début</li>
                            <li>Pas de chevauchement avec une demande en cours</li>
                            <li>Solde insuffisant = demande refusée automatiquement</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>