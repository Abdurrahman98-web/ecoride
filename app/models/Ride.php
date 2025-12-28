<?php

class Ride extends Model {

    /* ============================
       1-LOG SYSTEM
       ============================ */

    private function writeLog($message) {
        $date = date("Y-m-d H:i:s");
          file_put_contents(filename: __DIR__ . "/../../logs/ride.log", data: "[$date] $message" . PHP_EOL, flags: FILE_APPEND);
        //file_put_contents("Ride.log", "[$date] $message" . PHP_EOL, FILE_APPEND);
    }


    /* ============================
       2-ADD RIDE
       ============================ */
    public function addRide($chauffeurId, $vehiculeId, $lieuDepart, $lieuArrivee,
                            $dateDepart, $heureDepart, $dateArrivee, $heureArrivee,
                            $nbPlaces, $prix, $ecologique) {

        $vehiculeModel = new Vehicule();
        $ecologique = $vehiculeModel->isElectric($vehiculeId) ? 1 : 0;

        $sql = "INSERT INTO covoiturage
                (chauffeur_id, voiture_id, lieu_depart, lieu_arrivee,
                date_depart, heure_depart, date_arrivee, heure_arrivee,
                nb_places,prix_personne, ecologique)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $this->query($sql, [
            $chauffeurId, $vehiculeId, $lieuDepart, $lieuArrivee,
            $dateDepart, $heureDepart, $dateArrivee, $heureArrivee,
            $nbPlaces, $prix, $ecologique
        ]);

        // Son eklenen ride ID'sini al
        $rideId = $this->lastInsertId();

        // LOG
        $this->writeLog("Ride #$rideId created by user #$chauffeurId");

        return $rideId;
    }


    /* ============================
      3- SEARCH RIDES
       ============================ */
    public function searchRides($lieuDepart, $lieuArrivee, $date) {
        $sql = "SELECT c.*, u.pseudo, v.modele, v.energie
                FROM covoiturage c
                JOIN utilisateur u ON c.chauffeur_id = u.utilisateur_id
                JOIN vehicule v ON c.voiture_id = v.vehicule_id
                WHERE c.lieu_depart = ? AND c.lieu_arrivee = ? 
                AND c.date_depart = ? AND c.nb_places > 0";

        return $this->query($sql, [$lieuDepart, $lieuArrivee, $date])->fetchAll();
    }


    /* ============================
      4- GET RIDE DETAILS
       ============================ */
    public function getRideDetails($rideId) {
        $sql = "SELECT c.*, u.pseudo, u.email, v.modele, v.marque, v.energie
                FROM covoiturage c
                JOIN utilisateur u ON c.chauffeur_id = u.utilisateur_id
                JOIN vehicule v ON c.voiture_id = v.vehicule_id
                WHERE c.covoiturage_id = ?";
                $chauffeur = $this->query($sql, [$rideId])->fetch();

        return $chauffeur;
    }


    /* ============================
      5- JOIN RIDE
       ============================ */
    public function joinRide($rideId, $userId, $creditRequired) {

        // participation ekle
        $sql = "INSERT INTO participation (utilisateur_id, covoiturage_id, statut_participation, credit_utilise)
                VALUES (?, ?, 'confirme', ?)";
        $this->query($sql, [$userId, $rideId, $creditRequired]);

        // koltuğu 1 azalt
        $sql2 = "UPDATE covoiturage SET nb_places = nb_places - 1 WHERE covoiturage_id = ?";
        $this->query($sql2, [$rideId]);

        // kredi düş
        $userModel = new User();
        //$user = $userModel->getUserById($userId);
        $userModel->removeCredits($userId, $creditRequired);

        // LOG
        $this->writeLog("User #$userId joined ride #$rideId using $creditRequired credits");

        return true;
    }


    /* ============================
       START RIDE
       ============================ */
    public function startRide($rideId, $chauffeurId) {
        $sql = "UPDATE covoiturage SET statut = 'en_cours'
                WHERE covoiturage_id = ? AND chauffeur_id = ?";

        $this->query($sql, [$rideId, $chauffeurId]);

        // LOG
        $this->writeLog("Ride #$rideId started by chauffeur #$chauffeurId");

        return true;
    }


