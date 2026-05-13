<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Tableau de bord Administrateur</h1>

    <!-- Cartes métriques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Employés</h5>
                    <p class="card-text display-4"><?= $totalEmployes ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Demandes en attente</h5>
                    <p class="card-text display-4"><?= $demandesEnAttente ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Demandes approuvées ce mois</h5>
                    <p class="card-text display-4"><?= $demandesApprouveesMois ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Départements</h5>
                    <p class="card-text display-4"><?= $nbDepartements ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des demandes récentes -->
    <h2 class="mb-3">Demandes récentes</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Employé</th>
                <th>Type</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandesRecentes as $demande): ?>
                <tr>
                    <td><?= $demande['employe_nom'] ?></td>
                    <td><?= $demande['type'] ?></td>
                    <td><?= $demande['date_debut'] ?></td>
                    <td><?= $demande['date_fin'] ?></td>
                    <td><?= ucfirst($demande['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Liste des employés absents aujourd'hui -->
    <h2 class="mt-5">Employés absents aujourd'hui</h2>
    <ul class="list-group">
        <?php foreach ($employesAbsents as $employe): ?>
            <li class="list-group-item">
                <?= $employe['prenom'] . ' ' . $employe['nom'] ?> (<?= $employe['departement'] ?>)
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Inclusion de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
    <h2>Statistiques Administrateur</h2>

    <!-- Graphique en barres : Demandes par département -->
    <div class="mb-4">
        <canvas id="barChart"></canvas>
    </div>

    <!-- Graphique en courbe : Évolution des demandes sur l'année -->
    <div class="mb-4">
        <canvas id="lineChartAdmin"></canvas>
    </div>
</div>

<script>
    // Graphique en barres : Demandes par département
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Informatique', 'RH', 'Marketing'],
            datasets: [{
                label: 'Demandes',
                data: [15, 10, 8],
                backgroundColor: ['#2196f3', '#4caf50', '#ff9800'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                }
            }
        }
    });

    // Graphique en courbe : Évolution des demandes sur l'année
    const lineCtxAdmin = document.getElementById('lineChartAdmin').getContext('2d');
    new Chart(lineCtxAdmin, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Demandes',
                data: [5, 8, 6, 10, 12, 9, 7, 11, 8, 6, 10, 12],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>