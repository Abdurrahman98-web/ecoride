<?php
// View: app/views/ride/index.php
require BASE_PATH . '/app/views/layouts/header.php';

/*
Expected variables from controller:
- $rides (array)
- optional: $nextDate (string | null) ($suggestedDate in controller).
*/
?>

<h2 class="text-2xl font-bold mb-6 text-eco">
    Résultats de covoiturage
</h2>

<?php if (empty($rides)): ?>

    <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
        Aucun covoiturage disponible pour votre recherche.
    </div>

    <?php if (!empty($suggestedDate)): ?>
        <p class="mt-3 text-sm text-gray-600">
            Prochaine date disponible :
            <strong><?= htmlspecialchars($suggestedDate) ?></strong>
        </p>
    <?php endif; ?>

<?php else: ?>

    <div class="space-y-4">

        <?php foreach ($rides as $ride): ?>

            <div class="bg-white p-4 rounded shadow flex justify-between items-center">

                <!-- LEFT: RIDE INFO -->
                <div>
                    <p class="font-semibold">
                        <?= htmlspecialchars($ride['lieu_depart']) ?>
                        →
                        <?= htmlspecialchars($ride['lieu_arrivee']) ?>
                    </p>

                    <p class="text-sm text-gray-600">
                        Date : <?= htmlspecialchars($ride['date_depart']) ?>
                        |
                        Heure : <?= htmlspecialchars($ride['heure_depart']) ?>
                    </p>

                    <p class="text-sm">
                        Chauffeur :
                        <strong><?= htmlspecialchars($ride['pseudo']) ?></strong>
                    </p>

                    <p class="text-sm">
                        Places restantes :
                        <strong><?= (int)$ride['nb_places'] ?></strong>
                    </p>

                    <p class="text-sm">
                        Prix :
                        <strong><?= (float)$ride['prix_personne'] ?> crédits</strong>
                    </p>

                    <!-- ECOLOGIQUE -->
                    <?php if (!empty($ride['ecologique'])): ?>
                        <span class="inline-block mt-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
                            🌱 Voyage écologique
                        </span>
                    <?php endif; ?>
                </div>

                <!-- RIGHT: ACTION -->
                <div> // *rides" degil ride
                    <a
                        href="/ride/show?id=<?= (int)$ride['covoiturage_id'] ?>"
                        class="bg-eco text-white px-4 py-2 rounded text-sm hover:bg-green-700 transition"
                    >
                        Détail
                    </a>
                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php
require BASE_PATH . '/app/views/layouts/footer.php';
?>
// eof at 25/12/25 // devam egolicique kisimi arastirilsin
// *rides degil ride ,egolicique olmasa else olmali 