<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechMada RH</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="bg-dark text-white py-3">
        <div class="container">
            <h1 class="text-center">TechMada RH</h1>
        </div>
    </header>

    <main class="container my-5">
        <div class="d-flex">
            <!-- Sidebar -->
            <nav class="bg-light border-end" style="width: 250px; min-height: 100vh;">
                <div class="p-3">
                    <h4 class="text-center">Menu</h4>
                    <ul class="nav flex-column">
                        <?php $role = session()->get('user_role'); ?>

                        <?php if ($role === 'employe'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/employee/dashboard"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/employee/demande/create"><i class="bi bi-plus-circle"></i> Nouvelle demande</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/employee/demandes"><i class="bi bi-list"></i> Mes demandes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/employee/profil"><i class="bi bi-person"></i> Mon profil</a>
                            </li>
                        <?php elseif ($role === 'rh'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/rh/dashboard"><i class="bi bi-speedometer2"></i> Tableau de bord</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/rh/demandes">
                                    <i class="bi bi-inbox"></i> Demandes à traiter
                                    <span class="badge bg-danger">5</span> <!-- Exemple de badge dynamique -->
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/rh/soldes"><i class="bi bi-wallet"></i> Liste des soldes</a>
                            </li>
                        <?php elseif ($role === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/dashboard"><i class="bi bi-bar-chart"></i> Vue d'ensemble</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/demandes"><i class="bi bi-list-check"></i> Toutes les demandes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/employes"><i class="bi bi-people"></i> Gestion employés</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/departements"><i class="bi bi-building"></i> Départements</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/types-conge"><i class="bi bi-card-list"></i> Types de congé</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="mt-auto p-3 border-top">
                    <p class="text-center mb-0">
                        <strong><?= session()->get('user_name') ?></strong><br>
                        <small><?= session()->get('user_email') ?></small><br>
                        <span class="badge bg-secondary text-capitalize">Rôle : <?= $role ?></span>
                    </p>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="flex-grow-1">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </main>

    <footer class="bg-light text-center py-3">
        <p>&copy; 2026 TechMada RH. Tous droits réservés.</p>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>