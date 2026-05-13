<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; }
        .wrap { max-width: 760px; margin: 40px auto; padding: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        a { color: #0b5ed7; text-decoration: none; }
        .links a { display: inline-block; margin-right: 12px; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h2>Bienvenue <?= esc((string) session()->get('user_name')) ?></h2>
            <p>Role: <?= esc((string) session()->get('user_role')) ?></p>
            <div class="links">
                <a href="<?= site_url('admin') ?>">Espace admin</a>
                <a href="<?= site_url('rh') ?>">Espace RH</a>
                <a href="<?= site_url('employe') ?>">Espace employe</a>
                <a href="<?= site_url('logout') ?>">Logout</a>
            </div>
            <?php if (session()->getFlashdata('error')): ?>
                <p style="color:#b42318; margin-top:12px;">
                    <?= esc(session()->getFlashdata('error')) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
