<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container">
    <h3>Types de congés paramétrés</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Quota annuel</th>
                <th>Couleur</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($types as $t): ?>
            <tr>
                <td><?= $t['nom'] ?></td>
                <td><?= $t['total_jours_par_an'] ?> jours</td>
                <td>
                    <span class="badge" style="background-color: <?= $t['couleur'] ?>;">&nbsp;</span>
                </td>
                <td><?= $t['actif'] ? 'Actif' : 'Désactivé' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>