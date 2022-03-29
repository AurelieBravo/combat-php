<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeux de combat</title>
    <link rel="stylesheet" href="styles.css">
    
</head>

<body>
 
  <form action="" method="post">
    <p>
    <section class="row col-sm-12">
                    <form class="form-horizontal" method="post">
                        <!-- Champ de saisie texte une ligne -->
                        <div class="form-group form-group-lg">
                            <label for="personnageNom" class="col-xs-12 col-sm-4 col-md-3 control-label">Nom du premier personnage : </label>
                            <div class="col-xs-12 col-sm-8 col-md-9 focus"> 
                                <input class="form-control input-lg" type="text" name="personnageNom" id="prenom" placeholder="Nom du personnage" autofocus required />
                            </div>
                            
                        </div>
                        <div class="form-group form-group-lg">
                            <label for="personnageType" class="col-xs-12 col-sm-4 col-md-3 control-label">Type du personnage : </label>
                            <div class="col-xs-12 col-sm-8 col-md-9">
                                <select class="form-control input-lg" name="personnageType">
                                    <option value="magicien">Magicien</option>
                                    <option value="guerrier">Guerrier</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-default btn-lg pull-right" value="Créer le personnage" name="creer">Créer le personnage</button>
                        <button type="submit" class="btn btn-default btn-lg pull-right" value="Utiliser le personnage" name="utiliser">Utiliser le personnage</button></br>
                    </form>
                </section>
  </form>
</body>

</html>
<?php
// On enregistre notre autoload.
function chargerClasse($classname)
{
  require $classname.'.php';
}
// require 'PersonnageRepository.php';

spl_autoload_register('chargerClasse');

session_start(); 


if (isset($_GET['deconnexion']))
{
  session_destroy();
  header('Location: .');
  exit();
}

