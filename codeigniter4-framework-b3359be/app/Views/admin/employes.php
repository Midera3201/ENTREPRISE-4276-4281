<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Gestion des employés</h1>

    <!-- Formulaire d'ajout d'employé -->
    <div class="card mb-4">
        <div class="card-header">Ajouter un employé</div>
        <div class="card-body">
            <form method="post" action="<?= base_url('admin/storeEmploye') ?>">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="departement" class="form-label">Département</label>
                        <select class="form-select" id="departement" name="departement" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="informatique">Informatique</option>
                            <option value="rh">RH</option>
                            <option value="marketing">Marketing</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="date_embauche" class="form-label">Date d'embauche</label>
                        <input type="date" class="form-control" id="date_embauche" name="date_embauche" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <!-- Barre de recherche et filtre -->
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Rechercher un employé">
            </div>
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

    <!-- Tableau des employés -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Employé</th>
                <th>Département</th>
                <th>Rôle</th>
                <th>Date d'embauche</th>
                <th>Statut</th>
                <th>Solde annuel</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employes as $employe): ?>
                <tr>
                    <td>
                        <?= $employe['prenom'] . ' ' . $employe['nom'] ?><br>
                        <small><?= $employe['email'] ?></small>
                    </td>
                    <td><?= $employe['departement'] ?></td>
                    <td><?= ucfirst($employe['role']) ?></td>
                    <td><?= $employe['date_embauche'] ?></td>
                    <td>
                        <span class="badge bg-<?= $employe['statut'] === 'actif' ? 'success' : 'danger' ?>">
                            <?= ucfirst($employe['statut']) ?>
                        </span>
                    </td>
                    <td><?= $employe['solde_annuel'] ?> jours</td>
                    <td>
                        <a href="<?= base_url('admin/editEmploye/' . $employe['id']) ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <a href="<?= base_url('admin/toggleStatut/' . $employe['id']) ?>" class="btn btn-sm btn-<?= $employe['statut'] === 'actif' ? 'danger' : 'success' ?>">
                            <?= $employe['statut'] === 'actif' ? 'Désactiver' : 'Réactiver' ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>