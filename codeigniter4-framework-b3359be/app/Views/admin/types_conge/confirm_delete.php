<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h4>Supprimer le type de congé #<?= esc($typeConge['id']) ?></h4>

<div class="alert alert-warning">
    Êtes-vous sûr de vouloir supprimer le type de congé <strong><?= esc($typeConge['libelle']) ?></strong> ?
    Cette action est irréversible.
</div>

<form method="post" action="/admin/types-conge/<?= $typeConge['id'] ?>/delete">
    <?= csrf_field() ?>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-danger">Supprimer</button>
        <a href="/admin/types-conge" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?= $this->endSection() ?>