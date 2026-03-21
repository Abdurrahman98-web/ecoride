<?php

class AuthController {

    /* ======================================
       REGISTER — US 7
       ====================================== */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require BASE_PATH .'/app/views/auth/register.php';//view çağrısı. BB
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
            $_POST['email'],
            $hashed,
            $_POST['pseudo']
        );

        // US 7 → yeni kullanıcıya otomatik 20 crédit
        // $userModel->addCredits($userId, 20); // il ya deja a function register(). 

        // Default role: user
        require_once  BASE_PATH . '/app/models/Role.php'; 
        $roleModel = new Role();
        $roleModel->addRoleToUser($userId, 4);   // 4 = "utilisateur(passger in db)"
        //view eklene bilir ileride.
        // Otomatik olarak oturum açalım ve kullanıcıyı rol seçim sayfasına yönlendirelim
        Session::set('user_id', $userId);
        Session::set('pseudo', $_POST['pseudo']);

        header("Location: " . BASE_URL . "/user/choose-role");
        exit;
    }


    /* ======================================
       LOGIN
       ====================================== */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require BASE_PATH . '/app/views/auth/login.php';//view çağrısı //BASE_PATH eklenecek
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

        // Role göre yönlendir (daha güvenilir: role_id ile kontrol)
        require_once BASE_PATH . '/app/models/Role.php';
        $roleModel = new Role();
        $roles = $roleModel->getRolesByUser($user['utilisateur_id']);

        // Eğer DB'de rol yoksa debug için log/notice ekleyelim
        if (empty($roles)) {
            // fallback: home
            header("Location: " . BASE_URL . "/");
            exit;
        }

        $roleIds = array_map('intval', array_column($roles, 'role_id'));

        if (in_array(intval(Role::ROLE_ADMIN), $roleIds, true)) {
            header("Location: " . BASE_URL . "/admin");
        } elseif (in_array(intval(Role::ROLE_EMPLOYE), $roleIds, true)) {
            header("Location: " . BASE_URL . "/employee");
        } elseif (in_array(intval(Role::ROLE_CHAUFFEUR), $roleIds, true)) {
            // chauffeur -> maybe driver dashboard (fallback to home for now)
            header("Location: " . BASE_URL . "/");
        } else {
            header("Location: " . BASE_URL . "/");
        }

        exit;
    }


    /* ======================================
       LOGOUT
       ====================================== */
    public function logout()
    {
        Session::destroy();
        header("Location: " . BASE_URL . "/login");//view çağrısı// c"est fait
        exit;
    }
}
//gunncellenme tarihi 03/12/2025 22:10 view çağrısı dikett
//gunncellenme tarihi 24/12/2025 view adaptasyonu.

