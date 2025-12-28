<?php

class ParticipationController
{
    /* ============================================================
       JOIN A RIDE  (US 6)
       ============================================================ */
    public function join()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        $userId = Session::get("user_id");

        if (!isset($_GET["ride_id"])) {
            die("Missing ride ID.");
        }

        $rideId = $_GET["ride_id"];

        $rideModel = new Ride();
        $participationModel = new Participation();
        $userModel = new User();


        /* ------------------------------------------------------------
           1) Check ride exists
        ------------------------------------------------------------ */
        $ride = $rideModel->getRideDetails($rideId);//inntegration with ride model.

        if (!$ride) {
            die("Ride does not exist.");
        }


        /* ------------------------------------------------------------
           2) Check available seats
        ------------------------------------------------------------ */
        if ($ride["nb_places"] <= 0) {
            die("No seats available for this ride.");
        }


        /* ------------------------------------------------------------
           3) Check user credit
        ------------------------------------------------------------ */
        // Ride price = (chauffeur price) - 2 credits (platform fee)
        $requiredCredits = $ride["prix"];

        $userCredits = $userModel->getCredits($userId);

        if ($userCredits < $requiredCredits) {
            die("Not enough credits to join this ride.");
        }


        /* ------------------------------------------------------------
           4) Check if already joined
        ------------------------------------------------------------ */
        if ($participationModel->hasJoined($userId, $rideId)) {
            die("You already joined this ride.");//inntegration with participation model.
        }


        /* ------------------------------------------------------------
           5) Show confirmation page (double confirmation)
        ------------------------------------------------------------ */

        // VIEW: participation_confirm.php MUST BE CREATED LATER
        // This page should ask: "Do you REALLY want to join this ride?"
        // With a button that posts to participationController->confirmJoin()
        require "views/participation/participation_confirm.php";
    }


    /* ============================================================
       CONFIRM JOIN (after double confirmation)
       ============================================================ */
    public function confirmJoin()
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

        $rideModel = new Ride();
        $participationModel = new Participation();
        $userModel = new User();


        /* ------------------------------------------------------------
           1) Reserve seat
        ------------------------------------------------------------ */
        $rideModel->reduceSeat($rideId); //"koltuk azaltma en son."

        // burdann devam et participation modeldeki fonksiyonu kullanarak.
        /* ------------------------------------------------------------
           2) Deduct credits from passenger
        ------------------------------------------------------------ */
        $ride = $rideModel->getRideDetails($rideId);
        $price = $ride["prix"];

        $userModel->removeCredits($userId, $price);


        /* ------------------------------------------------------------
           3) Participation record
        ------------------------------------------------------------ */
        $participationModel->hasJoined($rideId, $userId);


        header("Location: ride_detail.php?ride_id=$rideId&joined=1");// view eklenecek
        exit;
    }


    /* ============================================================
       CANCEL PARTICIPATION (US 10)
       ============================================================ */
    public function cancel()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        $userId = Session::get("user_id");

        if (!isset($_GET["ride_id"])) {
            die("Missing ride ID.");
        }

        $rideId = $_GET["ride_id"];

        $participationModel = new Participation();
        //*$rideModel = new Ride();
         // $userModel = new User();
           


        /* ------------------------------------------------------------
           1) Check if user participated
        ------------------------------------------------------------ */
        if (!$participationModel->hasJoined($userId, $rideId)) {
            die("You cannot cancel a ride you did not join.");
        }


        /* ------------------------------------------------------------
           2) Show cancel confirmation view
        ------------------------------------------------------------ */
        // VIEW: participation_cancel_confirm.php MUST BE CREATED LATER
        // This view should ask: "Cancel participation? YES / NO"
        require "views/participation/participation_cancel_confirm.php";
    }


    /* ============================================================
       CONFIRM CANCEL (after double confirmation)
       ============================================================ */
    public function confirmCancel()
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

        $participationModel = new Participation();
        $rideModel = new Ride();
        $userModel = new User();


        /* ------------------------------------------------------------
           1) Cancel ride participation /2) Restore seat
        ------------------------------------------------------------ */
       // $participationModel->cancelParticipation($rideId, $userId);
        //je coll la fonction cancelParticipation du model participation.php avec cancelRide function.
        $rideModel->cancelRide($rideId, $userId);



        /* ------------------------------------------------------------
           3) Refund credits to user
        ------------------------------------------------------------ */
        $ride = $rideModel->getRideDetails($rideId);
        $price = $ride["prix"];

        $userModel->addCredits($userId, $price);


        header("Location: user_rides.php?cancelled=1");
        exit;
    }
}
// eof at aat 12/12/25 in 18:14
