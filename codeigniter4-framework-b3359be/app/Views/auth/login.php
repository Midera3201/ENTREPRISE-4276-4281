<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: var(--beige);">
    <div class="card shadow" style="width: 100%; max-width: 400px;">
        <div class="card-header text-center bg-dark text-white">
            <h3>Connexion</h3>
        </div>
        <div class="card-body">
            <!-- Flashdata Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="/auth/login" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Entrez votre email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-dark">Se connecter</button>
                </div>
            </form>

            <!-- Demo Accounts -->
            <div class="mt-4">
                <h6>Comptes de démonstration :</h6>
                <ul>
                    <li><strong>Admin :</strong> admin@fitspace.mg / admin123</li>
                    <li><strong>RH :</strong> rh@fitspace.mg / rh123</li>
                    <li><strong>Employé :</strong> employe@fitspace.mg / employe123</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
