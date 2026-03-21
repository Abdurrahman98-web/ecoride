<?php
require BASE_PATH . '/app/views/layouts/header.php';

// Expected variables from controller:
// - $pendingAvis : array of reviews awaiting validation
// - $problematicRides : array of problematic rides with participation info

?>

<div class="max-w-6xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">Espace Employé</h1>

    <!-- PENDING REVIEWS -->
    <section class="bg-white shadow rounded p-6 mb-6">
        <h2 class="text-xl font-semibold mb-2">Avis en attente de validation</h2>

        <p class="text-sm text-gray-600 mb-4">Un Employé peut depuis son espace, après connexion, valider les avis déposés par les participants sur le chauffeur avant visibilité, il peut également refuser l’avis.</p>

        <?php if (empty($pendingAvis)): ?>
            <div class="bg-yellow-50 text-yellow-800 p-3 rounded">Aucun avis en attente.</div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($pendingAvis as $avis): ?>
                    <div class="border rounded p-4">
                        <p class="font-semibold">Participant : <?= htmlspecialchars($avis['pseudo'] ?? $avis['utilisateur_id']) ?></p>
                        <p class="text-sm text-gray-700">Note : <?= htmlspecialchars($avis['note'] ?? '') ?></p>
                        <p class="text-sm mt-2">Commentaire : <?= nl2br(htmlspecialchars($avis['commentaire'] ?? '')) ?></p>

                        <div class="mt-3 flex gap-2">
                            <form method="POST" action="<?= BASE_URL ?>/employee/validateAvis">
                                <input type="hidden" name="avis_id" value="<?= (int)($avis['avis_id'] ?? $avis['id'] ?? 0) ?>">
                                <button class="bg-green-600 text-white px-3 py-1 rounded">Valider</button>
                            </form>

                            <form method="POST" action="<?= BASE_URL ?>/employee/refuseAvis">
                                <input type="hidden" name="avis_id" value="<?= (int)($avis['avis_id'] ?? $avis['id'] ?? 0) ?>">
                                <button class="bg-red-600 text-white px-3 py-1 rounded">Refuser</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- PROBLEMATIC RIDES -->
    <section class="bg-white shadow rounded p-6">
        <h2 class="text-xl font-semibold mb-2">Covoiturages signalés</h2>

        <p class="text-sm text-gray-600 mb-4">De plus, il doit être capable de visionner les covoiturages qui se sont mal passé en ayant le numéro du covoiturage, pseudo et le mail des deux intéressés, puis, un descriptif du trajet (notamment date de départ et d’arrivée ainsi que le lieu)</p>

        <?php if (empty($problematicRides)): ?>
            <div class="bg-yellow-50 text-yellow-800 p-3 rounded">Aucun covoiturage problématique signalé.</div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($problematicRides as $p): ?>
                    <div class="border rounded p-4">
                        <p class="font-semibold">Numéro du covoiturage : <span class="text-eco"><?= htmlspecialchars($p['covoiturage_id'] ?? $p['covoiturageId'] ?? '') ?></span></p>

                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <p class="text-sm font-medium">Participant</p>
                                <p class="text-sm">Pseudo : <?= htmlspecialchars($p['pseudo'] ?? $p['participant_pseudo'] ?? '') ?></p>
                                <p class="text-sm">Email : <?= htmlspecialchars($p['email'] ?? $p['participant_email'] ?? '') ?></p>
                            </div>

                            <div>
                                <p class="text-sm font-medium">Chauffeur</p>
                                <p class="text-sm">Pseudo : <?= htmlspecialchars($p['chauffeur_pseudo'] ?? $p['chauffeur_pseudo'] ?? '') ?></p>
                                <p class="text-sm">Email : <?= htmlspecialchars($p['chauffeur_email'] ?? $p['chauffeur_email'] ?? '') ?></p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <p class="text-sm">Lieu : <?= htmlspecialchars(($p['lieu_depart'] ?? '') . ' → ' . ($p['lieu_arrivee'] ?? '')) ?></p>
                            <p class="text-sm">Date départ : <?= htmlspecialchars($p['date_depart'] ?? '') ?></p>
                            <p class="text-sm">Date arrivée : <?= htmlspecialchars($p['date_arrivee'] ?? '') ?></p>
                            <p class="text-sm mt-2">Commentaire signalé : <?= nl2br(htmlspecialchars($p['commentaire'] ?? '')) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

</div>

<?php
require BASE_PATH . '/app/views/layouts/footer.php';
?>
