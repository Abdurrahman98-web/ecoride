<?php
// View: app/views/ride/history.php
require BASE_PATH . '/app/views/layouts/header.php';

/*
<?php
// Accessible only to authenticated users
// Controller should ensure user is logged in
Expected variables from controller:
- $asDriver   (array)  // rides where user is chauffeur
- $asPassenger (array) // rides where user is participant
*/
?>

<h2 class="text-2xl font-bold mb-6 text-eco">
    Mon historique de covoiturages
</h2>

<!-- =========================
     CHAUFFEUR (DRIVER) HISTORY
     ========================= -->
<h3 class="text-xl font-semibold mb-3">
    En tant que chauffeur
</h3>

<?php if (empty($asDriver)): ?>

    <p class="text-sm text-gray-600 mb-6">
        Aucun covoiturage créé en tant que chauffeur.
    </p>

<?php else: ?>

    <div class="space-y-3 mb-8">
        <?php foreach ($asDriver as $ride): ?>
            <div class="bg-white p-4 rounded shadow flex justify-between items-center">

                <div>
                    <p class="font-semibold">
                        <?= htmlspecialchars($ride['lieu_depart']) ?>
                        →
                        <?= htmlspecialchars($ride['lieu_arrivee']) ?>
                    </p>

                    <p class="text-sm text-gray-600">
                        <?= htmlspecialchars($ride['date_depart']) ?>
                        à <?= htmlspecialchars($ride['heure_depart']) ?>
                    </p>

                    <p class="text-sm">
                        Statut :
                        <strong><?= htmlspecialchars($ride['statut']) ?></strong>
                    </p>
                </div>

                <div class="space-x-2">
                    <a
                        href="/ride/show?id=<?= (int)$ride['covoiturage_id'] ?>"
                        class="text-sm text-eco font-semibold hover:underline"
                    >
                        Détail
                    </a>

                    <?php if ($ride['statut'] === 'terminé'): ?>
                        <a
                            href="/ride/start?id=<?= (int)$ride['covoiturage_id'] ?>"
                            class="text-sm text-blue-600 hover:underline"
                        >
                            Démarrer
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<!-- =========================
     PASSENGER HISTORY
     ========================= -->
<h3 class="text-xl font-semibold mb-3">
    En tant que passager
</h3>

<?php if (empty($asPassenger)): ?> 

    <p class="text-sm text-gray-600">
        Aucun covoiturage rejoint en tant que passager.
    </p>

<?php else: ?>

    <div class="space-y-3">
        <?php foreach ($asPassenger as $ride): ?>
            <div class="bg-white p-4 rounded shadow flex justify-between items-center">

                <div>
                    <p class="font-semibold">
                        <?= htmlspecialchars($ride['lieu_depart']) ?>
                        →
                        <?= htmlspecialchars($ride['lieu_arrivee']) ?>
                    </p>

                    <p class="text-sm text-gray-600">
                        <?= htmlspecialchars($ride['date_depart']) ?>
                        à <?= htmlspecialchars($ride['heure_depart']) ?>
                    </p>

                    <p class="text-sm">
                        Statut participation :
                        <strong><?= htmlspecialchars($ride['statut_participation']) ?></strong>
                    </p>
                </div>

                <div class="space-x-2">
                    <a
                        href="/ride/show?id=<?= (int)$ride['covoiturage_id'] ?>"
                        class="text-sm text-eco font-semibold hover:underline"
                    >
                        Détail
                    </a>

                    <?php if ($ride['statut_participation'] === 'confirme'): ?>
                        <a
                            href="/participation/cancel?id=<?= (int)$ride['covoiturage_id'] ?>"
                            class="text-sm text-red-600 hover:underline"
                        >
                            Annuler
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<?php
require BASE_PATH . '/app/views/layouts/footer.php';
?>
<?php
/*
Notes:
- $asDriver comes from RideModel::getUserRides()
- $asPassenger comes from ParticipationModel::getHistory()
*/


// Compare this snippet from ecoride/app/models/Ride.php:
// ride.php ,RideController.php uyygunlasitrilmali
//$asDriver = $rideModel->getUserRides($userId); yapildi
// Compare this snippet from ecoride/app/models/participation.php:
// $asPassenger = $participationModel->getHistory($userId); done
// devloper ride/history.php view to show both lists.
?>