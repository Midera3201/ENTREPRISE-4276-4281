<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Soldes</h4>
    <a href="/admin/soldes/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Initialiser un solde</a>
</div>

<table class="table table-bordered table-hover bg-white rounded">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Employé</th>
            <th>Type de congé</th>
            <th>Année</th>
            <th>Jours attribués</th>
            <th>Jours pris</th>
            <th>Solde restant</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($soldes)): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">Aucun solde</td></tr>
        <?php endif; ?>
        <?php foreach ($soldes as $s): ?>
        <tr>
            <td><?= esc($s['id']) ?></td>
            <td><?= esc($s['prenom'] . ' ' . $s['nom']) ?><br><small class="text-muted"><?= esc($s['email']) ?></small></td>
            <td><?= esc($s['type_conge_libelle'] ?? '-') ?></td>
            <td><?= esc($s['annee']) ?></td>
            <td><?= esc($s['jours_attribues']) ?></td>
            <td><?= esc($s['jours_pris']) ?></td>
            <td>
                <?php $restant = $s['jours_attribues'] - $s['jours_pris']; ?>
                <span class="badge <?= $restant < 2 ? 'bg-danger' : ($restant < 5 ? 'bg-warning' : 'bg-success') ?>">
                    <?= $restant ?> j.
                </span>
            </td>
            <td>
                <a href="/admin/soldes/<?= $s['id'] ?>/edit" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                <a href="/admin/soldes/<?= $s['id'] ?>/confirm-delete" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>