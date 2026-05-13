<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Mes demandes</h1>

    <!-- Filtre par statut -->
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="statut" class="form-select">
                    <option value="">-- Tous les statuts --</option>
                    <option value="en_attente">En attente</option>
                    <option value="approuvee">Approuvée</option>
                    <option value="refusee">Refusée</option>
                    <option value="annulee">Annulée</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark">Filtrer</button>
            </div>
        </div>
    </form>

    <!-- Tableau des demandes -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Type de congé</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Durée</th>
                <th>Statut</th>
                <th>Commentaire RH</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $demande): ?>
                <tr>
                    <td>
                        <span class="badge bg-<?= $demande['type'] === 'annuel' ? 'primary' : ($demande['type'] === 'maladie' ? 'warning' : ($demande['type'] === 'special' ? 'success' : 'secondary')) ?>">
                            <?= ucfirst($demande['type']) ?>
                        </span>
                    </td>
                    <td><?= $demande['date_debut'] ?></td>
                    <td><?= $demande['date_fin'] ?></td>
                    <td><?= $demande['duree'] ?> jour(s)</td>
                    <td>
                        <span class="badge bg-<?= $demande['statut'] === 'en_attente' ? 'warning' : ($demande['statut'] === 'approuvee' ? 'success' : ($demande['statut'] === 'refusee' ? 'danger' : 'secondary')) ?>">
                            <?= ucfirst(str_replace('_', ' ', $demande['statut'])) ?>
                        </span>
                    </td>
                    <td><?= $demande['commentaire'] ?: 'N/A' ?></td>
                    <td>
                        <?php if ($demande['statut'] === 'en_attente'): ?>
                            <a href="/employee/demande/annuler/<?= $demande['id'] ?>" class="btn btn-danger btn-sm">Annuler</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>