<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h4>Supprimer l'employé #<?= esc($employe['id']) ?></h4>

<div class="alert alert-warning">
    Êtes-vous sûr de vouloir supprimer <strong><?= esc($employe['prenom'] . ' ' . $employe['nom']) ?></strong> (<?= esc($employe['email']) ?>) ?
    Cette action est irréversible.
</div>

<form method="post" action="/admin/employes/<?= $employe['id'] ?>/delete">
    <?= csrf_field() ?>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">Supprimer</button>
        <a href="/admin/employes" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?= $this->endSection() ?>