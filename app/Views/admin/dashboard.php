<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="content">
    <h2>Tableau de bord</h2>

    <div class="metrics">
        <div class="metric">
            <div class="metric-top">
                <div>
                    <div class="metric-val"><?= $total_emp ?></div>
                    <div class="metric-label">Employés actifs</div>
                </div>
                <div class="metric-icon mi-forest"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
        <div class="metric">
            <div class="metric-top">
                <div>
                    <div class="metric-val"><?= $en_attente ?></div>
                    <div class="metric-label">Demandes en attente</div>
                </div>
                <div class="metric-icon mi-amber"><i class="bi bi-clock-fill"></i></div>
            </div>
        </div>
    </div>

    <div class="data-card">
        <div class="data-card-head">
            <h3>Absences de <?= date('F') ?></h3>
        </div>
        <div class="content">
            <?php if (empty($absents)): ?>
                <div class="empty"><i class="bi bi-calendar-x"></i><p>Aucune absence approuvée ce mois.</p></div>
            <?php else: ?>
                <ul class="list-unstyled">
                    <?php foreach($absents as $a): ?>
                        <li class="py-2">
                            <strong><?= esc($a['emp_nom']) ?></strong> <span class="td-muted">(<?= esc($a['type_nom']) ?>)</span>
                            <div class="td-muted">du <?= esc($a['date_debut']) ?> au <?= esc($a['date_fin']) ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>