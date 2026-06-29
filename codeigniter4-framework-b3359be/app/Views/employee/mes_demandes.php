<?= $this->extend('layouts/employee') ?>

<?= $this->section('content') ?>
<h4>Mes demandes de congé</h4>

<div class="mb-3">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link <?= $filtre === 'tous' ? 'active' : '' ?>" href="/employee/mes-demandes?filtre=tous">Tous</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filtre === 'en_attente' ? 'active' : '' ?>" href="/employee/mes-demandes?filtre=en_attente">En attente</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filtre === 'approuve' ? 'active' : '' ?>" href="/employee/mes-demandes?filtre=approuve">Approuvés</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filtre === 'refuse' ? 'active' : '' ?>" href="/employee/mes-demandes?filtre=refuse">Refusés</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filtre === 'annule' ? 'active' : '' ?>" href="/employee/mes-demandes?filtre=annule">Annulés</a>
        </li>
    </ul>
</div>

<table class="table table-bordered table-hover bg-white rounded">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Période</th>
            <th>Jours</th>
            <th>Statut</th>
            <th>Date soumission</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($mesConges)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Aucune demande</td></tr>
        <?php endif; ?>
        <?php foreach ($mesConges as $c): ?>
        <tr>
            <td><?= esc($c['id']) ?></td>
            <td><?= esc($c['type_libelle'] ?? '-') ?></td>
            <td><?= esc($c['date_debut']) ?> → <?= esc($c['date_fin']) ?></td>
            <td><?= esc($c['nb_jours']) ?> j</td>
            <td>
                <?php $badgeClass = [
                    'en_attente' => 'bg-warning',
                    'approuve'   => 'bg-success',
                    'refuse'     => 'bg-danger',
                    'annule'     => 'bg-secondary',
                ][$c['statut']] ?? 'bg-light'; ?>
                <span class="badge <?= $badgeClass ?>"><?= esc($c['statut']) ?></span>
            </td>
            <td><small><?= esc($c['date_soumission'] ?? '-') ?></small></td>
            <td>
                <?php if ($c['statut'] === 'en_attente'): ?>
                    <a href="/employee/demande/<?= $c['id'] ?>/annuler" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                        <i class="bi bi-x-circle"></i> Annuler
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>