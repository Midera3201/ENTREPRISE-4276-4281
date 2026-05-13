<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Types de congé</h4>
    <a href="/admin/types-conge/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter</a>
</div>

<table class="table table-bordered table-hover bg-white rounded">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Libellé</th>
            <th>Congés annuels</th>
            <th>Déductible</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($typesConge)): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">Aucun type de congé</td></tr>
        <?php endif; ?>
        <?php foreach ($typesConge as $tc): ?>
        <tr>
            <td><?= esc($tc['id']) ?></td>
            <td><?= esc($tc['libelle']) ?></td>
            <td><?= esc($tc['jours_annuels'] ?? '-') ?></td>
            <td><span class="badge bg-<?= $tc['deductible'] ? 'success' : 'secondary' ?>"><?= $tc['deductible'] ? 'Oui' : 'Non' ?></span></td>
            <td>
                <a href="/admin/types-conge/<?= $tc['id'] ?>/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                <a href="/admin/types-conge/<?= $tc['id'] ?>/confirm-delete" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>