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
            <li><a href="<?= base_url('/employe/calendrier') ?>" class="active"><i class="bi bi-calendar-week"></i> Calendrier</a></li>
            <li><a href="<?= base_url('/employe/statistiques') ?>"><i class="bi bi-graph-up"></i> Statistiques</a></li>
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
                <div class="topbar-title">Calendrier des congés</div>
                <div class="topbar-breadcrumb">
                    <a href="<?= base_url('/employe/dashboard') ?>">Accueil</a>
                    <i class="bi bi-chevron-right"></i> Calendrier
                </div>
            </div>
        </div>

        <div class="content">
            <div class="data-card">
                <div class="data-card-head">
                    <h3><i class="bi bi-calendar3"></i> Mes congés approuvés</h3>
                </div>
                <div class="calendar-container">
                    <div id="calendar" data-events='<?= $evenements ?>'></div>
                </div>
            </div>
            
            <div class="legend">
                <div class="legend-title">Legende</div>
                <div class="legend-items">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #2d5a3d"></div>
                        <span>Congé annuel</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #1a4f7a"></div>
                        <span>Congé maladie</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #5a2d82"></div>
                        <span>Congé spécial</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>