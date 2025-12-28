<?php

class Vehicule extends Model {

    /* ============================
       GET VEHICULES
       ============================ */

    // Bir kullanıcının tüm araçlarını getir
    public function getVehiculesByUser($userId) {
        $sql = "SELECT * FROM vehicule WHERE utilisateur_id = ?";
        return $this->query($sql, [$userId])->fetchAll();
    }

    // Tek bir aracı ID ile getir
    public function getVehiculeById($vehiculeId) {
        $sql = "SELECT * FROM vehicule WHERE vehicule_id = ?";
        return $this->query($sql, [$vehiculeId])->fetch();
    }


    /* ============================
       ADD VEHICULE (US 8)
       ============================ */

    public function addVehicule($userId, $marque, $modele, $couleur, $energie, $immatriculation, $datePremiere, $nbPlaces) 
    {
        $sql = "INSERT INTO vehicule (utilisateur_id, marque, modele, couleur, energie, immatriculation, date_premiere_immatriculation, nb_places)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->query($sql, [
            $userId,
            $marque,
            $modele,
            $couleur,
            $energie,
            $immatriculation,
            $datePremiere,
            $nbPlaces
        ]);
    }


    /* ============================
       UPDATE VEHICULE
       ============================ */

    public function updateVehicule($vehiculeId, $marque, $modele, $couleur, $energie, $immatriculation, $datePremiere, $nbPlaces) 
    {
        $sql = "UPDATE vehicule
                SET marque = ?, modele = ?, couleur = ?, energie = ?, 
                    immatriculation = ?, date_premiere_immatriculation = ?, 
                    nb_places = ?
                WHERE vehicule_id = ?";

        return $this->query($sql, [
            $marque,
            $modele,
            $couleur,
            $energie,
            $immatriculation,
            $datePremiere,
            $nbPlaces,
            $vehiculeId
        ]);
    }


    /* ============================
       DELETE VEHICULE
       ============================ */

    public function deleteVehicule($vehiculeId) {
        $sql = "DELETE FROM vehicule WHERE vehicule_id = ?";
        return $this->query($sql, [$vehiculeId]);
    }


    /* ============================
       VEHICULE UTILITIES
       ============================ */

    // Araç elektrikli mi? (ekolojik yolculuk)
    public function isElectric($vehiculeId) {
        $sql = "SELECT energie FROM vehicule WHERE vehicule_id = ?";
        $energie = $this->query($sql, [$vehiculeId])->fetch()['energie'];

        return strtolower(trim($energie)) === "electrique";
    }
}
//end of Vehicule class at 02/12/2025 10:54;
