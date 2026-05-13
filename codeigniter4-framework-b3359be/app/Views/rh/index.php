<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Demandes à traiter</h1>

    <!-- Messages Flashdata -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Filtres -->
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
            <div class="col-md-4">
                <select name="statut" class="form-select">
                    <option value="">-- Tous les statuts --</option>
                    <option value="en_attente">En attente</option>
                    <option value="approuvee">Approuvée</option>
                    <option value="refusee">Refusée</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark">Filtrer</button>
            </div>
        </div>
    </form>

    <!-- Tableau des demandes -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Employé</th>
                <th>Type de congé</th>
                <th>Période</th>
                <th>Durée</th>
                <th>Solde disponible</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $demande): ?>
                <tr>
                    <td>
                        <img src="<?= $demande['employe']['avatar'] ?>" alt="Avatar" class="rounded-circle" width="40" height="40">
                        <strong><?= $demande['employe']['nom'] ?></strong><br>
                        <small><?= $demande['employe']['departement'] ?></small>
                    </td>
                    <td>
                        <span class="badge bg-<?= $demande['type'] === 'annuel' ? 'primary' : ($demande['type'] === 'maladie' ? 'warning' : 'success') ?>">
                            <?= ucfirst($demande['type']) ?>
                        </span>
                    </td>
                    <td><?= $demande['date_debut'] ?> → <?= $demande['date_fin'] ?></td>
                    <td><?= $demande['duree'] ?> jour(s)</td>
                    <td>
                        <?= $demande['solde'] ?> jour(s)
                        <?php if ($demande['solde'] < $demande['duree']): ?>
                            <span class="text-danger">(Insuffisant)</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/rh/demande/approuver/<?= $demande['id'] ?>" class="btn btn-success btn-sm">Approuver</a>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#refusModal<?= $demande['id'] ?>">Refuser</button>

                        <!-- Modal de refus -->
                        <div class="modal fade" id="refusModal<?= $demande['id'] ?>" tabindex="-1" aria-labelledby="refusModalLabel<?= $demande['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="refusModalLabel<?= $demande['id'] ?>">Refuser la demande</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/rh/demande/refuser/<?= $demande['id'] ?>" method="post">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="commentaire" class="form-label">Commentaire</label>
                                                <textarea name="commentaire" id="commentaire" class="form-control" rows="4" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Refuser</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>