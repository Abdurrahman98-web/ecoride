<?php
// View: app/views/ride/show.php
require BASE_PATH . '/app/views/layouts/header.php';

/*
Expected variables from controller:
- $ride (array)
- optional: $alreadyJoined (bool)
*/
?>

<?php if (empty($ride)): ?>

    <div class="bg-red-100 text-red-700 p-4 rounded">
        Covoiturage introuvable.
    </div>

<?php else: ?>

<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-4 text-eco">
        Détail du covoiturage
    </h2>

    <!-- ROUTE -->
    <p class="font-semibold text-lg">
        <?= htmlspecialchars($ride['lieu_depart']) ?>
        →
        <?= htmlspecialchars($ride['lieu_arrivee']) ?>
    </p>

    <!-- DATE / TIME -->
    <p class="text-sm text-gray-600 mt-1">
        Départ :
        <?= htmlspecialchars($ride['date_depart']) ?>
        à <?= htmlspecialchars($ride['heure_depart']) ?>
    </p>

    <p class="text-sm text-gray-600">
        Arrivée :
        <?= htmlspecialchars($ride['date_arrivee']) ?>
        à <?= htmlspecialchars($ride['heure_arrivee']) ?>
    </p>

    <hr class="my-4">

    <!-- CHAUFFEUR -->
    <p class="text-sm">
        Chauffeur :
        <strong><?= htmlspecialchars($ride['pseudo']) ?></strong>
    </p>

    <p class="text-sm">
        Véhicule :
        <?= htmlspecialchars($ride['marque']) ?>
        <?= htmlspecialchars($ride['modele']) ?>
        (<?= htmlspecialchars($ride['energie']) ?>)
    </p>

    <!-- ECOLOGIQUE -->
    <?php if (!empty($ride['ecologique'])): ?>
        <span class="inline-block mt-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
            🌱 Voyage écologique (véhicule électrique)
        </span>
    <?php endif; ?>

    <hr class="my-4">

    <!-- PLACES / PRICE -->
    <p class="text-sm">
        Places restantes :
        <strong><?= (int)$ride['nb_places'] ?></strong>
    </p>

    <p class="text-sm mb-4">
        Prix par personne :
        <strong><?= (float)$ride['prix_personne'] ?> crédits</strong>
    </p>

    <!-- ACTION -->
    <?php if (!class_exists('Session') || !Session::get('user_id')): ?>

        <!-- NOT LOGGED IN -->
        <div class="bg-yellow-100 text-yellow-800 p-3 rounded text-sm">
            Vous devez être connecté pour participer.
        </div>

        <a
            href="/login"
            class="inline-block mt-3 text-eco font-semibold hover:underline"
        >
            Se connecter
        </a>

    <?php elseif (!empty($alreadyJoined)): ?>

        <!-- ALREADY JOINED -->
        <div class="bg-blue-100 text-blue-800 p-3 rounded text-sm">
            Vous participez déjà à ce covoiturage.
        </div>

    <?php elseif ($ride['nb_places'] <= 0): ?>

        <!-- NO PLACES -->
        <div class="bg-red-100 text-red-700 p-3 rounded text-sm">
            Plus aucune place disponible.
        </div>

    <?php else: ?>

        <!-- JOIN FORM -->
        <form method="POST" action="/participation/join" class="mt-4">
            <input type="hidden" name="ride_id" value="<?= (int)$ride['covoiturage_id'] ?>">

            <button
                type="submit"
                class="bg-eco text-white px-6 py-2 rounded hover:bg-green-700 transition"
            >
                Participer
            </button>
        </form>

    <?php endif; ?>

</div>

<?php endif; ?>

<?php
require BASE_PATH . '/app/views/layouts/footer.php';
?>
// not menionedd any require ($ride=??)
// Compare this snippet from ecoride/app/controllers/RideController.php:
//devlopped at 11:33 AM.