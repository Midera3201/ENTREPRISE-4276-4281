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

<form method="post" action="<?= ! empty($solde['id']) ? '/admin/soldes/' . $solde['id'] . '/update' : '/admin/soldes/store' ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= esc($solde['id'] ?? '') ?>">

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Employé *</label>
            <select name="employe_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($employes as $emp): ?>
                    <option value="<?= $emp['id'] ?>" <?= (oldInput('employe_id', $solde['employe_id'] ?? '') == $emp['id']) ? 'selected' : '' ?>>
                        <?= esc($emp['prenom'] . ' ' . $emp['nom'] . ' (' . $emp['email'] . ')') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Type de congé *</label>
            <select name="type_conge_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($typesConge as $tc): ?>
                    <option value="<?= $tc['id'] ?>" <?= (oldInput('type_conge_id', $solde['type_conge_id'] ?? '') == $tc['id']) ? 'selected' : '' ?>>
                        <?= esc($tc['libelle']) ?> (<?= esc($tc['deductible'] ? 'Déductible' : 'Non déductible') ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Année *</label>
            <input type="number" name="annee" class="form-control" value="<?= esc(oldInput('annee', $solde['annee'] ?? date('Y'))) ?>" required min="2020" max="2030">
        </div>
        <div class="col-md-6">
            <label class="form-label">Jours attribués *</label>
            <input type="number" name="jours_attribues" class="form-control" value="<?= esc(oldInput('jours_attribues', $solde['jours_attribues'] ?? '')) ?>" step="0.5" min="0" required>
        </div>
    </div>

    <?php if (! empty($solde['id'])): ?>
    <div class="mb-3">
        <small class="text-muted">Jours déjà pris : <strong><?= esc($solde['jours_pris']) ?></strong> | Solde actuel : <strong><?= esc($solde['jours_attribues'] - $solde['jours_pris']) ?></strong> jours</small>
    </div>
    <?php endif; ?>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/admin/soldes" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?= $this->endSection() ?>