<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Gestion des départements</h1>

    <!-- Formulaire d'ajout de département -->
    <div class="card mb-4">
        <div class="card-header">Ajouter un département</div>
        <div class="card-body">
            <form method="post" action="<?= base_url('admin/storeDepartement') ?>">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Tableau des départements -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departements as $departement): ?>
                <tr>
                    <td><?= $departement['nom'] ?></td>
                    <td><?= $departement['description'] ?></td>
                    <td>
                        <a href="<?= base_url('admin/editDepartement/' . $departement['id']) ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <a href="<?= base_url('admin/deleteDepartement/' . $departement['id']) ?>" class="btn btn-sm btn-danger">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>