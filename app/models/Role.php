<?php

class Role extends Model {const ROLE_ADMIN = "1";
      const ROLE_EMPLOYE = "2";
     const ROLE_CHAUFFEUR = "3";
      const ROLE_PASSAGER= "4";
      const ROLE_CHAUFFEUR_PASSAGER = "5";

    /* ============================
       Bir kullanıcının rollerini getir
       ============================ */
    public function getRolesByUser($userId) {
        $sql = "SELECT r.role_id, r.libelle
                FROM role r
                JOIN utilisateur_role ur ON ur.role_id = r.role_id
                WHERE ur.utilisateur_id = ?";
                
        return $this->query($sql, [$userId])->fetchAll();
    }


    /* ============================
       Kullanıcıya rol ekle
       ============================ */
    public function addRoleToUser($userId, $roleId) {

        // Zaten var mı kontrol et
        $sqlCheck = "SELECT * FROM utilisateur_role 
                     WHERE utilisateur_id = ? AND role_id = ?";
        $exists = $this->query($sqlCheck, [$userId, $roleId])->fetch();

        if ($exists) {
            return false; // Zaten bu rol atanmış
        }

        $sql = "INSERT INTO utilisateur_role (utilisateur_id, role_id)
                VALUES (?, ?)";
                
        return $this->query($sql, [$userId, $roleId]);
    }


    /* ============================
       Kullanıcıdan rol kaldır
       ============================ */
    public function removeRoleFromUser($userId, $roleId) {
        $sql = "DELETE FROM utilisateur_role
                WHERE utilisateur_id = ? AND role_id = ?";

        return $this->query($sql, [$userId, $roleId]);
    }


    /* ============================
       Tüm rolleri getir
       ============================ */
    public function getAllRoles() {
        $sql = "SELECT * FROM role ORDER BY role_id ASC";
        return $this->query($sql)->fetchAll();
    }


    /* ============================
       Rol detayını getir
       ============================ */
    public function getRole($roleId) {
        $sql = "SELECT * FROM role WHERE role_id = ?";
        return $this->query($sql, [$roleId])->fetch();
    }


    /* ============================
       Kullanıcı belirli bir role sahip mi?
       ============================ */
    public function userHasRole($userId, $roleName) {
        $sql = "SELECT r.libelle
                FROM role r
                JOIN utilisateur_role ur ON ur.role_id = r.role_id
                WHERE ur.utilisateur_id = ? AND r.libelle = ?";

        $result = $this->query($sql, [$userId, $roleName])->fetch();

        return $result ? true : false;
    }
}

