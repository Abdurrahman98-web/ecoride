<?php
require BASE_PATH . '/app/views/layouts/header.php';

/**
 * Formulaire d'ajout de véhicule (pour les chauffeurs)
 * Champs obligatoires : immatriculation, date première immatriculation,
 * marque, modèle, couleur, nombre de places, énergie
 */
?>

<h2>Ajouter un véhicule</h2>

<form method="POST" action="/ecoride/vehicule/store">

    <div>
        <label>Immatriculation *</label>
        <input style="border: 1px solid #cccccc;" type="text" name="immatriculation" required>
    </div>

    <div>
        <label>Date de première immatriculation *</label>
        <input style="border: 1px solid #cccccc;" type="date" name="date_mise_en_circulation" required>
    </div>

    <div>
        <label>Marque *</label>
        <input style="border: 1px solid #cccccc;" type="text" name="marque" required>
    </div>

    <div>
        <label>Modèle *</label>
        <input style="border: 1px solid #cccccc;" type="text" name="modele" required>
    </div>

    <div>
        <label>Couleur *</label>
        <input style="border: 1px solid #cccccc;" type="text" name="couleur" required>
    </div>

    <div>
        <label>Énergie (ex: essence, diesel, electrique) *</label>
        <input style="border: 1px solid #cccccc;" type="text" name="energie" required>
    </div>

    <div>
        <label>Nombre de places disponibles *</label>
        <input style="border: 1px solid #cccccc;" type="number" name="nb_places" min="1" required>
    </div>

    <div style="margin-top:12px;">
        <button type="submit">Enregistrer le véhicule</button>
    </div>

</form>

<p>Après avoir ajouté un véhicule, vous pouvez définir vos préférences (fumeur/animal, règles personnelles): <a href="/ecoride/preferences">Gérer mes préférences</a></p>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
