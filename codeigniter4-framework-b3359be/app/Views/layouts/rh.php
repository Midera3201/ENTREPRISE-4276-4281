<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Espace RH') ?> | TechMada RH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fc; }
        .rh-sidebar { background: #1e293b; min-height: 100vh; }
        .rh-sidebar .nav-link { color: #cbd5e1; padding: 10px 20px; border-radius: 6px; margin: 2px 0; }
        .rh-sidebar .nav-link:hover,
        .rh-sidebar .nav-link.active { background: #334155; color: #fff; }
        .rh-sidebar .nav-link i { width: 20px; text-align: center; margin-right: 10px; }
        .rh-header { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 16px 24px; }
        .rh-content { padding: 24px; }
        .stat-card { border: none; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <div class="d-flex">
        <nav class="rh-sidebar p-3" style="width: 260px; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100;">
            <h5 class="text-white text-uppercase fw-bold mb-4 px-3">Espace RH</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= strpos(uri_string(), 'rh/demandes') === 0 || uri_string() === 'rh' ? 'active' : '' ?>" href="/rh/demandes">
                        <i class="bi bi-inbox"></i> Demandes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() === 'rh/filtres' ? 'active' : '' ?>" href="/rh/filtres">
                        <i class="bi bi-funnel"></i> Filtres
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() === 'rh/soldes-equipe' ? 'active' : '' ?>" href="/rh/soldes-equipe">
                        <i class="bi bi-wallet"></i> Soldes équipe
                    </a>
                </li>
            </ul>
            <div class="mt-auto p-3 border-top">
                <a href="/logout" class="btn btn-outline-danger w-100">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </div>
        </nav>

        <div class="ms-260 flex-grow-1">
            <div class="rh-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><?= esc($title ?? 'Espace RH') ?></h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning text-dark text-capitalize"><?= session()->get('user_role') ?></span>
                        <span class="text-muted small"><?= session()->get('user_name') ?></span>
                    </div>
                </div>
            </div>

            <div class="rh-content">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>