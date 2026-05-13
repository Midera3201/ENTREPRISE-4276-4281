<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; }
        .card { max-width: 420px; margin: 80px auto; padding: 24px; background: #fff; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .field { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; }
        input { width: 100%; padding: 10px 12px; border: 1px solid #d0d5dd; border-radius: 6px; }
        button { width: 100%; padding: 10px 12px; border: 0; border-radius: 6px; background: #0b5ed7; color: #fff; font-weight: 600; cursor: pointer; }
        .alert { padding: 10px 12px; border-radius: 6px; margin-bottom: 12px; }
        .alert-error { background: #ffe5e5; color: #b42318; }
        .alert-success { background: #e6f4ea; color: #1e7d34; }
        .hint { margin-top: 12px; color: #667085; font-size: 13px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Connexion</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('login') ?>">
            <?= csrf_field() ?>
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
            </div>
            <div class="field">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>

        <div class="hint">Comptes de test: admin@rh.local / rh@rh.local / employe@rh.local (password123)</div>
    </div>
</body>
</html>
