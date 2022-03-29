<?php
class PersonnagesRepository
{
  private $_db; 
  
  public function __construct($db)
  {
    $this->setDb($db);
  }
  
  public function add(Personnage $perso)
  {
    
      $req = $this->bdd->prepare('INSERT INTO Personnages_v2
       SET nom    = :nom,
      type   = :type
       ');        
    $req->execute();
    
    $perso->hydrate([
      'id' => $this->_db->lastInsertId(),
      'degats' => 0,
      'atout'  => 0
    ]);
    
  }
  
  public function countPersonnages()
  {
    return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
  }
  
  public function deletePersonnages(Personnage $perso)
  {
    $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->getId());
  }
  
  public function exists($info)
  {
    if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
    {
      return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
    }
    
    // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.
    
    $req = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
    $req->execute([':nom' => $info]);
    
    return (bool) $req->fetchColumn();
  }
  
  public function get($info)
  {
    if (is_int($info))
    {
      $req = $this->_db->query('SELECT id, nom, degats, experience, niveau , forcePerso FROM personnages WHERE id = '.$info);
      $donnees = $req->fetch(PDO::FETCH_ASSOC);
      
      return new Personnage($donnees);
    }
    else
    {
      $req = $this->_db->prepare('SELECT id, nom, degats, experience,niveau, forcePerso FROM personnages WHERE nom = :nom');
      $req->execute([':nom' => $info]);
    
      return new Personnage($req->fetch(PDO::FETCH_ASSOC));
    }
  //   switch ($donneesPerso['type']) {
  //     case 'guerrier' : return new Guerrier($donneesPerso);
  //     case 'magicien' : return new Magicien($donneesPerso);
  //     default : return null;
  // }
  }
  
  public function getList($nom)
  {
    $perso = [];
    
    $req = $this->_db->prepare('SELECT id, nom, degats ,experience , niveau, forcePerso FROM personnages WHERE nom <> :nom ORDER BY nom');
    $req->execute([':nom' => $nom]);
    
    while ($donnees = $req->fetch(PDO::FETCH_ASSOC))
    {
      $perso[] = new Personnage($donnees);
    }
    
    return $perso;
  }
  
  public function update(Personnage $perso)
  {
    $req = $this->_db->prepare('UPDATE personnages_v2 SET degats = :degats, endormi = :endormi, experience = :experience  WHERE id = :id');
    
    $req->bindValue(':degats', $perso->getDegats(), PDO::PARAM_INT);
    $req->bindValue(':id', $perso->getId(), PDO::PARAM_INT);
    $req->bindValue(':endormi', $perso->getEndormi(), PDO::PARAM_INT);
    $req->bindValue(':experience', $perso->getExperience(),PDO::PARAM_INT );
    
    $req->execute();
  }
  
  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
}