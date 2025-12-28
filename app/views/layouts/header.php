<?php
// app/views/layouts/header.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>EcoRide</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Optional: basic config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        eco: '#16a34a'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen">

<!-- SIMPLE HEADER / NAVBAR -->
<header class="bg-eco text-white p-4 mb-6">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">
            EcoRide
        </h1>

        <nav class="space-x-4 text-sm">
            <a href="/rides" class="hover:underline">Rides</a>

            <?php if (class_exists('Session') && Session::get('user_id')): ?>
                <a href="/logout" class="hover:underline">Logout</a>
            <?php else: ?>
                <a href="/login" class="hover:underline">Login</a>
                <a href="/register" class="hover:underline">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="container mx-auto px-4">
    // The main content will be injected here by the specific views,and then we close the tags in footer.php
    // updates by a.a at 05:10 24/12/25
