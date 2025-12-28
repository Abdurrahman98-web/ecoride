<?php
// View: app/views/auth/login.php
require BASE_PATH . '/app/views/layouts/header.php'; // we need define the BASE_PATH in index.php 
?>
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-6 text-center text-eco">
        Connexion
    </h2>

    <!-- ERROR MESSAGE (optional) -->
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            Identifiants incorrects.
        </div>
    <?php endif; ?>

    <!-- LOGIN FORM -->
    <form method="POST" action="/login" class="space-y-4">

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
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-eco"
            >
        </div>

        <!-- SUBMIT -->
        <div>
            <button
                type="submit"
                class="w-full bg-eco text-white py-2 rounded hover:bg-green-700 transition"
            >
                Se connecter
            </button>
        </div>

    </form>

    <!-- REGISTER LINK -->
    <p class="text-sm text-center mt-4">
        Pas encore de compte ?
        <a href="/register" class="text-eco font-semibold hover:underline">
            Créer un compte
        </a>
    </p>

</div>

<?php
require BASE_PATH . '/app/views/layouts/footer.php';
?>
