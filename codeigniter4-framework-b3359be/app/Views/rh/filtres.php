<?= $this->extend('layouts/rh') ?>

<?= $this->section('content') ?>
<h4>Filtrer les demandes</h4>

<form method="get" action="/rh/filtres" class="row mb-4 g-3">
    <div class="col-md-3">
        <label class="form-label">Statut</label>
        <select name="statut" class="form-select">
            <option value="tous" <?= $filtres['statut'] === 'tous' ? 'selected' : '' ?>>Tous</option>
            <option value="en_attente" <?= $filtres['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
            <option value="approuve" <?= $filtres['statut'] === 'approuve' ? 'selected' : '' ?>>Approuvés</option>
            <option value="refuse" <?= $filtres['statut'] === 'refuse' ? 'selected' : '' ?>>Refusés</option>
            <option value="annule" <?= $filtres['statut'] === 'annule' ? 'selected' : '' ?>>Annulés</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Type de congé</label>
        <select name="type" class="form-select">
            <option value="">Tous</option>
            <?php foreach ($typesConge as $tc): ?>
                <option value="<?= $tc['id'] ?>" <?= $filtres['type'] == $tc['id'] ? 'selected' : '' ?>>
                    <?= esc($tc['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Recherche (nom/email)</label>
        <input type="text" name="search" class="form-control" value="<?= esc($filtres['search']) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Période</label>
        <div class="input-group">
            <input type="date" name="debut" class="form-control" value="<?= esc($filtres['debut']) ?>">
            <span class="input-group-text">→</span>
            <input type="date" name="fin" class="form-control" value="<?= esc($filtres['fin']) ?>">
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filtrer</button>
        <a href="/rh/filtres" class="btn btn-secondary">Réinitialiser</a>
    </div>
</form>

<h5 class="mt-4">Résultats (<?= count($demandes) ?>)</h5>
<table class="table table-bordered table-hover bg-white rounded">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Employé</th>
            <th>Type</th>
            <th>Période</th>
            <th>Jours</th>
            <th>Statut</th>
            <th>Date soumission</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($demandes)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Aucun résultat</td></tr>
        <?php endif; ?>
        <?php foreach ($demandes as $d): ?>
        <tr>
            <td><?= esc($d['id']) ?></td>
            <td><?= esc($d['prenom'] . ' ' . $d['nom']) ?></td>
            <td><?= esc($d['type_libelle'] ?? '-') ?></td>
            <td><?= esc($d['date_debut']) ?> → <?= esc($d['date_fin']) ?></td>
            <td><?= esc($d['nb_jours']) ?> j</td>
            <td>
                <?php $badgeClass = [
                    'en_attente' => 'bg-warning',
                    'approuve'   => 'bg-success',
                    'refuse'     => 'bg-danger',
                    'annule'     => 'bg-secondary',
                ][$d['statut']] ?? 'bg-light'; ?>
                <span class="badge <?= $badgeClass ?>"><?= esc($d['statut']) ?></span>
            </td>
            <td><small><?= esc($d['date_soumission'] ?? '-') ?></small></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>