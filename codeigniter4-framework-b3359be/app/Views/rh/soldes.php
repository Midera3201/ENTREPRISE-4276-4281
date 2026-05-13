<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Soldes des employés</h1>

    <!-- Filtre par département -->
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="departement" class="form-select">
                    <option value="">-- Tous les départements --</option>
                    <option value="informatique">Informatique</option>
                    <option value="rh">RH</option>
                    <option value="marketing">Marketing</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark">Filtrer</button>
            </div>
        </div>
    </form>

    <!-- Tableau des soldes -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Employé</th>
                <th>Congé annuel</th>
                <th>Maladie</th>
                <th>Congé spécial</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employes as $employe): ?>
                <tr>
                    <td>
                        <strong><?= $employe['prenom'] . ' ' . $employe['nom'] ?></strong><br>
                        <small><?= $employe['departement'] ?></small>
                    </td>
                    <td>
                        <?= $employe['soldes']['annuel']['restant'] ?> / <?= $employe['soldes']['annuel']['attribue'] ?>
                        <div class="progress">
                            <?php $percent = ($employe['soldes']['annuel']['restant'] / $employe['soldes']['annuel']['attribue']) * 100; ?>
                            <div class="progress-bar bg-<?= $percent > 60 ? 'success' : ($percent > 20 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $percent ?>%;">
                                <?= round($percent) ?>%
                            </div>
                        </div>
                    </td>
                    <td>
                        <?= $employe['soldes']['maladie']['restant'] ?> / <?= $employe['soldes']['maladie']['attribue'] ?>
                        <div class="progress">
                            <?php $percent = ($employe['soldes']['maladie']['restant'] / $employe['soldes']['maladie']['attribue']) * 100; ?>
                            <div class="progress-bar bg-<?= $percent > 60 ? 'success' : ($percent > 20 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $percent ?>%;">
                                <?= round($percent) ?>%
                            </div>
                        </div>
                    </td>
                    <td>
                        <?= $employe['soldes']['special']['restant'] ?> / <?= $employe['soldes']['special']['attribue'] ?>
                        <div class="progress">
                            <?php $percent = ($employe['soldes']['special']['restant'] / $employe['soldes']['special']['attribue']) * 100; ?>
                            <div class="progress-bar bg-<?= $percent > 60 ? 'success' : ($percent > 20 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $percent ?>%;">
                                <?= round($percent) ?>%
                            </div>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm">Détails</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>