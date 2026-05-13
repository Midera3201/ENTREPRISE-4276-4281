<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Tableau de bord</h1>

    <div class="row">
        <!-- Cartes métriques -->
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">En attente</h5>
                    <p class="card-text display-4 text-center"><?= $demandesEnAttente ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Approuvées</h5>
                    <p class="card-text display-4 text-center"><?= $demandesApprouvees ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Jours restants</h5>
                    <p class="card-text display-4 text-center"><?= $soldeRestant ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Refusées</h5>
                    <p class="card-text display-4 text-center"><?= $demandesRefusees ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Barres de progression -->
    <h2 class="mt-5">Soldes</h2>
    <div class="mb-3">
        <label>Congé annuel</label>
        <div class="progress">
            <div class="progress-bar bg-<?= $soldes['annuel'] > 60 ? 'success' : ($soldes['annuel'] > 20 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $soldes['annuel'] ?>%;">
                <?= $soldes['annuel'] ?>%
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label>Congé maladie</label>
        <div class="progress">
            <div class="progress-bar bg-<?= $soldes['maladie'] > 60 ? 'success' : ($soldes['maladie'] > 20 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $soldes['maladie'] ?>%;">
                <?= $soldes['maladie'] ?>%
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label>Congé spécial</label>
        <div class="progress">
            <div class="progress-bar bg-<?= $soldes['special'] > 60 ? 'success' : ($soldes['special'] > 20 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= $soldes['special'] ?>%;">
                <?= $soldes['special'] ?>%
            </div>
        </div>
    </div>
</div>

<!-- Inclusion de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
    <h2>Statistiques Employé</h2>

    <!-- Graphique en courbe : Évolution des congés pris par mois -->
    <div class="mb-4">
        <canvas id="lineChart"></canvas>
    </div>

    <!-- Graphique en camembert : Répartition des demandes par statut -->
    <div class="mb-4">
        <canvas id="pieChart"></canvas>
    </div>
</div>

<script>
    // Graphique en courbe : Évolution des congés pris par mois
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Congés pris',
                data: [2, 3, 1, 4, 5, 2, 3, 4, 1, 2, 3, 4],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
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

    // Graphique en camembert : Répartition des demandes par statut
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Approuvées', 'En attente', 'Refusées'],
            datasets: [{
                data: [10, 5, 2],
                backgroundColor: ['#4caf50', '#ff9800', '#f44336'],
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