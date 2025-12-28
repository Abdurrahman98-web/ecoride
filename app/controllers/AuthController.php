<?php

class AuthController {

    /* ======================================
       REGISTER — US 7
       ====================================== */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require 'app/views/auth/register.php';//view çağrısı
            return;
        }

        $userModel = new User();

        // Güçlü şifre kontrolü (şartname bunu ister)
        $password = $_POST['mot_de_passe'];

        if (strlen($password) < 8) {
            die("Le mot de passe doit contenir au moins 8 caractères.");
        }

        if (!preg_match('/[A-Z]/', $password)) {
            die("Le mot de passe doit contenir une lettre majuscule.");
        }

        if (!preg_match('/[0-9]/', $password)) {
            die("Le mot de passe doit contenir un chiffre.");
        }

        // Şifre hash
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Kullanıcı oluştur
        $userId = $userModel->register(
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $hashed,
            $_POST['pseudo']
        );

        // US 7 → yeni kullanıcıya otomatik 20 crédit
        $userModel->addCredits($userId, 20);

        // Default role: user
        $roleModel = new Role();
        $roleModel->addRoleToUser($userId, 3);   // 3 = "utilisateur"

        header("Location: login.php?success=1");//view çağrısı
        exit;
    }


    /* ======================================
       LOGIN
       ====================================== */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require 'app/views/auth/login.php';//view çağrısı //BASE_PATH eklenecek
            return;
        }

        $userModel = new User();
        $user = $userModel->login($_POST['email']);

        if (!$user) {
            die("Identifiants incorrects.");
        }

        // Suspend kontrolü
        if ($userModel->isSuspended($user['utilisateur_id'])==1) {
            die("Votre compte est suspendu.");
        }

        // Şifre doğrulama
        if (!password_verify($_POST['mot_de_passe'], $user['mot_de_passe'])) {
            die("Identifiants incorrects.");
        }

        // Session aç
        Session::set('user_id', $user['utilisateur_id']);

        // Role göre yönlendir
        $roleModel = new Role();
        $roles = $roleModel->getRole($user['utilisateur_id']);

        $roleNames = array_column($roles, 'libelle');

        if (in_array("administrateur", $roleNames)) {
            header("Location: admin/dashboard.php");//view çağrısı
        } elseif (in_array("employe", $roleNames)) {
            header("Location: employe/dashboard.php");//view çağrısı
        } else {
            header("Location: espace.php");//view çağrısı
        }

        exit;
    }


    /* ======================================
       LOGOUT
       ====================================== */
    public function logout()
    {
        Session::destroy();
        header("Location: login.php");//view çağrısı// c"est fait
        exit;
    }
}
//gunncellenme tarihi 03/12/2025 22:10 view çağrısı dikett
//gunncellenme tarihi 24/12/2025 view adaptasyonu.

