<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="form-section">
        <h3>Nouveau collaborateur</h3>
        <form action="/admin/employes/enregistrer" method="post">
            <?= csrf_field() ?>
            <div class="form-grid-2">
                <div>
                    <label class="f-label">Nom</label>
                    <input class="f-input" type="text" name="nom" placeholder="Nom" required>
                </div>
                <div>
                    <label class="f-label">Email</label>
                    <input class="f-input" type="email" name="email" placeholder="Email" required>
                </div>
            </div>
            <div class="f-group">
                <label class="f-label">Département</label>
                <select class="f-select" name="departement_id">
                    <?php foreach($departements as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= esc($d['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="f-group">
                <label class="f-label">Rôle</label>
                <select class="f-select" name="role">
                    <option value="employe">Employé</option>
                    <option value="rh">RH</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-actions">
                <button class="btn-primary" type="submit">Créer l'employé</button>
                <a class="btn-secondary" href="/admin/employes">Annuler</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>