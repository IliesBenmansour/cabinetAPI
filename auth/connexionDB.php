<?php
function BDconnexion() {
    $servername = "mysql-47medecine.alwaysdata.net";
    $db = "47medecine_connexion";
    $login = "344048_menoh";
    $mdp = "cestmoiyoumni";

    try {
        $bdd = new PDO("mysql:host=$servername;dbname=$db;charset=utf8", $login, $mdp);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
    } catch (PDOException $e) {
        die('La connexion a la base de donnée a échoué: ' . $e->getMessage());
    }
}


function connexion_BD() {
    $servername = "mysql-47medecine.alwaysdata.net";
    $db = "47medecine_bd";
    $login = "344048_menoh";
    $mdp = "cestmoiyoumni";

    try {
        $bdd = new PDO("mysql:host=$servername;dbname=$db;charset=utf8", $login, $mdp);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
    } catch (PDOException $e) {
        die('La connexion a la base de donnée a échoué: ' . $e->getMessage());
    }
}
?>