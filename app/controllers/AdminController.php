<?php

class AdminController
{
    /* ============================================================
       ADMIN DASHBOARD
       ============================================================ */
    public function index()
    {
        if (!Session::has("user_id")) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }

        // OPTIONAL role check
        //if (!Session::hasRole("ADMIN")) { die("Access denied"); }

        $userModel = new User();
        $rideModel = new Ride();
        $date = date("Y-m-d");

        // Statistics
        $ridesPerDay = $rideModel->countRidesPerDay($date);
        $creditsPerDay = $userModel->creditsPerDay($date);
        $totalCredits = $userModel->getTotalPlatformCredits();

        // Users & employees list
        $users = $userModel->getAllUsers();

        /* ------------------------------------------------------------
           VIEW REQUIRED
           ------------------------------------------------------------
           views/admin/index.php
           - Rides per day chart
           - Credits per day chart
           - Total credits
           - User list (suspend / activate)
        ------------------------------------------------------------ */
        require BASE_PATH . "/app/views/admin/dashboard.php";
    }


    /* ============================================================
       CREATE EMPLOYEE ACCOUNT
       ============================================================ */
    public function createEmployee()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Invalid request.");
        }

        $userModel = new User();

        // Collect fields from POST
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        $date_naissance = $_POST['date_naissance'] ?? null;
        $compte_suspendu = isset($_POST['compte_suspendu']) ? 1 : 0;
        $pseudo = $_POST['pseudo'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($pseudo) || empty($email) || empty($password)) {
            die('Pseudo, email and password are required.');
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $userId = $userModel->createEmployee(
            $nom,
            $prenom,
            $telephone,
            $adresse,
            $date_naissance,
            $compte_suspendu,
            $pseudo,
            $email,
            $hashed
        );
        require_once BASE_PATH . '/app/models/Role.php';

        // Basic validation: ensure we have a valid inserted user id
        if (!$userId || !is_numeric($userId)) {
            die('Failed to create user or invalid user id returned.');
        }

        // Assign a role to the newly created employee. Use posted role if present.
        $roleModel = new Role();
        $selectedRole = isset($_POST['role']) && !empty($_POST['role']) ? (int)$_POST['role'] : (int)Role::ROLE_EMPLOYE;

        // Verify role exists
        $roleRow = $roleModel->getRole($selectedRole);
        if (!$roleRow) {
            die('Selected role does not exist in the database.');
        }

        // Try to add role and handle possible DB errors
        try {
            $res = $roleModel->addRoleToUser($userId, $selectedRole);
            if ($res === false) {
                // addRoleToUser returns false when role already assigned
                // that's not fatal, continue
            }
        } catch (Exception $e) {
            die('Database error while assigning role: ' . $e->getMessage());
        }

        header("Location: " . BASE_URL . "/admin?employee_created=1");
        exit;
    }


    /* ============================================================
       SUSPEND USER / EMPLOYEE
       ============================================================ */
    public function suspend()
    {
        $userId = $_REQUEST['user_id'] ?? null;
        if (!$userId) { die('User ID missing.'); }
        $userModel = new User();
        $userModel->suspendUser($userId);
        header("Location: " . BASE_URL . "/admin?suspended=1");
        exit;
    }


    /* ============================================================
       ACTIVATE USER / EMPLOYEE
       ============================================================ */
    public function activate()
    {
        $userId = $_REQUEST['user_id'] ?? null;
        if (!$userId) { die('User ID missing.'); }
        $userModel = new User();
        $userModel->activateUser($userId);
        header("Location: " . BASE_URL . "/admin?activated=1");
        exit;
    }
}
// End of AdminController class 23/12/2025