<?php
require_once __DIR__ . '/../core/Model.php';

class User extends Model {

    public function register($nom, $prenom, $email, $pass, $pseudo) {
        $sql = "INSERT INTO utilisateur 
                (nom, prenom, email, mot_de_passe, pseudo, credit)
                VALUES (?, ?, ?, ?, ?, 20)";
        return $this->query($sql, [$nom, $prenom, $email, $pass, $pseudo]);
    }

    public function login($email) {
        return $this->query("SELECT * FROM utilisateur WHERE email = ?", [$email])->fetch();
    }

    public function isSuspended($id) {
        $sql = "SELECT compte_suspendu FROM utilisateur WHERE utilisateur_id = ?";
        return $this->query($sql, [$id])->fetch()['compte_suspendu'];
    }

    /* ============================
       USER DATA
       ============================ */

    // ID ile kullanıcıyı getir
    public function getUserById($id) {
        $sql = "SELECT * FROM utilisateur WHERE utilisateur_id = ?";
        return $this->query($sql, [$id])->fetch();
    }

    // Profil güncelleme
    public function updateProfile($id, $nom, $prenom, $email, $pseudo
    ) {
        $sql = "UPDATE utilisateur 
                SET nom = ?, prenom = ?, email = ?, pseudo = ?
                WHERE utilisateur_id = ?";
        return $this->query($sql, [$nom, $prenom, $email, $pseudo, $id]);
    }


    /* ============================
       PREFERENCES
       ============================ */

    // Kullanıcı tercihlerini getir
    public function getPreferences($userId) {
        $sql = "SELECT * FROM preferences WHERE utilisateur_id = ?";
        return $this->query($sql, [$userId])->fetch();
    }

    // Kullanıcı tercih güncelleme
    public function updatePreferences($userId, $fumeur, $animal, $autres) {
        $sql = "UPDATE preferences
                SET fumeur = ?, animal = ?, autres = ?
                WHERE utilisateur_id = ?";
        return $this->query($sql, [$fumeur, $animal, $autres, $userId]);
    }
    /* =======================================
   GET USER CREDITS
   ======================================= */
public function getCredits($userId)
{
    $sql = "SELECT credit FROM utilisateur WHERE utilisateur_id = ?";
    $result = $this->query($sql, [$userId])->fetch();
    return $result ? (int)$result['credit'] : 0;
}


/* =======================================
   ADD CREDITS (crédit ekleme)
   ======================================= */
public function addCredits($userId, $amount)
{
    $sql = "UPDATE utilisateur 
            SET credit = credit + ? 
            WHERE utilisateur_id = ?";

    return $this->query($sql, [$amount, $userId]);
}


/* =======================================
   REMOVE CREDITS (crédit düşme)
   ======================================= */
public function removeCredits($userId, $amount): bool|PDOStatement
{
    // Kullanıcıda yeterli kredi var mı?
    $current = $this->getCredits($userId);

    if ($current < $amount) {
        return false; // yetersiz kredi
    }

    $sql = "UPDATE utilisateur 
            SET credit = credit - ? 
            WHERE utilisateur_id = ?";

    return $this->query($sql, [$amount, $userId]);
}
 // =======================================
   //CREDITS PER DAY

public function creditsPerDay($date) {
    $sql = "SELECT SUM(prix_personne) as total_credits
            FROM covoiturage
            WHERE date_depart = ?";
    $result = $this->query($sql, [$date])->fetch();
    return $result ? (float)$result['total_credits'] : 0;

}
// =======================================
   //TOTAL PLATFORM CREDITS
   public function getTotalPlatformCredits() {
    $sql = "SELECT SUM(prix_personne) as total_credits FROM covoiturage";
    $result = $this->query($sql)->fetch();
    return $result ? (float)$result['total_credits'] : 0;
}
    /* ============================
         GET ALL USERS
         ============================ */
     public function getAllUsers() {
          $sql = "SELECT * FROM utilisateur ORDER BY utilisateur_id DESC";
          return $this->query($sql)->fetchAll();

     
}
    /* ============================
         CREATE EMPLOYEE ACCOUNT
         ============================ */
     public function createEmployee($pseudo, $email, $hashedPassword) {
          $sql = "INSERT INTO utilisateur 
                  (pseudo, email, mot_de_passe, role, credit)
                  VALUES (?, ?, ?, 'EMPLOYEE', 0)";
          $this->query($sql, [$pseudo, $email, $hashedPassword]);
          return $this->lastInsertId();
     }
     /* ============================
         SUSPEND USER / EMPLOYEE
         ============================ */
        public function suspendUser($userId) {
            $sql = "UPDATE utilisateur 
                  SET compte_suspendu = 1 
                  WHERE utilisateur_id = ?";
          return $this->query($sql, [$userId]);}
       /* ============================
         ACTIVATE USER / EMPLOYEE
         ============================ */
        public function activateUser($userId) {
            $sql = "UPDATE utilisateur 
                  SET compte_suspendu = 0 
                  WHERE utilisateur_id = ?";
          return $this->query($sql, [$userId]);}   
}
//gunncellenme tarihi 03/12/2025(kredi)/Profil güncelleme(22:42)
// gunncellenme tarihi 23/12/2025