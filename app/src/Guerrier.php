<?php
class Guerrier extends Personnage {

    function recevoirUnCoup() {
        if ($this->degats >= 0 && $this->degats <= 25) {
            $this->experience = 4;
        } elseif ($this->degats > 25 && $this->degats <= 50) {
            $this->experience = 3;
        } elseif ($this->degats > 50 && $this->degats <= 75) {
            $this->experience = 2;
        } elseif ($this->degats > 75 && $this->degats <= 90) {
            $this->experience = 1;
        } else {
            $this->experience = 0;
        }
        
        $this->degats += 5 - $this->experience;
        if ($this->degats >= 100) {
            return self::PERSO_MORT;
        }
        return self::PERSONNAGE_FRAPPE;
    }
        
}
