<?php
// View: app/views/auth/register.php
require  BASE_PATH .'/app/views/layouts/header.php';// we need define the BASE_PATH in index.php(?)
?>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-6 text-center text-eco">
        Création de compte
    </h2>

    <!-- SUCCESS MESSAGE -->
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
            Compte créé avec succès. Vous pouvez maintenant vous connecter.
        </div>
    <?php endif; ?>

    <!-- REGISTER FORM -->
    <form method="POST" action="/register" class="space-y-4">

        <!-- PSEUDO -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Pseudo
            </label>
            <input
                type="text"
                name="pseudo"
                required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-eco"
            >
        </div>

        <!-- EMAIL -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Email
            </label>
            <input
                type="email"
                name="email"
                required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-eco"
            >
        </div>

        <!-- PASSWORD -->
        <div>
            <label class="block text-sm font-medium mb-1">
                Mot de passe
            </label>
            <input
                type="password"
                name="mot_de_passe"
                required
                minlength="8"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-eco"
            >
            <p class="text-xs text-gray-500 mt-1">
                Minimum 8 caractères
            </p>
        </div>

        <!-- SUBMIT -->
        <div>
            <button
                type="submit"
                class="w-full bg-eco text-white py-2 rounded hover:bg-green-700 transition"
            >
                Créer le compte
            </button>
        </div>

    </form>

    <!-- LOGIN LINK -->
    <p class="text-sm text-center mt-4">
        Déjà un compte ?
        <a href="/login" class="text-eco font-semibold hover:underline">
            Se connecter
        </a>
    </p>

</div>

<?php
require  BASE_PATH . '/app/views/layouts/footer.php';// we need define the BASE_PATH in index.php(?)
?>
//devlopped by a.a at 24/12/2025 22:59
// end of file auth. 
// Base_path" defined in public/index.php at line 2 in 03:00 25/12/2025. 
