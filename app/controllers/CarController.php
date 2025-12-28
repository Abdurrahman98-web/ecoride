<?php

class VehiculeController {

    /* ============================================
       Show list of user's vehicules (chauffeur)
       ============================================ */
    public function index()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        $userId = Session::get("user_id");

        // Only chauffeurs can manage vehicles
        $roleModel = new Role();
        if (!$roleModel->userHasRole($userId, "chauffeur")) {
            die("Access denied: only chauffeurs can manage vehicules.");
        }

        $vehiculeModel = new Vehicule();
        $vehicules = $vehiculeModel->getVehiculesByUser($userId);

        // VIEW: vehicules_list.php SHOULD BE CREATED LATER @@@@@#####
        // (Vehicle list page: shows table of vehicules + buttons edit/delete)
        require "views/vehicule/index.php";// vehicule/list.php

    }


    /* ============================================
       Show form to add a vehicule
       ============================================ */
    public function addForm()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        $userId = Session::get("user_id");

        // VIEW: vehicule_add.php SHOULD BE CREATED LATER @@@&&&!!!!
        // (Form: marque, modele, couleur, energie, immatriculation, date)
        require "views/vehicule/vehicule_add.php";
    }


    /* ============================================
       Save new vehicule
       ============================================ */
    public function add()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: vehicules.php");//view index.php/create.php var vehicules.php
            exit;
        }

        $userId = Session::get("user_id");

        $marque = $_POST["marque"];
        $modele = $_POST["modele"];
        $couleur = $_POST["couleur"];
        $energie = $_POST["energie"];
        $immatriculation = $_POST["immatriculation"];
        $date = $_POST["date_mise_en_circulation"];
        $nbPlaces = $_POST["nb_places"];

        $vehiculeModel = new Vehicule();
        $vehiculeModel->addVehicule(
            $userId,
            $marque,
            $modele,
            $couleur,
            $energie,
            $immatriculation,
            $date,
            $nbPlaces
        );

        header("Location: vehicules.php?added=1"); //view eklenecek ////view index.php/create.php var vehicules.php
        exit;
    }


    /* ============================================
       Show "edit vehicule" form
       ============================================ */
    public function editForm()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php");
            exit;
        }

        $vehiculeId = $_GET["id"] ?? null;

        if (!$vehiculeId) {
            die("Vehicule ID missing.");
        }

        $vehiculeModel = new Vehicule();
        $vehicule = $vehiculeModel->getVehiculeById($vehiculeId);

        if (!$vehicule) {
            die("Vehicule not found.");
        }

        // VIEW: vehicule_edit.php SHOULD BE CREATED LATER
        // (Form pre-filled with vehicle data)
        require "views/vehicule/vehicule_edit.php"; //view index.php/create.php var vehicules.php
    }


    /* ============================================
       Update vehicule
       ============================================ */
    public function update()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php"); //view eklenecek
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: vehicules.php");
            exit;
        }

        $vehiculeId = $_POST["vehicule_id"];

        $marque          = $_POST["marque"];
        $modele          = $_POST["modele"];
        $couleur         = $_POST["couleur"];
        $energie         = $_POST["energie"];
        $immatriculation = $_POST["immatriculation"];
        $date            = $_POST["date_mise_en_circulation"];
        $nbPlaces        = $_POST["nb_places"];

        $vehiculeModel = new Vehicule();
        $vehiculeModel->updateVehicule(
            $vehiculeId,
            $marque,
            $modele,
            $couleur,
            $energie,
            $immatriculation,
            $date,
            $nbPlaces 
        );

        header("Location: vehicules.php?updated=1"); //view eklenecek
        exit;
    }


    /* ============================================
       Delete vehicule
       ============================================ */
    public function delete()
    {
        if (!Session::has("user_id")) {
            header("Location: login.php"); //view eklenecek
            exit;
        }

        $vehiculeId = $_GET["id"] ?? null;

        if (!$vehiculeId) {
            die("Missing vehicule ID.");
        }

        $vehiculeModel = new Vehicule();
        $vehiculeModel->deleteVehicule($vehiculeId);

        header("Location: vehicules.php?deleted=1");// view eklenecek
        exit;
    }
}
//* 05:24 AM 4/12/2025 End of VehiculeController class