    /* ============================
       END RIDE
       ============================ */
    public function endRide($rideId, $chauffeurId) {
    // 1) Ride bitti
    $sql = "UPDATE covoiturage 
            SET statut = 'termine'
            WHERE covoiturage_id = ? AND chauffeur_id = ?";
    $this->query($sql, [$rideId, $chauffeurId]);

    // 2) Tüm yolcuları "avis bekleniyor" durumuna al
    $sql2 = "UPDATE participation
             SET statut_participation = 'validation_pending'
             WHERE covoiturage_id = ?";
    $this->query($sql2, [$rideId]);

    // 3) LOG
    $this->writeLog("Ride #$rideId ended by chauffeur #$chauffeurId — awaiting avis validation");

    return true;
  }
   public function validateParticipation($rideId, $userId) {
    $sql = "UPDATE participation 
            SET statut_participation = 'valide'
            WHERE covoiturage_id = ? AND utilisateur_id = ?";

    return $this->query($sql, [$rideId, $userId]);
  }
  public function reportProblem($rideId, $userId, $commentaire) {

    // Veritabanına işaretle
    $sql = "UPDATE participation
            SET statut_participation = 'probleme', commentaire = ?
            WHERE covoiturage_id = ? AND utilisateur_id = ?";
    $this->query($sql, [$commentaire, $rideId, $userId]);

    // Log dosyasına kaydet
    $this->writeLog("User #$userId reported a problem for ride #$rideId : $commentaire");

    return true;
   }
   // ============================
      //12 GET PROBLEMATIC RIDES

    //    ============================ */
   public function getProblematicRides() {
    $sql = "SELECT p.*, c.lieu_depart, c.lieu_arrivee
            FROM participation p
            JOIN covoiturage c ON p.covoiturage_id = c.covoiturage_id
            WHERE p.statut_participation = 'probleme'";
    return $this->query($sql)->fetchAll();
  }

    /* ============================
       CANCEL RIDE
       ============================ */
   public function cancelRide($rideId, $userId) {

    // koltuk geri ekle
    $sql1 = "UPDATE covoiturage c
             JOIN participation p ON p.covoiturage_id = c.covoiturage_id
             SET c.nb_places = c.nb_places + 1
             WHERE p.utilisateur_id = ? AND c.covoiturage_id = ?";
    $ok1 = $this->query($sql1, [$userId, $rideId]);

    // participation sil
    $sql2 = "DELETE FROM participation WHERE utilisateur_id = ? AND covoiturage_id = ?";
    $ok2 = $this->query($sql2, [$userId, $rideId]);


    // BAŞARILIYSA:
    if ($ok1 && $ok2) {

        // LOG BURADA ÇALIŞIR
        $this->writeLog("User #$userId cancelled participation for ride #$rideId");

        return true;
    }

    // BAŞARISIZSA:
    return false;
 }
    /* ============================
       R)  REDUCE SEAT
         
     ============================ */
  public function reduceSeat($rideId) {
    $sql = "UPDATE covoiturage
            SET nb_places = nb_places - 1
            WHERE covoiturage_id = ? AND nb_places > 0";
    return $this->query($sql, [$rideId]);
    }
    //* ============================
    //  RA)  SEAT
       
   // public function addSeat($rideId) {
    //$sql = "UPDATE covoiturage
      //      SET nb_places = nb_places + 1
        //    WHERE covoiturage_id = ?";
    //return $this->query($sql, [$rideId]);
    //}

 /* ============================
       UPCOMING RIDES
       ============================ */
  public function upcomingRides($limit = 5) {
    $today = date("Y-m-d H:i:s");
    $sql = "SELECT * FROM covoiturage
            WHERE CONCAT(date_depart,' ',heure_depart) >= ?
            ORDER BY date_depart ASC, heure_depart ASC
            LIMIT ?";
    return $this->query($sql, [$today, $limit])->fetchAll();
 }
 /* ============================
     10  NEXT AVAILABLE DATE
       ============================ */
  public function getNextAvailableDate($villeDepart, $villeArrivee, $dateRecherche)
 {
    $sql = "SELECT date_depart
            FROM covoiturage
            WHERE lieu_depart = ?
            AND lieu_arrivee = ?
            AND date_depart > ?
            AND nb_places > 0
            ORDER BY date_depart ASC
            LIMIT 1";

    return $this->query($sql, [
        $villeDepart,
        $villeArrivee,
        $dateRecherche
    ])->fetch();

 }
    /* ============================
        11) GET USER RIDE HISTORY
         ============================ */

   public function getUserRides($userId){
        $sql = "SELECT * FROM covoiturage
                WHERE chauffeur_id = ?
                ORDER BY date_depart DESC, heure_depart DESC";

        $asDriver =  $this->query($sql, [$userId])->fetchAll();
        return  $asDriver;

    }
    // 13) counttotalrides
        public function countRidesPerDay($date){
        $sql = "SELECT COUNT(*) as total FROM covoiturage
                WHERE date_depart = ?";
        $result = $this->query($sql, [$date])->fetch();
        return $result ? $result['total'] : 0;
        }
    

}
// End of Ride class before 02/12/2025
//updated.....(10"" added AND filtiers deleted)) 06:15/Same day.
//for next available function:modification in joinridde(5) function// its bad idea "." 
// R(need),RA(not need)adde  aat 12/12/25 in18:14 
//"11" added at 12/12/25 09:51
//"12" added at 18/12/25 11;04

