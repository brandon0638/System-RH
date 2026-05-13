<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — TechMada RH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
    <?php $user = session()->get('user') ?>
    <div class="app-wrap">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-logo-icon"><i class="bi bi-shield-lock"></i></div>
                <div class="sidebar-brand-name">TechMada RH<span>Espace Admin</span></div>
            </div>

            <div class="sidebar-section">Menu</div>
            <ul class="sidebar-nav">
                <li><a href="<?= base_url('admin/dashboard') ?>" class="<?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Tableau de bord</a></li>
                <li><a href="<?= base_url('admin/employes') ?>" class="<?= strpos(uri_string(), 'admin/employes') === 0 ? 'active' : '' ?>"><i class="bi bi-people"></i> Employés</a></li>
                <li><a href="<?= base_url('admin/employes/nouveau') ?>"><i class="bi bi-person-plus"></i> Ajouter employé</a></li>
                <li><a href="<?= base_url('admin/types') ?>" class="<?= uri_string() === 'admin/types' ? 'active' : '' ?>"><i class="bi bi-list"></i> Types de congés</a></li>
            </ul>

            <div class="sidebar-user">
                <div class="s-user-row">
                    <div class="avatar av-green"><?= isset($user['nom']) ? strtoupper(substr($user['nom'],0,2)) : 'AD' ?></div>
                    <div>
                        <div class="user-name"><?= esc($user['nom'] ?? ($user['email'] ?? 'Admin')) ?></div>
                        <div class="user-role">Administrateur</div>
                    </div>
                    <a href="<?= base_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
                </div>
            </div>
        </aside>

        <div class="main">
            <div class="topbar">
                <div>
                    <div class="topbar-title">Espace administration</div>
                    <div class="topbar-breadcrumb">Accueil</div>
                </div>
            </div>

            <div class="content">
                <?= $this->renderSection('content') ?>
            </div>

            <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Gestion des congés CI4</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
    <script src="<?= base_url('assets/js/rh.js') ?>"></script>
</body>
</html>
