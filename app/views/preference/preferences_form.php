<?php
require BASE_PATH . '/app/views/layouts/header.php';

/**
 * Formulaire des préférences principales et personnalisées pour chauffeurs
 * - fumeur / non-fumeur
 * - animal / pas d'animal
 * - notes libres
 * - ajouter des préférences personnalisées
 */

?>

<h2>Mes préférences (chauffeur)</h2>

<form method="POST" action="/ecoride/preferences/save">
    <div>
        <label>
            <input type="checkbox" name="fumeur" <?= (!empty($preferences) && $preferences['fumeur']) ? 'checked' : '' ?>> Accepte les fumeurs
        </label>
    </div>

    <div>
        <label>
            <input type="checkbox" name="animal" <?= (!empty($preferences) && $preferences['animal']) ? 'checked' : '' ?>> Accepte les animaux
        </label>
    </div>

    <div>
        <label>Notes / Règles générales</label>
        <textarea name="notes"><?= htmlspecialchars($preferences['notes'] ?? '') ?></textarea>
    </div>

    <div style="margin-top:12px;">
        <button type="submit">Enregistrer</button>
    </div>
</form>

<hr>

<h3>Préférences personnalisées</h3>

<?php if (!empty($custom)): ?>
    <ul>
        <?php foreach ($custom as $c): ?>
            <li>
                <?= htmlspecialchars($c['texte']) ?>
                <a href="/ecoride/preferences/custom/delete?id=<?= (int)$c['preference_chuffeur_Id'] ?>">Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune préférence personnalisée.</p>
<?php endif; ?>

<form method="POST" action="/ecoride/preferences/custom/add" style="margin-top:12px;">
    <div>
        <label>Ajouter une règle personnelle</label>
        <input type="text" name="texte" required>
    </div>
    <div style="margin-top:8px;">
        <button type="submit">Ajouter</button>
    </div>
</form>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
