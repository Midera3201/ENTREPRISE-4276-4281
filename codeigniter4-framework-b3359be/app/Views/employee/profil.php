<?= $this->extend('layouts/employee') ?>

<?= $this->section('content') ?>
<h4>Mon profil</h4>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4"><strong>Nom complet</strong></div>
            <div class="col-md-8"><?= esc($employe['prenom'] . ' ' . $employe['nom']) ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4"><strong>Email</strong></div>
            <div class="col-md-8"><?= esc($employe['email']) ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4"><strong>Rôle</strong></div>
            <div class="col-md-8">
                <span class="badge bg-info text-capitalize"><?= esc($employe['role']) ?></span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4"><strong>Date d'embauche</strong></div>
            <div class="col-md-8"><?= esc($employe['date_embauche'] ?? 'Non renseignée') ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4"><strong>Actif</strong></div>
            <div class="col-md-8">
                <?= $employe['actif'] ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4"><strong>Total demandes</strong></div>
            <div class="col-md-8"><?= $stats['total'] ?> dont <?= $stats['approuves'] ?> approuvées</div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>