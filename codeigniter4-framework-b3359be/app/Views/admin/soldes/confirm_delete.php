<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h4>Supprimer le solde #<?= esc($solde['id']) ?></h4>

<div class="alert alert-warning">
    Êtes-vous sûr de vouloir supprimer ce solde ?
    Cette action est irréversible.
</div>

<form method="post" action="/admin/soldes/<?= $solde['id'] ?>/delete">
    <?= csrf_field() ?>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">Supprimer</button>
        <a href="/admin/soldes" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?= $this->endSection() ?>