<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h4>Supprimer le département #<?= esc($departement['id']) ?></h4>

<div class="alert alert-warning">
    Êtes-vous sûr de vouloir supprimer le département <strong><?= esc($departement['nom']) ?></strong> ?
    <?php if (($nbEmployes ?? 0) > 0): ?>
        <br><small class="text-danger">⚠️ <?= $nbEmployes ?> employé(s) sont actuellement rattachés à ce département. Ils seront dissociés.</small>
    <?php endif; ?>
    Cette action est irréversible.
</div>

<form method="post" action="/admin/departements/<?= $departement['id'] ?>/delete">
    <?= csrf_field() ?>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">Supprimer</button>
        <a href="/admin/departements" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?= $this->endSection() ?>