<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechMada RH — Gestion des congés CI4</title>
    
    <!-- CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- CSS Local -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    
    <!-- FullCalendar CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
</head>
<body>
    <?= $this->renderSection('content') ?>
    
    <!-- JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/fr.global.min.js"></script>
    
    <!-- Chart.js Local -->
    <script src="<?= base_url('assets/js/chart.umd.js') ?>"></script>
    
    <!-- JS Local -->
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
    <script src="<?= base_url('assets/js/calendar.js') ?>"></script>
    <script src="<?= base_url('assets/js/statistiques.js') ?>"></script>
    <script src="<?= base_url('assets/js/rh.js') ?>"></script>
</body>
</html>