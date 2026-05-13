<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Employés</h4>
    <a href="/admin/employes/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter</a>
</div>

<table class="table table-bordered table-hover bg-white rounded">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Département</th>
            <th>Actif</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($employes)): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">Aucun employé</td></tr>
        <?php endif; ?>
        <?php foreach ($employes as $emp): ?>
        <tr>
            <td><?= esc($emp['id']) ?></td>
            <td><?= esc($emp['nom']) ?></td>
            <td><?= esc($emp['prenom']) ?></td>
            <td><?= esc($emp['email']) ?></td>
            <td><span class="badge badge-role bg-<?= $emp['role'] === 'admin' ? 'danger' : ($emp['role'] === 'rh' ? 'warning' : 'info') ?> text-white"><?= esc($emp['role']) ?></span></td>
            <td><?= esc($emp['departement_nom'] ?? '-') ?></td>
            <td><?= $emp['actif'] ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' ?></td>
            <td>
                <a href="/admin/employes/<?= $emp['id'] ?>/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                <a href="/admin/employes/<?= $emp['id'] ?>/confirm-delete" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>