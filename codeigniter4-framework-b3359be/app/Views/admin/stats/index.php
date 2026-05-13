<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card text-white bg-primary">
            <div class="card-body text-center">
                <h3><?= $stats['totalEmployes'] ?></h3>
                <small>Employés</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white bg-success">
            <div class="card-body text-center">
                <h3><?= $stats['totalDepartements'] ?></h3>
                <small>Départements</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white bg-info">
            <div class="card-body text-center">
                <h3><?= $stats['totalConges'] ?></h3>
                <small>Demandes de congé</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white bg-warning">
            <div class="card-body text-center">
                <h3><?= $stats['totalSoldes'] ?></h3>
                <small>Soldes gérés</small>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h5 class="text-primary"><?= $stats['enAttente'] ?></h5>
                <small class="text-muted">En attente</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h5 class="text-success"><?= $stats['approuves'] ?></h5>
                <small class="text-muted">Approuvés</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h5 class="text-danger"><?= $stats['refuses'] ?></h5>
                <small class="text-muted">Refusés</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body text-center">
                <h5 class="text-secondary"><?= $stats['annules'] ?></h5>
                <small class="text-muted">Annulés</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Congés récents -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header fw-bold">
                <i class="bi bi-clock-history"></i> 5 dernières demandes
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Employé</th>
                            <th>Type</th>
                            <th>Période</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stats['congesRecents'])): ?>
                            <tr><td colspan="4" class="text-center text-muted py-3">Aucune demande</td></tr>
                        <?php endif; ?>
                        <?php foreach ($stats['congesRecents'] as $c): ?>
                        <tr>
                            <td><?= esc($c['prenom'] . ' ' . $c['nom']) ?></td>
                            <td><?= esc($c['type_libelle'] ?? '-') ?></td>
                            <td><?= esc($c['date_debut']) ?> → <?= esc($c['date_fin']) ?></td>
                            <td>
                                <?php $badgeClass = [
                                    'en_attente' => 'bg-warning',
                                    'approuve'   => 'bg-success',
                                    'refuse'     => 'bg-danger',
                                    'annule'     => 'bg-secondary',
                                ][$c['statut']] ?? 'bg-light'; ?>
                                <span class="badge <?= $badgeClass ?>"><?= esc($c['statut']) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Employés par département -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header fw-bold">
                <i class="bi bi-building"></i> Employés par département
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Département</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stats['employesParDepartement'])): ?>
                            <tr><td colspan="2" class="text-center text-muted py-3">Aucun département</td></tr>
                        <?php endif; ?>
                        <?php foreach ($stats['employesParDepartement'] as $dept): ?>
                        <tr>
                            <td><?= esc($dept['departement_nom'] ?? 'Sans département') ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar" style="width: <?= min(($dept['nb_employes'] / max(1, $stats['totalEmployes'])) * 100, 100) ?>%"></div>
                                        </div>
                                    </div>
                                    <strong><?= $dept['nb_employes'] ?></strong>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Soldes faibles -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header fw-bold text-danger">
                <i class="bi bi-exclamation-triangle"></i> Soldes faibles (< 2 jours restants)
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Employé</th>
                            <th>Type de congé</th>
                            <th>Année</th>
                            <th>Attribués</th>
                            <th>Pris</th>
                            <th>Restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stats['soldesFaibles'])): ?>
                            <tr><td colspan="6" class="text-center text-muted py-3">Tous les soldes sont suffisants</td></tr>
                        <?php endif; ?>
                        <?php foreach ($stats['soldesFaibles'] as $sf): ?>
                        <?php $restant = $sf['jours_attribues'] - $sf['jours_pris']; ?>
                        <tr class="<?= $restant <= 0 ? 'table-danger' : '' ?>">
                            <td><?= esc($sf['prenom'] . ' ' . $sf['nom']) ?></td>
                            <td><?= esc($sf['type_libelle'] ?? '-') ?></td>
                            <td><?= esc($sf['annee']) ?></td>
                            <td><?= $sf['jours_attribues'] ?></td>
                            <td><?= $sf['jours_pris'] ?></td>
                            <td>
                                <span class="badge <?= $restant <= 0 ? 'bg-danger' : 'bg-warning' ?>">
                                    <?= $restant ?> jour(s)
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>