<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1 class="mb-4">Mon profil</h1>

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

    <div class="row">
        <!-- Formulaire modification informations personnelles -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5>Modifier mes informations</h5>
                </div>
                <div class="card-body">
                    <form action="/employee/profil/update" method="post">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" name="nom" id="nom" class="form-control" value="<?= $user['nom'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" name="prenom" id="prenom" class="form-control" value="<?= $user['prenom'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= $user['email'] ?>" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Formulaire modification mot de passe -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5>Modifier mon mot de passe</h5>
                </div>
                <div class="card-body">
                    <form action="/employee/profil/update-password" method="post">
                        <div class="mb-3">
                            <label for="ancien_mdp" class="form-label">Ancien mot de passe</label>
                            <input type="password" name="ancien_mdp" id="ancien_mdp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="nouveau_mdp" class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="nouveau_mdp" id="nouveau_mdp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmation_mdp" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" name="confirmation_mdp" id="confirmation_mdp" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>