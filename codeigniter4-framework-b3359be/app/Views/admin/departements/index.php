<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Départements</h4>
    <a href="/admin/departements/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter</a>
</div>

<table class="table table-bordered table-hover bg-white rounded">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Employés</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($departements)): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">Aucun département</td></tr>
        <?php endif; ?>
        <?php foreach ($departements as $dept): ?>
        <tr>
            <td><?= esc($dept['id']) ?></td>
            <td><?= esc($dept['nom']) ?></td>
            <td><?= esc($dept['description'] ?? '-') ?></td>
            <td><span class="badge bg-info"><?= esc($dept['nb_employes'] ?? 0) ?></span></td>
            <td>
                <a href="/admin/departements/<?= $dept['id'] ?>/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                <a href="/admin/departements/<?= $dept['id'] ?>/confirm-delete" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>