$db = new PDO('mysql:host=localhost;dbname=jeuxcombat11', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$repository = new PersonnagesRepository($db);

if (isset($_SESSION['perso'])) 
{
  $perso = $_SESSION['perso'];
}

if (isset($_POST['creer']) && isset($_POST['nom'])) 
{
  $perso = new Personnage (['nom' => $_POST['nom']]); 
  
  if (!$perso->nomValide())
  {
    $message = 'Le nom choisi est invalide.';
    unset($perso);
  }
  elseif ($repository->exists($perso->getNom()))
  {
    $message = 'Le nom du personnage est déjà pris.';
    unset($perso);
  }
  else
  {
    $repository->add($perso);
  }
}

elseif (isset($_POST['utiliser']) && isset($_POST['nom'])) // Si on a voulu utiliser un personnage.
{
  if ($repository->exists($_POST['nom'])) // Si celui-ci existe.
  {
    $perso = $repository->get($_POST['nom']);
  }
  else
  {
    $message = 'Ce personnage n\'existe pas !'; // S'il n'existe pas, on affichera ce message.
  }
}

elseif (isset($_GET['frapper'])) // Si on a cliqué sur un personnage pour le frapper.
{
  if (!isset($perso))
  {
    $message = 'Merci de créer un personnage ou de vous identifier.';
  }
  
  else
  {
    if (!$repository->exists((int) $_GET['frapper']))
    {
      $message = 'Le personnage que vous voulez frapper n\'existe pas !';
    }
    
    else
    {
      $persoAFrapper = $repository->get((int) $_GET['frapper']);
      
      $retour = $perso->frapper($persoAFrapper); // On stocke dans $retour les éventuelles erreurs ou messages que renvoie la méthode frapper.
      
      switch ($retour)
      {
        case Personnage::CEST_MOI :
          $message = 'Mais... pourquoi voulez-vous vous frapper ???';
          break;
        
        case Personnage::PERSONNAGE_FRAPPE :
          $message = 'Le personnage a bien été frappé !';
          
          $repository->update($perso);
          $repository->update($persoAFrapper);
          
          break;
        
        case Personnage::PERSONNAGE_TUE :
          $message = 'Vous avez tué ce personnage !';
          
          $repository->update($perso);
          $repository->deletePersonnages($persoAFrapper);
          
          break;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>TP : Mini jeu de combat</title>
    
    <meta charset="utf-8" />
  </head>
  <body>
  <section class="row col-sm-12">
                    <form class="form-horizontal" method="post">
                        
                        <div class="form-group form-group-lg">
                            <label for="personnageNom" class="col-xs-12 col-sm-4 col-md-3 control-label">Nom du deuxieme personnage : </label>
                            <div class="col-xs-12 col-sm-8 col-md-9 focus"> 
                                <input class="form-control input-lg" type="text" name="personnageNom" id="prenom" placeholder="Nom du personnage" autofocus required />
                            </div>
                            
                        </div>
                        <div class="form-group form-group-lg">
                            <label for="personnageType" class="col-xs-12 col-sm-4 col-md-3 control-label">Type du personnage : </label>
                            <div class="col-xs-12 col-sm-8 col-md-9">
                                <select class="form-control input-lg" name="personnageType">
                                    <option value="magicien">Magicien</option>
                                    <option value="guerrier">Guerrier</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-default btn-lg pull-right" value="Créer le personnage" name="creer">Créer le personnage</button>
                        <button type="submit" class="btn btn-default btn-lg pull-right" value="Utiliser le personnage" name="utiliser">Utiliser le personnage</button>
                    </form>


                    <section id="infos" class="row col-sm-12">
                <p>Nombre de personnage créés : <?php $repository->countPersonnages() ?></p>
                <p>
                    <?php
                        if (isset($message)) {  // Si message à afficher
                          echo '<p>', $message, '</p>';     // on affiche le message
                        }
                    ?>
                </p>
            </section>
                </section>

        <!-- Section Personnage
        ================================================== -->
        <?php
            // Si utilisation d'un personnage
            if (isset($perso)) {
            ?>
                <div class="row col-sm-12"><a class="btn btn-default btn-lg pull-right" href="?deconnexion=1" role="button">Déconnexion</a></div>
                <section class="row col-sm-12">
                    <fieldset>
                        <legend>Mes informations</legend>
                        <p>
                            Nom : <?= htmlspecialchars($perso->getNom()) ?><br>
                            Dégâts : <?= $perso->getDegats() ?><br>
                            Type : <?= ucfirst($perso->getType()) ?><br>
                            <?php
                            
            }elseif 
                               (isset($_GET['envouter']))
                             

    if (!isset($perso))
    {
        $message = 'Merci de créer une personnage ou de vous identifier';
    }
    
    else
    {
        //si personnage est un Magicien
        if ($perso->getType() != 'magicien')
        {
            $message = 'Vous n\êtes pas un magicien...Vous ne pouvez pas envouter un adversaire';
        }
        
        else
        {
            if (!$manager->ifPersonnageExist((int) $_GET['envouter']))
            {
                $message = 'Le personnage que vous voulez envouter n\existe pas';
            }
            
          else
            {
                $persoAEnvouter = $manager->getPersonnage((int) $_GET['envouter']);
                $retour = $perso->lancerUnSort($persoAEnvouter);
                
                switch ($retour)
                {
                    case Personnage::CEST_MOI :
                        $message = 'Stupid idiot...Je ne peux m\'envouter';
                        
                        break;
                    
                    case Personnage::PERSONNAGE_ENVOUTE :
                        $message = 'Votre adversaire est bien envouté';
                        
                        $manager->updatePersonnages($perso);
                        $manager->updatePersonnages($persoAEnvouter);
                        
                        break;
                    
                    case Personnage::MAGIE :
                        $message = 'Vous n\'avez pas assez de magie !';
                        
                        break;
                    
                    case Personnage::PERSO_ENDORMI :
                        $message = 'Vous êtes endormi, vous ne pouvez pas lancer de sort !';
                        
                        break;
                        case Personnage::PERSO_MORT :
                          $message = 'Vous avez tué ce personnage !';
                          
                          $manager->updatePersonnage($perso);
                          $manager->deletePersonnage($persoAFrapper);
                          
                          break;
                      
                      case Personnage::PERSO_ENDORMI :
                          $message = 'Vous êtes endormi et ne pouvez pas frapper un adversaire';
                          
                          break;
                  }
              }
          }
      }
              
            


                            
                            // echo $perso->getEnvouter();
                            ?>
                        </p>
                    </fieldset>
                    <fieldset>
                        <legend>Qui frapper ?</legend>
                        <p>
                        <?php
                        // R2cupérer la liste de tous les personnages par ordre alphabétique dont le nom est différent du personnage choisi
                            // $persos = $manager->getListPersonnages($perso->getNom());
                            
                            if (empty($persos)) {
                                echo 'Il n\'y aucun adversaire';
                            }
                            
                            else {
                                if ($perso->toBeAsleep()) {
                                    echo 'Un magicien vous a endormi ! Vous allez vous réveiller dans ' . $perso->reveil() . '.';
                                }
                                
                                else {
                                    foreach ($persos as $onePerson) {
                                        echo '<a href="?frapperUnPersonnage=' . $onePerson->getId() . '">' . htmlspecialchars($onePerson->getNom()) . '</a> (Dégats : ' . $onePerson->getDegats() . ' - type : ' . $onePerson->getType() . ')';
                                        
                                        if ($perso->getType() == 'magicien') {
                                            echo ' - <a href="?envouter=' . $onePerson->getId() . '">Lancer un sort</a>';
                                        }
                                        
                                        echo '<br>';
                                    }
                                }
                            }
                        ?>
                        </p>
                    </fieldset>
                </section>
            
            <!-- <?php
              // Si utilisation d'un personnage, formulaire n'est pas affiché
            ?>
         -->
        
        
        </div>
    </body>
</html>
<?php
if (isset($perso)) {
    $_SESSION['perso'] = $perso;

}
date_default_timezone_set('Europe/Paris');
$date = date('d-m-y h:i:s');
echo $date;
?>