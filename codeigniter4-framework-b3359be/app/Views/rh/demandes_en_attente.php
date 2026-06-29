<?= $this->extend('layouts/rh') ?>

<?= $this->section('content') ?>
<h4>Demandes à traiter</h4>

<form method="get" action="/rh/demandes" class="row mb-4 g-3">
    <div class="col-md-3">
        <label class="form-label">Statut</label>
        <select name="statut" class="form-select">
            <option value="tous" <?= $filtres['statut'] === 'tous' ? 'selected' : '' ?>>Tous</option>
            <option value="en_attente" <?= $filtres['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
            <option value="approuve" <?= $filtres['statut'] === 'approuve' ? 'selected' : '' ?>>Approuvés</option>
            <option value="refuse" <?= $filtres['statut'] === 'refuse' ? 'selected' : '' ?>>Refusés</option>
            <option value="annule" <?= $filtres['statut'] === 'annule' ? 'selected' : '' ?>>Annulés</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Type de congé</label>
        <select name="type" class="form-select">
            <option value="">Tous</option>
            <?php foreach ($typesConge as $tc): ?>
                <option value="<?= $tc['id'] ?>" <?= $filtres['type'] == $tc['id'] ? 'selected' : '' ?>>
                    <?= esc($tc['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Recherche (nom/email)</label>
        <input type="text" name="search" class="form-control" value="<?= esc($filtres['search']) ?>">
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary me-2"><i class="bi bi-search"></i> Filtrer</button>
        <a href="/rh/demandes" class="btn btn-secondary">Réinitialiser</a>
    </div>
</form>

<table class="table table-bordered table-hover bg-white rounded">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Employé</th>
            <th>Type</th>
            <th>Période</th>
            <th>Jours</th>
            <th>Date soumission</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($demandes)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Aucune demande</td></tr>
        <?php endif; ?>
        <?php foreach ($demandes as $d): ?>
        <tr class="<?= $d['statut'] === 'en_attente' ? 'table-warning' : '' ?>">
            <td><?= esc($d['id']) ?></td>
            <td><?= esc($d['prenom'] . ' ' . $d['nom']) ?><br><small class="text-muted"><?= esc($d['email']) ?></small></td>
            <td><?= esc($d['type_libelle'] ?? '-') ?></td>
            <td><?= esc($d['date_debut']) ?> → <?= esc($d['date_fin']) ?></td>
            <td><?= esc($d['nb_jours']) ?> j</td>
            <td><small><?= esc($d['date_soumission'] ?? '-') ?></small></td>
            <td>
                <?php if ($d['statut'] === 'en_attente'): ?>
                    <form method="post" action="/rh/approuver/<?= $d['id'] ?>" class="d-inline" onsubmit="return confirm('Approuver cette demande ?')">
                        <?= csrf_field() ?>
                        <div class="mb-2">
                            <textarea name="commentaire_rh" class="form-control form-control-sm" rows="2" placeholder="Commentaire (optionnel)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success me-1"><i class="bi bi-check-lg"></i> Approuver</button>
                    </form>
                    <form method="post" action="/rh/refuser/<?= $d['id'] ?>" class="d-inline" onsubmit="return confirm('Refuser cette demande ?')">
                        <?= csrf_field() ?>
                        <textarea name="commentaire_rh" class="form-control form-control-sm mb-2" rows="2" placeholder="Commentaire (optionnel)"></textarea>
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i> Refuser</button>
                    </form>
                <?php else: ?>
                    <span class="badge bg-<?= $d['statut'] === 'approuve' ? 'success' : ($d['statut'] === 'refuse' ? 'danger' : 'secondary') ?>">
                        <?= esc($d['statut']) ?>
                    </span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>