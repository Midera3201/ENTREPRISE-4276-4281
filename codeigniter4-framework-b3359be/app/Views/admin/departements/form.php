<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h4><?= esc($title) ?></h4>

<?php if (! empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $field => $msg): ?>
                <li><strong><?= esc($field) ?>:</strong> <?= esc($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= ! empty($departement['id']) ? '/admin/departements/' . $departement['id'] . '/update' : '/admin/departements/store' ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= esc($departement['id'] ?? '') ?>">

    <div class="mb-3">
        <label class="form-label">Nom *</label>
        <input type="text" name="nom" class="form-control" value="<?= esc(oldInput('nom', $departement['nom'] ?? '')) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"><?= esc(oldInput('description', $departement['description'] ?? '')) ?></textarea>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/admin/departements" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?= $this->endSection() ?>