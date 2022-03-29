<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=jeuxcombat11', 'root', '');

    foreach($dbh->query('SELECT * from personnages') as $row) {
        print_r($row);
    }

    $dbh = null; //fermer la connexion
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>