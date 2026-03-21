<?php
//burdaan devam  (14/12/25 10;20)
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
              views/employee/dashboard.php
              - List pending reviews (approve / refuse buttons)
              - List problematic rides with details
          ------------------------------------------------------------ */
          require BASE_PATH . "/app/views/employee/dashboard.php";
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

        // accept POST or GET
        $avisId = $_POST['avis_id'] ?? $_GET['avis_id'] ?? null;

        if (!$avisId) {
            die("Avis ID missing.");
        }

        $avisModel = new Avis();
        $avisModel->validerAvis($avisId);

        header("Location: " . BASE_URL . "/employee?validated=1");
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

        // accept POST or GET
        $avisId = $_POST['avis_id'] ?? $_GET['avis_id'] ?? null;

        if (!$avisId) {
            die("Avis ID missing.");
        }

        $avisModel = new Avis();
        $avisModel->refuserAvis($avisId);

        header("Location: " . BASE_URL . "/employee?refused=1");
        exit;
    }
}
//end of file at 18/12/25 11;05
