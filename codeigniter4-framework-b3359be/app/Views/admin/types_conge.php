<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Gestion des types de congé</h1>

    <!-- Formulaire d'ajout de type de congé -->
    <div class="card mb-4">
        <div class="card-header">Ajouter un type de congé</div>
        <div class="card-body">
            <form method="post" action="<?= base_url('admin/storeType') ?>">
                <div class="mb-3">
                    <label for="libelle" class="form-label">Libellé</label>
                    <input type="text" class="form-control" id="libelle" name="libelle" required>
                </div>
                <div class="mb-3">
                    <label for="jours_annuels" class="form-label">Jours annuels</label>
                    <input type="number" class="form-control" id="jours_annuels" name="jours_annuels" required>
                </div>
                <div class="mb-3">
                    <label for="deductible" class="form-label">Déductible</label>
                    <select class="form-select" id="deductible" name="deductible" required>
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Tableau des types de congé -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Libellé</th>
                <th>Jours annuels</th>
                <th>Déductible</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($types as $type): ?>
                <tr>
                    <td><?= $type['libelle'] ?></td>
                    <td><?= $type['jours_annuels'] ?></td>
                    <td><?= $type['deductible'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <a href="<?= base_url('admin/editType/' . $type['id']) ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <a href="<?= base_url('admin/deleteType/' . $type['id']) ?>" class="btn btn-sm btn-danger">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>