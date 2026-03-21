<?php
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/Role.php';

$userModel = new User();
$roleModel = new Role();

$totalCredits = $userModel->getTotalPlatformCredits();
$users = $userModel->getAllUsers();
// $allRoles = $roleModel->getAllRoles();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-7xl mx-auto p-8">

<h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

<!-- PLATFORM CREDIT -->
<div class="bg-white shadow rounded p-6 mb-8">
<h2 class="text-xl font-semibold mb-2">Total Platform Credits</h2>
<p class="text-3xl text-green-600 font-bold">
<?= $totalCredits ?> crédits
</p>
</div>

<!-- CREATE EMPLOYEE -->
<div class="bg-white shadow rounded p-6 mb-8">

<h2 class="text-xl font-semibold mb-4">Create Employee</h2>

<form method="POST" action="<?php echo BASE_URL; ?>/admin/create" class="grid grid-cols-3 gap-4">

<input type="text" name="nom" placeholder="Nom" class="border p-2 rounded" required>

<input type="text" name="prenom" placeholder="Prenom" class="border p-2 rounded" required>

<input type="tel" name="telephone" placeholder="Telephone" class="border p-2 rounded">

<input type="text" name="adresse" placeholder="Adresse" class="border p-2 rounded">

<input type="date" name="date_naissance" placeholder="Date de naissance" class="border p-2 rounded">

<label class="col-span-3">
	<input type="checkbox" name="compte_suspendu" value="1"> Suspend Account
</label>

<input type="text" name="pseudo" placeholder="Pseudo" class="border p-2 rounded" required>

<input type="email" name="email" placeholder="Email" class="border p-2 rounded" required>

<input type="password" name="password" placeholder="Password" class="border p-2 rounded" required>

<!-- Role selection (dynamic)gerkmiyor --> 


<button class="col-span-3 bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Create Employee</button>

</form>
</div>


<!-- USERS TABLE -->

<div class="bg-white shadow rounded p-6">

<h2 class="text-xl font-semibold mb-4">Users</h2>

<table class="w-full border">

<thead class="bg-gray-200">
<tr>

<th class="p-2">ID</th>
<th class="p-2">Pseudo</th>
<th class="p-2">Email</th>
<th class="p-2">Role</th>
<th class="p-2">Credits</th>
<th class="p-2">Status</th>
<th class="p-2">Action</th>

</tr>
</thead>

<tbody>

<?php foreach ($users as $user): ?>

<tr class="border-t text-center">

<td class="p-2"><?= $user['utilisateur_id'] ?></td>

<td class="p-2"><?= $user['pseudo'] ?></td>

<td class="p-2"><?= $user['email'] ?></td>

<td class="p-2"><?= htmlspecialchars($user['roles']) ?></td>

<td class="p-2"><?= $user['credit'] ?></td>

<td class="p-2">
<?= $user['compte_suspendu'] ? 
'<span class="text-red-500">Suspended</span>' :
'<span class="text-green-500">Active</span>' ?>
</td>

<td class="p-2">

<form method="POST" action="<?php echo BASE_URL; ?>/admin/<?= $user['compte_suspendu'] ? 'activate' : 'suspend' ?>">
	<input type="hidden" name="user_id" value="<?= $user['utilisateur_id'] ?>">
	<?php if ($user['compte_suspendu']): ?>
		<button class="bg-green-500 text-white px-3 py-1 rounded">Activate</button>
	<?php else: ?>
		<button class="bg-red-500 text-white px-3 py-1 rounded">Suspend</button>
	<?php endif; ?>
</form>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>
<!-- developed by a.a 06/03/2026. -->