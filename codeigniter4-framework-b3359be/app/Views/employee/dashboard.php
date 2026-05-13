<?= $this->extend('layouts/employee') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card text-white bg-primary">
            <div class="card-body text-center">
                <h3><?= $stats['total'] ?></h3>
                <small>Total demandes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white bg-warning">
            <div class="card-body text-center">
                <h3><?= $stats['en_attente'] ?></h3>
                <small>En attente</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white bg-success">
            <div class="card-body text-center">
                <h3><?= $stats['approuves'] ?></h3>
                <small>Approuvés</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white bg-danger">
            <div class="card-body text-center">
                <h3><?= $stats['refuses'] ?></h3>
                <small>Refusés</small>
            </div>
        </div>
    </div>
</div>

<h5 class="mb-3"><i class="bi bi-clock-history"></i> Dernières demandes</h5>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Période</th>
                    <th>Jours</th>
                    <th>Statut</th>
                    <th>Date soumission</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mesConges)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Aucune demande</td></tr>
                <?php endif; ?>
                <?php foreach ($mesConges as $c): ?>
                <tr>
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
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>