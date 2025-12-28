<?php
// View: app/views/ride/create.php
require BASE_PATH . '/app/views/layouts/header.php';

/*

// Only users with chauffeur role can access this page
// Controller should already enforce this rule

Expected variables from controller:
- $vehicule (array)  // vehicles of logged-in chauffeur
*/
?>

<h2 class="text-2xl font-bold mb-6 text-eco">
    Proposer un covoiturage
</h2>

<?php if (empty($vehicule)): ?>

    <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
        Vous devez d’abord ajouter un véhicule pour proposer un covoiturage.
    </div>

    <a
        href="/vehicule/create"
        class="inline-block mt-4 text-eco font-semibold hover:underline"
    >
        Ajouter un véhicule
    </a>

<?php else: ?>

<div class="max-w-xl bg-white p-6 rounded shadow">

    <!-- ADD RIDE FORM -->
    <form method="POST" action="/rides/store" class="space-y-4">
        <!-- CSRF TOKEN (optional but good practice) -->
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf'] ?? '' ?>">

        <!-- DEPART -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Lieu de départ
            </label>
            <input
                type="text"
                name="lieu_depart"
                required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-eco"
            >
        </div>

        <!-- ARRIVEE -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Lieu d’arrivée
            </label>
            <input
                type="text"
                name="lieu_arrivee"
                required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-eco"
            >
        </div>

        <!-- DATE / TIME DEPART -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">
                    Date de départ
                </label>
                <input
                    type="date"
                    name="date_depart"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Heure de départ
                </label>
                <input
                    type="time"
                    name="heure_depart"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2"
                >
            </div>
        </div>

        <!-- DATE / TIME ARRIVEE -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">
                    Date d’arrivée
                </label>
                <input
                    type="date"
                    name="date_arrivee"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Heure d’arrivée
                </label>
                <input
                    type="time"
                    name="heure_arrivee"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2"
                >
            </div>
        </div>

        <!-- VEHICULE -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Véhicule
            </label>
            <select
                name="vehicule_id"
                required
                class="w-full border border-gray-300 rounded px-3 py-2"
            >
                <?php foreach ($vehicule as $v): ?>
                    <option value="<?= (int)$v['vehicule_id'] ?>">
                        <?= htmlspecialchars($v['marque']) ?>
                        <?= htmlspecialchars($v['modele']) ?>
                        (<?= htmlspecialchars($v['energie']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- PLACES -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Nombre de places disponibles
            </label>
            <input
                type="number"
                name="nb_places"
                min="1"
                required
                class="w-full border border-gray-300 rounded px-3 py-2"
            >
        </div>

        <!-- PRIX -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Prix par personne (en crédits)
            </label>
            <input
                type="number"
                name="prix_personne"
                min="1"
                required
                class="w-full border border-gray-300 rounded px-3 py-2"
            >
            <p class="text-xs text-gray-500 mt-1">
                2 crédits seront prélevés par la plateforme.
            </p>
        </div>

        <!-- SUBMIT -->
        <div>
            <button
                type="submit"
                class="bg-eco text-white px-6 py-2 rounded hover:bg-green-700 transition"
            >
                Publier le covoiturage
            </button>
        </div>

    </form>

</div>

<?php endif; ?>

<?php
require BASE_PATH . '/app/views/layouts/footer.php';
?>
// make connection to addride founction