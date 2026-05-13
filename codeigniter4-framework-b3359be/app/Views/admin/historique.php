<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Historique des demandes</h1>

    <!-- Filtres -->
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control" value="<?= $filters['date'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label for="employe" class="form-label">Employé</label>
                <input type="text" name="employe" id="employe" class="form-control" placeholder="Nom ou prénom" value="<?= $filters['employe'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label for="statut" class="form-label">Statut</label>
                <select name="statut" id="statut" class="form-select">
                    <option value="">-- Tous --</option>
                    <option value="en attente" <?= isset($filters['statut']) && $filters['statut'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
                    <option value="approuve" <?= isset($filters['statut']) && $filters['statut'] === 'approuve' ? 'selected' : '' ?>>Approuvé</option>
                    <option value="refuse" <?= isset($filters['statut']) && $filters['statut'] === 'refuse' ? 'selected' : '' ?>>Refusé</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="departement" class="form-label">Département</label>
                <select name="departement" id="departement" class="form-select">
                    <option value="">-- Tous --</option>
                    <option value="informatique" <?= isset($filters['departement']) && $filters['departement'] === 'informatique' ? 'selected' : '' ?>>Informatique</option>
                    <option value="rh" <?= isset($filters['departement']) && $filters['departement'] === 'rh' ? 'selected' : '' ?>>RH</option>
                    <option value="marketing" <?= isset($filters['departement']) && $filters['departement'] === 'marketing' ? 'selected' : '' ?>>Marketing</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark">Filtrer</button>
            </div>
        </div>
    </form>

    <!-- Tableau des demandes -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Employé</th>
                <th>Type</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Statut</th>
                <th>Département</th>
                <th>Date de création</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $demande): ?>
                <tr>
                    <td><?= $demande['employe_nom'] . ' ' . $demande['employe_prenom'] ?></td>
                    <td><?= $demande['type'] ?></td>
                    <td><?= $demande['date_debut'] ?></td>
                    <td><?= $demande['date_fin'] ?></td>
                    <td>
                        <span class="badge bg-<?= $demande['statut'] === 'approuve' ? 'success' : ($demande['statut'] === 'refuse' ? 'danger' : 'warning') ?>">
                            <?= ucfirst($demande['statut']) ?>
                        </span>
                    </td>
                    <td><?= $demande['departement'] ?></td>
                    <td><?= $demande['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if (isset($pager)): ?>
        <div class="mt-4">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>