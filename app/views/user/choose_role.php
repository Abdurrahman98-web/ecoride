<?php
require BASE_PATH . '/app/views/layouts/header.php';

// $userRoles is an array of roles for current user (from controller)

$roleModel = new Role();
$allRoles = $roleModel->getAllRoles();

// build list of role ids already selected
$selected = [];
foreach ($userRoles as $r) {
    $selected[] = (int)$r['role_id'];
}
?>

<h2>Choix du rôle</h2>

<p>Depuis votre espace, vous pouvez choisir d'être <strong>chauffeur</strong>, <strong>passager</strong>, ou les deux.</p>

<form method="POST" action="<?= BASE_URL ?>/user/update-role">
    <?php foreach ($allRoles as $role): ?>
        <?php // only show chauffeur / passager roles to users (ignore admin/employee)
            $label = strtolower($role['libelle']);
            if (!in_array($label, ['chauffeur','passager','chauffeur_passager'])) continue;
        ?>
        <div>
            <label>
                <input type="checkbox" name="roles[]" value="<?= (int)$role['role_id'] ?>" <?= in_array((int)$role['role_id'], $selected) ? 'checked' : '' ?> >
                <?= htmlspecialchars(ucfirst($label)) ?>
            </label>
        </div>
    <?php endforeach; ?>

    <div style="margin-top:12px;">
        <button type="submit">Enregistrer</button>
    </div>
</form>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
