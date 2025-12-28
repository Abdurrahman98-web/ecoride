<?php

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
        $roles = $roleModel->getRole($userId);

        require 'views/user/profile.php';//VIew cağrısı
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

        $userId = Session::get('user_id');
        $roleModel = new Role();

        $userRoles = $roleModel->getRolesByUser($userId);

        require 'views/user/choose_role.php';//VIew cağrısı
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

        // önce eski rolleri sil
        $roleModel->removeRoleFromUser($userId,$selectedRoles);

        // sonra yeni rolleri ekle
        foreach ($selectedRoles as $roleId) {
            $roleModel->addRoleToUser($userId, $roleId);
        }

        // Eğer chauffeur seçildi ise → zorunlu alanlar doldurulmalı
        if (in_array(Role::ROLE_CHAUFFEUR, $selectedRoles)) {
            header("Location: vehicule_add.php");//VIew cağrısı
            exit;
        }

        header("Location: espace.php");//VIew cağrısı
        exit;
    }
}
//gunncellenme tarihi 04/12/2025 view çağrısı dikett
