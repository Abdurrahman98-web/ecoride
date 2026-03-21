<?php
require_once BASE_PATH . '/app/models/Role.php';
class UserController {

    /* ======================================
       PROFIL SAYFASI
       ====================================== */
    public function profile() 
    {
        if (!Session::has('user_id')) {
            header("Location: login.php");
            exit;
        }

        $userId = Session::get('user_id');
        $userModel = new User();
        $roleModel = new Role();

        $user = $userModel->getUserById($userId);
        // get roles for this user (previous code incorrectly called getRole)
        $roles = $roleModel->getRolesByUser($userId);

        require BASE_PATH . '/app/views/user/profile.php';//VIew cağrısı// daha yapilmadi
    }


    /* ======================================
       PROFİL GÜNCELLEME
       ====================================== */
    public function updateProfile()
    {
        if (!Session::has('user_id')) {
            header("Location: login.php");//VIew cağrısı
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: profile.php");//VIew cağrısı
            exit;
        }

        $userId = Session::get('user_id');
        $userModel = new User();

        $userModel->updateProfile(
            $userId,
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $_POST['pseudo']
        );

        header("Location: profile.php?updated=1");//VIew cağrısı
        exit;
    }


    /* ======================================
       ROL SEÇİM SAYFASI
       ====================================== */
    public function chooseRole()
    {
        if (!Session::has('user_id')) {
            header("Location: login.php");
            exit;
        }
        require_once BASE_PATH . '/app/models/Role.php';
         

        $userId = Session::get('user_id');
        $roleModel = new Role();

        $userRoles = $roleModel->getRolesByUser($userId);
        
        require BASE_PATH . '/app/views/user/choose_role.php';//VIew cağrısı daha yapilmadi
    }


    /* ======================================
       ROL SEÇME İŞLEMİ
       ====================================== */
    public function updateRole()
    {
        if (!Session::has('user_id')) {
            header("Location: login.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: choose_role.php");
            exit;
        }

        $userId = Session::get('user_id');
        $roleModel = new Role();

        $selectedRoles = $_POST['roles'] ?? [];

        // önce mevcut rolleri kaldır (tek tek)
        $currentRoles = $roleModel->getRolesByUser($userId);
        foreach ($currentRoles as $r) {
            $roleModel->removeRoleFromUser($userId, $r['role_id']);
        }

        // sonra yeni rolleri ekle
        foreach ($selectedRoles as $roleId) {
            $roleModel->addRoleToUser($userId, (int)$roleId);
        }
        // Eğer kullanıcı artık 'chauffeur' rolüne sahipse → araç ekleme sayfasına yönlendir
        $base = defined('BASE_URL') ? BASE_URL : '/ecoride';

        if ($roleModel->userHasRole($userId, 'chauffeur')) {
            header('Location: ' . $base . '/vehicule/create');
            exit;
        }

        // Aksi halde 'rides' sayfasına yönlendir
        header('Location: ' . $base . '/ride');
        
        exit;
    }
}
//gunncellenme tarihi 04/12/2025 view çağrısı dikett
