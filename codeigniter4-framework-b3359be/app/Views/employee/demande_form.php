<?= $this->extend('layouts/employee') ?>

<?= $this->section('content') ?>
<h4>Nouvelle demande de congé</h4>

<?php if (! empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $field => $msg): ?>
                <li><strong><?= esc($field) ?>:</strong> <?= esc($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="/employee/demande/store">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label">Type de congé *</label>
        <select name="type_conge_id" class="form-select" required>
            <option value="">-- Sélectionner un type --</option>
            <?php foreach ($typesConge as $tc): ?>
                <option value="<?= $tc['id'] ?>" <?= oldInput('type_conge_id') == $tc['id'] ? 'selected' : '' ?>>
                    <?= esc($tc['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Date de début *</label>
            <input type="date" name="date_debut" class="form-control" value="<?= esc(oldInput('date_debut', '')) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Date de fin *</label>
            <input type="date" name="date_fin" class="form-control" value="<?= esc(oldInput('date_fin', '')) ?>" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Motif (optionnel)</label>
        <textarea name="motif" class="form-control" rows="3"><?= esc(oldInput('motif', '')) ?></textarea>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Soumettre la demande</button>
        <a href="/employee/dashboard" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?= $this->endSection() ?>