//burdaan devam  (14/12/25 10;20)
<?php

class EmployeeController
{
    /* ============================================================
       EMPLOYEE DASHBOARD
       ============================================================ */
    public function index()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        // OPTIONAL: role check (employee)
        //if (!Session::hasRole("EMPLOYE")) { die("Access denied"); }

        $avisModel = new Avis();
        $rideModel = new Ride();

        // Reviews waiting for validation
        $pendingAvis = $avisModel->getAvisNonValides();

        // Rides reported as problematic

        $problematicRides = $rideModel->getProblematicRides();

        /* ------------------------------------------------------------
           VIEW REQUIRED
           ------------------------------------------------------------
           views/employee/index.php
           - List pending reviews (approve / refuse buttons)
           - List problematic rides with details
        ------------------------------------------------------------ */
        require "views/employee/index.php";
    }


    /* ============================================================
       VALIDATE AVIS
       ============================================================ */
    public function validateAvis()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        if (!isset($_GET["avis_id"])) {
            die("Avis ID missing.");
        }

        $avisId = $_GET["avis_id"];

        $avisModel = new Avis();
        $avisModel->validerAvis($avisId);

        header("Location: employee.php?validated=1");
        exit;
    }


    /* ============================================================
       REFUSE AVIS
       ============================================================ */
    public function refuseAvis()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        if (!isset($_GET["avis_id"])) {
            die("Avis ID missing.");
        }

        $avisId = $_GET["avis_id"];

        $avisModel = new Avis();
        $avisModel->refuserAvis($avisId);

        header("Location: employee.php?refused=1");
        exit;
    }
}
//end of file at 18/12/25 11;05
