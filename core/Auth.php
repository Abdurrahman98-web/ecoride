<?php

class Auth
{
    /* ============================================================
       CHECK IF USER IS LOGGED IN
       ============================================================ */
    public static function check()
    {
        return isset($_SESSION["user_id"]);
    }

    /* ============================================================
       REQUIRE LOGIN (GUARD)
       ============================================================ */
    public static function requireLogin()
    {
        if (!self::check()) {
            header("Location: login.php");
            exit;
        }
    }

    /* ============================================================
       LOGIN USER
       ============================================================ */
    public static function login($user)
    {
        $_SESSION["user_id"] = $user["utilisateur_id"];
        $_SESSION["pseudo"]  = $user["pseudo"];
    }

    /* ============================================================
       LOGOUT USER
       ============================================================ */
    public static function logout()
    {
        session_destroy();
        header("Location: login.php");
        exit;
    }

    /* ============================================================
       CHECK USER ROLE
       ============================================================ */
    public static function hasRole($role)
    {
        if (!isset($_SESSION["roles"])) {
            return false;
        }

        return in_array($role, $_SESSION["roles"]);
    }

    /* ============================================================
       REQUIRE ROLE (ADMIN / EMPLOYEE)
       ============================================================ */
    public static function requireRole($role)
    {
        if (!self::hasRole($role)) {
            die("Access denied.");
        }
    }

    /* ============================================================
       LOAD USER ROLES INTO SESSION
       ============================================================ */
    public static function loadRoles($userId)
    {
        $roleModel = new Role();
        $roles = $roleModel->getRole($userId);

        // Convert DB result to simple array
        $_SESSION["roles"] = array_map(function ($r) {
            return $r["libelle"];
        }, $roles);
    }
}
