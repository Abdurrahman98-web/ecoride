<?php

class Avis extends Model {

    /* ============================
       PASSAGER → Avis ekleme
       ============================ */
    public function addAvis($chauffeurId, $passagerId, $rideId, $note, $commentaire) {
        $sql = "INSERT INTO avis 
                (chauffeur_id, passager_id, covoiturage_id, note, commentaire, date_avis, valide)
                VALUES (?, ?, ?, ?, ?, NOW(), 0)";

        return $this->query($sql, [
            $chauffeurId,
            $passagerId,
            $rideId,
            $note,
            $commentaire
        ]);
    }


    /* ============================
       Chauffeur → Avis listeleme
       ============================ */
    public function getAvisByChauffeur($chauffeurId) {
        $sql = "SELECT a.*, u.pseudo AS auteur
                FROM avis a
                JOIN utilisateur u ON u.utilisateur_id = a.passager_id
                WHERE a.chauffeur_id = ? AND a.valide = 1
                ORDER BY a.date_avis DESC";

        return $this->query($sql, [$chauffeurId])->fetchAll();
    }
     public function getAvisByRide($rideId) {
        $sql = "SELECT a.*, u.pseudo AS auteur
                FROM avis a
                JOIN utilisateur u ON u.utilisateur_id = a.passager_id
                WHERE a.covoiturage_id = ? AND a.valide = 1
                ORDER BY a.date_avis DESC";

        return $this->query($sql, [$rideId])->fetchAll();
    }


    /* ============================
       Chauffeur → Ortalama Not
       ============================ */
    public function getMoyenneNotes($chauffeurId) {
        $sql = "SELECT AVG(note) AS moyenne
                FROM avis 
                WHERE chauffeur_id = ? AND valide = 1";

        $result = $this->query($sql, [$chauffeurId])->fetch();
        return $result["moyenne"] ?? 0;
    }
    /* ============================
        ++Get Chauffeur ID by Ride ID
       ============================ */
    public function getChauffeurIdByRide($rideId) {
        $sql = "SELECT chauffeur_id FROM covoiturage WHERE covoiturage_id = ? LIMIT 1";
        $result = $this->query($sql, [$rideId])->fetch();
        return $result['chauffeur_id'] ?? null;
    }

    /* ============================
       Employe → Onaysız yorumlar
       ============================ */
    public function getAvisNonValides() {
        $sql = "SELECT a.*, 
                       c.lieu_depart, c.lieu_arrivee, 
                       u1.pseudo AS chauffeur,
                       u2.pseudo AS passager
                FROM avis a
                JOIN covoiturage c ON c.covoiturage_id = a.covoiturage_id
                JOIN utilisateur u1 ON u1.utilisateur_id = a.chauffeur_id
                JOIN utilisateur u2 ON u2.utilisateur_id = a.passager_id
                WHERE a.valide = 0";

        return $this->query($sql)->fetchAll();
    }


    /* ============================
       Employe → Avis onaylama
       ============================ */
    public function validerAvis($avisId) {
        $sql = "UPDATE avis SET valide = 1 WHERE avis_id = ?";
        return $this->query($sql, [$avisId]);
    }


    /* ============================
       Employe → Avis reddetme
       ============================ */
    public function refuserAvis($avisId) {
        $sql = "DELETE FROM avis WHERE avis_id = ?";
        return $this->query($sql, [$avisId]);
    }
    /* =============================
       7 — Bir ride'a yorum bırakıldı mı? (duplicated yorum önleme)
       ============================= */
    public function alreadyPosted($userId, $rideId)
    {
        $sql = "SELECT * FROM avis 
                WHERE utilisateur_id = ? AND covoiturage_id = ?";

        return $this->query($sql, [$userId, $rideId])->fetch();
    }

}
//avis class sonu 02/12/2025 onnceki bir tarihte eklendi
//guncellendi(7 eklendi) 02/12/2025 04:56.by A.A
// ecoride/controllers/ReviewController.php (connection successful) at 10/12/2025 08:11
