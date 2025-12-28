<?php

class Preference extends Model {

    /* =============================
       1 — Kullanıcının ana tercihlerini getir
       ============================= */
    public function getByUser($userId)
    {
        $sql = "SELECT * FROM preference WHERE utilisateur_id = ?";
        return $this->query($sql, [$userId])->fetch();
    }


    /* =============================
       2 — Ana tercih oluştur
       Yoksa create, varsa update
       ============================= */
    public function save($userId, $fumeur, $animal, $notes = null)
    {
        $existing = $this->getByUser($userId);

        if ($existing) {
            return $this->update($userId, $fumeur, $animal, $notes);
        }

        return $this->create($userId, $fumeur, $animal, $notes);
    }


    public function create($userId, $fumeur, $animal, $notes = null)
    {
        $sql = "INSERT INTO preference (utilisateur_id, fumeur, animal, notes)
                VALUES (?, ?, ?, ?)";

        return $this->query($sql, [$userId, $fumeur, $animal, $notes]);
    }


    /* =============================
       3 — Ana tercih güncelleme
       ============================= */
    public function update($userId, $fumeur, $animal, $notes = null)
    {
        $sql = "UPDATE preference
                SET fumeur = ?, animal = ?, notes = ?
                WHERE utilisateur_id = ?";

        return $this->query($sql, [$fumeur, $animal, $notes, $userId]);
    }


    /* =============================
       4 — chuffeur preference EKLEME
       (kullanıcının özel tercihleri)
       ============================= */
    public function addChuffeurPreference($userId, $text)
    {
        $sql = "INSERT INTO preference_chuffeur (utilisateur_id, texte)
                VALUES (?, ?)";

        return $this->query($sql, [$userId, $text]);
    }


    /* =============================
       5 — chuffeur preference LİSTELEME
       ============================= */
    public function getchuffeurPreferences($userId)
    {
        $sql = "SELECT * FROM preference_chuffeur
                WHERE utilisateur_id = ?";

        return $this->query($sql, [$userId])->fetchAll();
    }


    /* =============================
       6 — chuffeur preference SİLME
       ============================= */
    public function deleteChuffeurPreference($prefId)
    {
        $sql = "DELETE FROM preference_chuffeur
                WHERE preference_chuffeur_Id = ?";

        return $this->query($sql, [$prefId]);
    }


    /* =============================
       7 — Kullanıcının tercihi var mı?
       ============================= */
    public function hasPreferences($userId)
    {
        return $this->getByUser($userId) ? true : false;
    }
}

// buran devan 03/12/2025 insallah 
// yukrddaki kod  03/12/2025 tarihinde eklenndi  
