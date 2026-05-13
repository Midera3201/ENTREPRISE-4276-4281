<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Nouvelle demande de congé</h1>

    <div class="row">
        <!-- Formulaire de demande -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5>Formulaire</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('employee/store') ?>">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-control" value="<?= old('nom') ?>">
                        </div>
                        <div class="form-group">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" class="form-control" value="<?= old('prenom') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type de congé</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">-- Sélectionnez un type --</option>
                                <option value="annuel">Congé annuel (30 jours/an)</option>
                                <option value="maladie">Congé maladie (10 jours/an)</option>
                                <option value="special">Congé spécial (5 jours/an)</option>
                                <option value="sans_solde">Sans solde</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" name="date_fin" id="date_fin" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="jours" class="form-label">Nombre de jours</label>
                            <input type="text" id="jours" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="motif" class="form-label">Motif (optionnel)</label>
                            <textarea name="motif" id="motif" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Soumettre la demande</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panneau latéral -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5>Soldes actuels</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Congé annuel</label>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 75%;">75%</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Congé maladie</label>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 50%;">50%</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Congé spécial</label>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 15%;">15%</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5>Règles de congé</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Préavis de 48 heures requis.</li>
                        <li>Pas de chevauchement avec d'autres congés.</li>
                        <li>Les congés spéciaux nécessitent une validation RH.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('date_debut').addEventListener('change', calculateDays);
    document.getElementById('date_fin').addEventListener('change', calculateDays);

    function calculateDays() {
        const startDate = new Date(document.getElementById('date_debut').value);
        const endDate = new Date(document.getElementById('date_fin').value);

        if (!isNaN(startDate) && !isNaN(endDate) && endDate >= startDate) {
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            document.getElementById('jours').value = diffDays + ' jour(s)';
        } else {
            document.getElementById('jours').value = '';
        }
    }
</script>
<?= $this->endSection() ?>