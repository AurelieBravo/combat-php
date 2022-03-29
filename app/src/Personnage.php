<?php
abstract class Personnage
{
  private $_id,
          $_nom,
          $_forcePerso,
          $_degats,
          $_niveau,
          $_experience,
          $_endormi,
          $_reveille,
          $_type;
          
          
  
  const CEST_MOI = 1; 
  const PERSONNAGE_TUE = 2; 
  const PERSONNAGE_FRAPPE = 3; 
  const PERSONNAGE_ENVOUTE = 4; 
  const MAGIE =5;
  const PERSO_MORT =6;
  const PERSO_ENDORMI =7;
  
  
  
  public function __construct(array $donnees )
  {
    $this->hydrate($donnees);
    $this->type = strtolower(static::class);
    
  }
  
  public function nomValide()
  {
    return !empty($this->_nom);
  }
  
  public function frapper(Personnage $perso)
  {
    if ($perso->getId() == $this->_id)
    {
      return self::CEST_MOI;
    }
    
    
    return $perso->recevoirDegats();
  }
  
  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value)
    {
      $method = 'set'.ucfirst($key);
      
      if (method_exists($this, $method))
      {
        $this->$method($value);
      }
    }
  }
  
  
    public function recevoirDegats()
    {
      $this->_degats += 5;
      
      
      if ($this->_degats >= 100)
      {
        return self::PERSONNAGE_TUE;
      }
      
      
      return self::PERSONNAGE_FRAPPE;
    }


    public function validNom()
     {
      return !empty($this->nom);
    }



  
  // GETTERS //
  

  public function getType()
  {
    return $this->_type;
  }

  public function getReveille()
  {
    return $this->_reveille;
    date_default_timezone_set('Europe/Paris');
$date = date('d-m-y h:i:s');
echo $date;
  }

  public function getEndormi()
  {
    return $this->_endormi;
  }
  
  public function getId() {
    return $this->_id;
}
  
  public function getNom()
  {
    return $this->_nom;
  }

  public function getDegats()
  {
    return $this->_degats;
  }

  public function getForcePerso()
  {
    return $this->_forcePerso;
  }

  public function getNiveau()
  {
    return $this->_niveau;
  }

  public function getExperience()
  {
    return $this->_experience;
  }
  
 //setter//
 

 public function setType($type)
 {
   $this->_type = $type;
 }

 public function setReveille($reveille)
 {
   $this->_reveille = $reveille;
   date_default_timezone_set('Europe/Paris');
  $date = date('d-m-y h:i:s');
  echo $date;
}
 
 public function setEndormi($endormi)
 {
   $endormi = (bool) $endormi;
   $this->_endormi = $endormi;
 }
  
  public function setDegats($degats)
  {
    $degats = (int) $degats;
    
    if ($degats >= 0 && $degats <= 100)
    {
      $this->_degats = $degats;
    }
  }
  
  public function setId($id)
  {
    $id = (int) $id;
    
    if ($id > 0)
    {
      $this->_id = $id;
    }
  }
  
  public function setNom($nom)
  {
    if (is_string($nom))
    {
      $this->_nom = $nom;
    }
  }

  public function setForcePerso($forcePerso)
  {
    $forcePerso = (int) $forcePerso;
    if ($forcePerso >1 && $forcePerso <= 50)
    {
      $this->_forcePerso = $forcePerso;
    }
  }

  public function setNiveau($niveau)
  {
    $niveau = (int) $niveau;
    if ($niveau >1 && $niveau <=100)
    {
      $this->_niveau = $niveau;
    }
  }

  public function setExperience($experience)
  {
    $experience = (int) $experience;
    if ($experience >1 && $experience <=100)
    {
      $this->_experience = $experience;
    }
  }
}