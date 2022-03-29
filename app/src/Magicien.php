<?php
class Magicien extends Personnage
{
   public function lancerUnSort(Personnage $persoAEnvouter) {
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
        
        if ($persoAEnvouter->id == $this->id) {
            return self::CEST_MOI;
        }
        
        if ($this->experience == 0) {
            return self::MAGIE;
        }
        
        // if ($this->endormi()) {
        //     return self::ENDORMI;
        // }
        
        $persoAEnvouter->timeEndormi = time() + ($this->atout * 1) * 60;
        
        return self::PERSONNAGE_ENVOUTE;
    }
}