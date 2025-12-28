<?php

class AvisController
{
    /* ============================================================
       Show all reviews for a ride (used in ride detail page — US 5)
       ============================================================ */
    public function listForRide()
    {
        if (!isset($_GET["ride_id"])) {
            die("Missing ride ID.");
        }

        $rideId = $_GET["ride_id"];

        $avisModel = new Avis();
        $avisList = $avisModel->getAvisByRide($rideId);

        // VIEW: avis_list.php SHOULD BE CREATED LATER
        // This view will show all comments + ratings for this specific ride.
        require "views/avis/avis_list.php";//view eklenecek &&&&#####@@@
    }


    /* ============================================================
       Show form to create a review (US 11)
       Participant must confirm ride completion first.
       ============================================================ */
    public function addForm()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php"); //view eklenecek
            exit;
        }

        $userId = Session::get("user_id");

        if (!isset($_GET["ride_id"])) {
            die("Missing ride ID.");
        }

        $rideId = $_GET["ride_id"];

        $participationModel = new Participation();

        // Check if user participated in this ride
        if (!$participationModel->hasJoined($userId, $rideId)) {
            die("Access denied: you did not participate in this ride.");
        }

        // VIEW: avis_add.php SHOULD BE CREATED LATER
        // This view displays a form (rating + comment textarea)
        require "views/avis/avis_add.php"; //view eklenecek &&&&#####@@@
    }


    /* ============================================================
       Save review (US 11)
       ============================================================ */
    public function add()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Invalid request.");
        }

        $userId = Session::get("user_id");
        $rideId = $_POST["ride_id"];
        $rating = $_POST["rating"];
        $comment = $_POST["comment"];

        // Validation
        if ($rating < 1 || $rating > 5) {
            die("Rating must be between 1 and 5.");
        }

        $participationModel = new Participation();
        if (!$participationModel->hasJoined($userId, $rideId)) {
            die("Access denied: cannot review a ride you did not join.");
        }

        $avisModel = new Avis();
        $avisModel->addAvis(
            $avisModel->getChauffeurIdByRide($rideId), // Chauffeur ID. bulan bir medtod eksik avis.modelinde 
            $userId,
            $rideId,
            $rating,
            $comment
        );

        header("Location: ride_detail.php?ride_id=$rideId&avis_added=1");// view eklenecek%%
        exit;
    } //burdan devam et


    /* ============================================================
       (EMPLOYEE FEATURE) — Validate or refuse review (US 12)
       ============================================================ */
    public function validate()
    {
        if (!Session::has("user_id")) {
            die("Unauthorized.");
        }

        $userId = Session::get("user_id");

        // Check if user is employee
        $roleModel = new Role();
        if (!$roleModel->userHasRole($userId, "employe")) {
            die("Only employees can validate reviews.");
        }

        if (!isset($_GET["avis_id"])) {
            die("Missing avis ID.");
        }

        $avisId = $_GET["avis_id"];

        $avisModel = new Avis();
        $avisModel->validerAvis($avisId);//burda bir method eksik avis modelinde

        header("Location: employee_dashboard.php?avis_validated=1");//view eklenecek
        exit;
    }


    /* ============================================================
       (EMPLOYEE FEATURE) — Refuse review (US 12)
       ============================================================ */
    public function refuse()
    {
        if (!Session::has("user_id")) {
            die("Unauthorized.");
        }

        $userId = Session::get("user_id");

        // Employee check
        $roleModel = new Role();
        if (!$roleModel->userHasRole($userId, "employe")) {
            die("Only employees can refuse reviews.");
        }

        if (!isset($_GET["avis_id"])) {
            die("Missing avis ID.");
        }

        $avisId = $_GET["avis_id"];

        $avisModel = new Avis();
        $avisModel->refuserAvis($avisId);

        header("Location: employee_dashboard.php?avis_refused=1");
        exit;
    }
}
// ReviewController class final at 10/12/2025 08:11