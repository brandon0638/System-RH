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
            <li><a href="<?= base_url('/employe/profil') ?>" class="active"><i class="bi bi-person"></i> Mon profil</a></li>
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
                <div class="topbar-title">Mon profil</div>
                <div class="topbar-breadcrumb"><a href="<?= base_url('/employe/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Mon profil</div>
            </div>
        </div>

        <div class="content">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash flash-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash flash-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
                <!-- Informations personnelles -->
                <div class="form-section">
                    <h3><i class="bi bi-person-badge"></i> Informations personnelles</h3>
                    
                    <div class="f-group">
                        <label class="f-label">Nom complet</label>
                        <input type="text" class="f-input" value="<?= esc($employe['nom']) ?>" readonly disabled>
                    </div>
                    
                    <div class="f-group">
                        <label class="f-label">Email</label>
                        <input type="email" class="f-input" value="<?= esc($employe['email']) ?>" readonly disabled>
                    </div>
                    
                    <div class="f-group">
                        <label class="f-label">Rôle</label>
                        <input type="text" class="f-input" value="<?= ucfirst($employe['role']) ?>" readonly disabled>
                    </div>
                    
                    <div class="f-group">
                        <label class="f-label">Date d'embauche</label>
                        <input type="text" class="f-input" value="<?= date('d/m/Y', strtotime($employe['date_embauche'])) ?>" readonly disabled>
                    </div>
                </div>

                <!-- Statistiques -->
                <div>
                    <div class="form-section">
                        <h3><i class="bi bi-graph-up"></i> Statistiques</h3>
                        
                        <div style="margin-bottom:1rem">
                            <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                                <span class="solde-type">Ancienneté</span>
                                <span class="solde-nums">
                                    <?php
                                    $embauche = new DateTime($employe['date_embauche']);
                                    $aujourdhui = new DateTime();
                                    $diff = $embauche->diff($aujourdhui);
                                    echo $diff->y . ' année(s), ' . $diff->m . ' mois';
                                    ?>
                                </span>
                            </div>
                        </div>
                        
                        <div style="margin-bottom:1rem">
                            <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                                <span class="solde-type">Statut</span>
                                <span class="statut s-approuvee">Actif</span>
                            </div>
                        </div>
                        
                        <hr style="border-color:var(--border);margin:1rem 0">
                        
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                                <span class="solde-type">Total demandes</span>
                                <span class="solde-nums"><strong><?= isset($total_demandes) ? $total_demandes : 0 ?></strong> demandes</span>
                            </div>
                            <div class="solde-bar"><div class="solde-fill" style="width:100%"></div></div>
                        </div>
                    </div>

                    <!-- Changer mot de passe -->
                    <div class="form-section">
                        <h3><i class="bi bi-key"></i> Changer le mot de passe</h3>
                        
                        <form action="<?= base_url('/employe/changer-mot-de-passe') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="f-group">
                                <label class="f-label">Nouveau mot de passe</label>
                                <input type="password" name="password" class="f-input" placeholder="••••••••" required>
                                <div class="f-hint">Minimum 4 caractères</div>
                            </div>
                            <div class="f-group">
                                <label class="f-label">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirm" class="f-input" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="btn-forest"><i class="bi bi-save"></i> Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
    </div>
</div>
<?= $this->endSection() ?>