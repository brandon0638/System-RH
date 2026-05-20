<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="data-card">
        <div class="data-card-head">
            <h3>Liste des employés</h3>
            <a class="btn-forest" href="/admin/employes/nouveau"><i class="bi bi-plus-lg"></i>&nbsp;Ajouter</a>
        </div>
        <div class="content">
            <table class="tbl">
                <thead>
                    <tr><th>Nom</th><th>Email</th><th>Département</th><th>Statut</th></tr>
                </thead>
                <tbody>
                <?php foreach($employes as $e): ?>
                <tr>
                    <td class="td-name"><?= esc($e['nom']) ?></td>
                    <td class="td-mono"><?= esc($e['email']) ?></td>
                    <td><?= esc($e['dept_nom']) ?></td>
                    <td><span class="statut <?= $e['actif'] ? 's-approuvee' : 's-annulee' ?>"><?= $e['actif'] ? 'Actif' : 'Inactif' ?></span></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>