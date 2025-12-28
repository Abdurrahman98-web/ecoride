<?php

class PreferenceController {

    /* ======================================
       Show preference page (main form)
       Only chauffeurs are allowed to edit
       ====================================== */
    public function index()
    {
        if (!Session::has('user_id')) {
            header("Location: login.php");//VIew cağrısı
            exit;
        }

        $userId = Session::get('user_id');

        // Only chauffeurs are allowed to edit preferences
        $roleModel = new Role();
        if (!$roleModel->userHasRole($userId, "chauffeur")) {
            die("Access denied: Only chauffeurs can edit preferences.");
        }

        $prefModel = new Preference();

        // Load main preferences + custom preferences
        $preferences = $prefModel->getByUser($userId);
        $custom = $prefModel->getchuffeurPreferences($userId);

        require 'views/preference/preferences_form.php';//VIew cağrısı
    }


    /* ======================================
       Save main preferences
       (smoker, animal, notes)
       ====================================== */
    public function save()
    {
        if (!Session::has('user_id')) {
            header("Location: login.php");//VIew cağrısı
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: preferences.php");//VIew cağrısı
            exit;
        }

        $userId = Session::get('user_id');

        // Only chauffeurs can update preferences
        $roleModel = new Role();
        if (!$roleModel->userHasRole($userId, "chauffeur")) {
            die("Access denied.");
        }

        $prefModel = new Preference();

        $fumeur = isset($_POST['fumeur']) ? 1 : 0;
        $animal = isset($_POST['animal']) ? 1 : 0;
        $notes  = $_POST['notes'] ?? null;

        // Create or update preference record
        $prefModel->save($userId, $fumeur, $animal, $notes);

        header("Location: preferences.php?saved=1");//VIew cağrısı
        exit;
    }


    /* ======================================
       Add a custom preference
       (chauffeur personal rules)
       ====================================== */
    public function addCustom()
    {
        if (!Session::has('user_id')) {
            header("Location: login.php");//VIew cağrısı
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: preferences.php");//VIew cağrısı
            exit;
        }

        $userId = Session::get('user_id');

        // Only chauffeurs can add custom preferences
        $roleModel = new Role();
        if (!$roleModel->userHasRole($userId, "chauffeur")) {
            die("Access denied.");
        }

        $text = trim($_POST['texte']);

        if (strlen($text) < 2) {
            die("Custom preference must contain at least 2 characters.");
        }

        $prefModel = new Preference();
        $prefModel->addChuffeurPreference($userId, $text);

        header("Location: preferences.php?added=1");//VIew cağrısı
        exit;
    }


    /* ======================================
       Delete a custom preference
       ====================================== */
    public function deleteCustom()
    {
        if (!Session::has('user_id')) {
            header("Location: login.php");//VIew cağrısı
            exit;
        }
//burda devamı var 04/12/2025 00:44
        $userId = Session::get('user_id');
        $customId = $_GET['id'] ?? null;

        if (!$customId) {
            die("Missing preference ID.");
        }

        // Only chauffeurs can delete custom preferences
        $roleModel = new Role();
        if (!$roleModel->userHasRole($userId, "chauffeur")) {
            die("Access denied.");
        }

        $prefModel = new Preference();
        $prefModel->deleteChuffeurPreference($customId);

        header("Location: preferences.php?deleted=1"); //VIew cağrısı
        exit;
    }
}
// End of PreferenceController class at 04:12 in 4/12/2025 