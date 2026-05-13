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

<form method="post" action="<?= ! empty($typeConge['id']) ? '/admin/types-conge/' . $typeConge['id'] . '/update' : '/admin/types-conge/store' ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= esc($typeConge['id'] ?? '') ?>">

    <div class="mb-3">
        <label class="form-label">Libellé *</label>
        <input type="text" name="libelle" class="form-control" value="<?= esc(oldInput('libelle', $typeConge['libelle'] ?? '')) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Déductible ?</label>
        <select name="deductible" class="form-select">
            <option value="1" <?= (oldInput('deductible', $typeConge['deductible'] ?? 1) == 1) ? 'selected' : '' ?>>Oui</option>
            <option value="0" <?= (oldInput('deductible', $typeConge['deductible'] ?? 1) == 0) ? 'selected' : '' ?>>Non</option>
        </select>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/admin/types-conge" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?= $this->endSection() ?>