<?php

class Participation extends Model {

    /* =============================
       1 — Kullanıcı ride’a katılmış mı? 
       (çift katılımı engellemek için)
       ============================= */
    public function hasJoined($userId, $rideId)
    {
        $sql = "SELECT * FROM participation 
                WHERE utilisateur_id = ? AND covoiturage_id = ?";

        return $this->query($sql, [$userId, $rideId])->fetch();
    }


    /* =============================
       2 — Katılım ekleme (US 6)
       ============================= */
    public function addParticipation($userId, $rideId)
    {
        // Aynı ride’a 2 kez katılmasın
        if ($this->hasJoined($userId, $rideId)) {
            return false;
        }

        $sql = "INSERT INTO participation (utilisateur_id, covoiturage_id, date_participation)
                VALUES (?, ?, NOW())";

        return $this->query($sql, [$userId, $rideId]);
    }


    /* =============================
       3 — Kullanıcının tüm ride katılımları (US 10)
       ============================= */
    public function getParticipationsByUser($userId)
    {
        $sql = "SELECT p.*, 
                       c.lieu_depart, c.lieu_arrivee,
                       c.date_depart, c.heure_depart,
                       c.prix, c.nb_places,
                       u.pseudo AS chauffeur
                FROM participation p
                JOIN covoiturage c ON c.covoiturage_id = p.covoiturage_id
                JOIN utilisateur u ON u.utilisateur_id = c.utilisateur_id
                WHERE p.utilisateur_id = ?
                ORDER BY c.date_depart DESC";

        return $this->query($sql, [$userId])->fetchAll();
    }


    /* =============================
       4 — Kullanıcı ride'a katılımını iptal eder (US 10)
       ============================= */
    public function cancelParticipation($userId, $rideId)
    {
        $sql = "DELETE FROM participation 
                WHERE utilisateur_id = ? AND covoiturage_id = ?";

        return $this->query($sql, [$userId, $rideId]);
    }


    /* =============================
       5 — Bir ride'a kimler katılmış (detay sayfası)
       ============================= */
    public function getParticipants($rideId)
    {
        $sql = "SELECT p.*, u.pseudo, u.photo
                FROM participation p
                JOIN utilisateur u ON u.utilisateur_id = p.utilisateur_id
                WHERE p.covoiturage_id = ?";

        return $this->query($sql, [$rideId])->fetchAll();
    }


    /* =============================
       6 — Bir kullanıcının ride geçmişi (passager görünümü)
       ============================= */
    public function getHistory($userId)
    {
        $sql = "SELECT p.*, c.*, 
                       u.pseudo AS chauffeur
                FROM participation p
                JOIN covoiturage c ON c.covoiturage_id = p.covoiturage_id
                JOIN utilisateur u ON u.utilisateur_id = c.utilisateur_id
                WHERE p.utilisateur_id = ?
                ORDER BY c.date_depart DESC";
                $asPassenger = $this->query($sql, [$userId])->fetchAll();

        return $asPassenger;
    }
}
// EOF in 02/12/2025 10:38
//modifications in getHistory method(function).