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

<form method="post" action="<?= $oldInput['id'] ?? 0 ? '/admin/employes/' . $oldInput['id'] . '/update' : '/admin/employes/store' ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= esc($oldInput['id'] ?? $employe['id'] ?? '') ?>">

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Nom *</label>
            <input type="text" name="nom" class="form-control" value="<?= esc(oldInput('nom', $employe['nom'] ?? '')) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Prénom *</label>
            <input type="text" name="prenom" class="form-control" value="<?= esc(oldInput('prenom', $employe['prenom'] ?? '')) ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="<?= esc(oldInput('email', $employe['email'] ?? '')) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Mot de passe <?= empty($employe['id']) ? '*': '(laisser vide pour ne pas changer)' ?></label>
            <input type="password" name="password_hash" class="form-control" <?= empty($employe['id']) ? 'required' : '' ?>>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Rôle *</label>
            <select name="role" class="form-select" required>
                <option value="employe" <?= (oldInput('role', $employe['role'] ?? '') === 'employe') ? 'selected' : '' ?>>Employé</option>
                <option value="rh" <?= (oldInput('role', $employe['role'] ?? '') === 'rh') ? 'selected' : '' ?>>RH</option>
                <option value="admin" <?= (oldInput('role', $employe['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Département</label>
            <select name="departement_id" class="form-select">
                <option value="">-- Aucun --</option>
                <?php foreach ($departements as $d): ?>
                    <option value="<?= $d['id'] ?>" <?= (oldInput('departement_id', $employe['departement_id'] ?? '') == $d['id']) ? 'selected' : '' ?>>
                        <?= esc($d['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Date d'embauche</label>
            <input type="date" name="date_embauche" class="form-control" value="<?= esc(oldInput('date_embauche', $employe['date_embauche'] ?? '')) ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Actif</label>
            <select name="actif" class="form-select">
                <option value="1" <?= (oldInput('actif', $employe['actif'] ?? 1) == 1) ? 'selected' : '' ?>>Oui</option>
                <option value="0" <?= (oldInput('actif', $employe['actif'] ?? 1) == 0) ? 'selected' : '' ?>>Non</option>
            </select>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/admin/employes" class="btn btn-secondary">Annuler</a>
    </div>
</form>
<?= $this->endSection() ?>