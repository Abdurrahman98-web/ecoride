<?php

class AdminController
{
    /* ============================================================
       ADMIN DASHBOARD
       ============================================================ */
    public function index()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
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
        require "views/admin/index.php";
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

        $hashed = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $userId = $userModel->createEmployee(
            $_POST["pseudo"],
            $_POST["email"],
            $hashed
        );

        header("Location: admin.php?employee_created=1");
        exit;
        // vieww eklenncek
    }


    /* ============================================================
       SUSPEND USER / EMPLOYEE
       ============================================================ */
    public function suspend()
    {
        if (!isset($_GET["user_id"])) {
            die("User ID missing.");
        }

        $userModel = new User();
        $userModel->suspendUser($_GET["user_id"]);

        header("Location: admin.php?suspended=1");
        exit;
    }


    /* ============================================================
       ACTIVATE USER / EMPLOYEE
       ============================================================ */
    public function activate()
    {
        if (!isset($_GET["user_id"])) {
            die("User ID missing.");
        }

        $userModel = new User();
        $userModel->activateUser($_GET["user_id"]);

        header("Location: admin.php?activated=1");
        exit;
    }
}
// End of AdminController class 23/12/2025