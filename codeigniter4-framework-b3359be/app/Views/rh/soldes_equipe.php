<?= $this->extend('layouts/rh') ?>

<?= $this->section('content') ?>
<h4>Soldes de l'équipe</h4>

<form method="get" action="/rh/soldes-equipe" class="row mb-4 g-3">
    <div class="col-md-4">
        <label class="form-label">Département</label>
        <select name="departement_id" class="form-select">
            <option value="">Tous les départements</option>
            <?php foreach ($departements as $dept): ?>
                <option value="<?= $dept['id'] ?>" <?= $filtreDep == $dept['id'] ? 'selected' : '' ?>>
                    <?= esc($dept['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-8 d-flex align-items-end">
        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filtrer</button>
        <a href="/rh/soldes-equipe" class="btn btn-secondary">Réinitialiser</a>
    </div>
</form>

<?php foreach ($employes as $emp): ?>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong><?= esc($emp['prenom'] . ' ' . $emp['nom']) ?></strong>
        <span class="badge bg-<?= $emp['actif'] ? 'success' : 'secondary' ?> text-white">
            <?= $emp['actif'] ? 'Actif' : 'Inactif' ?>
        </span>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>Type de congé</th>
                    <th>Année</th>
                    <th>Attribués</th>
                    <th>Pris</th>
                    <th>Restant</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($emp['soldes'])): ?>
                    <tr><td colspan="5" class="text-center text-muted py-2">Aucun solde</td></tr>
                <?php endif; ?>
                <?php foreach ($emp['soldes'] as $s): ?>
                <tr class="<?= $s['solde_restant'] < 2 && $s['solde_restant'] >= 0 ? 'table-warning' : '' ?> <?= $s['solde_restant'] < 0 ? 'table-danger' : '' ?>">
                    <td><?= esc($s['type_libelle'] ?? '-') ?></td>
                    <td><?= esc($s['annee']) ?></td>
                    <td><?= esc($s['jours_attribues']) ?> j</td>
                    <td><?= esc($s['jours_pris']) ?> j</td>
                    <td>
                        <strong><?= esc($s['solde_restant']) ?> j</strong>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endforeach; ?>
<?= $this->endSection() ?>