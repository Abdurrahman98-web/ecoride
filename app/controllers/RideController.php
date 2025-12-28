<?php

class RideController
{
    /* ============================================================
       US 3 — SEARCH & LIST RIDES (view/ride/index.php)
       ============================================================ */
    public function index()
    {
        $rides = [];
        $suggestedDate = null;//??

        if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["depart"])) {

            $depart = trim(string: $_GET["depart"]);
            $arrivee = trim(string: $_GET["arrivee"]);
            $date = $_GET["date"];

            $rideModel = new Ride();

            // Search rides by city + date
            $rides = $rideModel->searchRides( $depart, $arrivee, $date);

            // Suggest closest available date if no rides found
            if (empty($rides)) {
                $suggestedDate = $rideModel->getNextAvailableDate($depart, $arrivee, $date);
            }
        }

        // VIEW: views/ride/index.php
        // - Search form
        // - Ride list
        // - Suggested date message???
        require BASE_PATH . "/views/ride/index.php";
    }


    /* ============================================================
       US 5 — RIDE DETAIL??
       ============================================================ */
    public function show()
    {
        if (!isset($_GET["ride_id"])) {
            die("Ride ID missing.");
        }

        $rideId = $_GET["ride_id"];

        $rideModel = new Ride();
        $avisModel = new Avis();

        $ride = $rideModel->getRideDetails($rideId);
        $avis = $avisModel->getAvisByRide($rideId);//??

        if (!$ride) {
            die("Ride not found.");
        }

        // VIEW: views/ride/show.php
        // - Ride details
        // - Driver info
        // - Vehicle info
        // - Avis list
        // - Join button
        require BASE_PATH . "/views/ride/show.php";
        header("Location: views/ride/show.php");//bu veyaa core/router olabilir.
        exit; 
    }


    /* ============================================================
       US 9 — CREATE RIDE (FORM)
       ============================================================ */
    public function create()//??
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        $userId = Session::get("user_id");

        $vehiculeModel = new Vehicule();
        $vehicules = $vehiculeModel->getVehiculesByUser($userId);

        // VIEW: views/ride/create.php
        // - Ride creation form
        // - Vehicle selector
         require BASE_PATH . "/app/views/ride/create.php";
        // buda olabilir,core/router.php. 
    }


    /* ============================================================
       US 9 — STORE RIDE (POST)
       ============================================================ */
    public function store()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            die("Invalid request.");
        }

        $userId = Session::get("user_id");

        $rideModel = new Ride();
        $vehiculeModel = new Vehicule();

        $vehiculeId = $_POST["vehicule_id"];

        // Check if vehicle is electric
        $ecologique = $vehiculeModel->isElectric(vehiculeId: $vehiculeId) ? 1 : 0;

        $rideModel->addRide(
            chauffeurId: $userId,
            vehiculeId: $_POST["vehicule_id"],
            lieuDepart: $_POST["lieu_depart"],
            lieuArrivee: $_POST["lieu_arrivee"],
            dateDepart: $_POST["date_depart"],
            heureDepart: $_POST["heure_depart"],
            dateArrivee: $_POST["date_arrivee"],
            heureArrivee: $_POST["heure_arrivee"],
            prix: $_POST["prix_personne"],
            nbPlaces: $_POST["nb_places"],
            ecologique: $ecologique
        );

        header("Location: user_ride.php?created=1");
        exit;
        // yukardaki exit; kodu core/router.php de olabilir. yadaa views/ride/store.php .
    }
    // be curiful about fonction variables and parameters names.


    /* ============================================================
       US 10 — USER(chauffeur) RIDE HISTORY
       ============================================================ */
    public function history()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        $userId = Session::get("user_id");

        $rideModel = new Ride();
        $rides = $rideModel->getUserRides($userId);

        // VIEW: views/ride/history.php
        // - List of rides (driver + passenger)
        // - Cancel button
        require BASE_PATH . "/app/views/ride/history.php";
    }


    /* ============================================================
       US 11 — START RIDE (DRIVER)
       ============================================================ */
    public function start()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        if (!isset($_GET["ride_id"])) {
            die("Ride ID missing.");
        }

        $rideId = $_GET["ride_id"];
        //$userId = $_GET["user_id"];
        $userId = Session::get("user_id");// pour securiser.

        $rideModel = new Ride();
        $rideModel->startRide($rideId,$userId);

        header("Location: user_rides.php?started=1");
        exit;
    }


    /* ============================================================
       US 11 — END RIDE (ARRIVAL)
       ============================================================ */
    public function end()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        if (!isset($_GET["ride_id"])) {
            die("Ride ID missing.");
        }

        $rideId = $_GET["ride_id"];
         $userId = Session::get("user_id");// pour securiser.


        $rideModel = new Ride();
        $rideModel->endRide($rideId, $userId);

        // After ending ride, passengers will be invited to submit avis
        header("Location: user_rides.php?ended=1"); // wiew eklenecek.
        exit;
    }
}

// End of RideController.php guncellenmesi gerkir(fzrazi ve ilkel kod )
// ended at 14/12/25 09/36.
