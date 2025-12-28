<?php
// View: app/views/avis/create.php
require BASE_PATH . '/app/views/layouts/header.php';

/*
Expected variables from the controller:
- $rideId. ??? (int)  // ID of the ride being reviewed
- $chauffeur (array)  // chauffeur info from the ride
*/
?>

<h2 class="text-2xl font-bold mb-6 text-eco">
    Laisser un avis
</h2>

<div class="max-w-lg bg-white p-6 rounded shadow">

    <p class="mb-4 text-sm text-gray-700">
        Vous laissez un avis pour le covoiturage avec
        <strong><?= htmlspecialchars($chauffeur['pseudo'] ?? '') ?></strong>
    </p>

    <form method="POST" action="/avis/store" class="space-y-4">

        <input type="hidden" name="ride_id" value="<?= (int)$rideId ?>">

        <!-- RATING -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Note (1 à 5)
            </label>
            <select
                name="note"
                required
                class="w-full border border-gray-300 rounded px-3 py-2"
            >
                <option value="">-- Choisir --</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- COMMENT -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Commentaire
            </label>
            <textarea
                name="commentaire"
                rows="4"
                class="w-full border border-gray-300 rounded px-3 py-2"
                placeholder="Votre expérience..."
            ></textarea>
        </div>

        <!-- SUBMIT -->
        <div>
            <button
                type="submit"
                class="bg-eco text-white px-6 py-2 rounded hover:bg-green-700 transition"
            >
                Envoyer l’avis
            </button>
        </div>

    </form>

</div>

<?php
require BASE_PATH . '/app/views/layouts/footer.php';
 
//* devloped by A.A at 11:00
?>


