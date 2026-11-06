<?php
$prefixe = 'AP3_';

// Local
$serveur = "localhost";
$utilisateur = "root";
$mdp = "";
$nombdd = "gamezone";

// En ligne
if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] != 'localhost') {
    $utilisateur = "u618673928_27DUTAU";
    $mdp = "E7dut26?";
    $nombdd = "u618673928_27DUTAU_BDD";
}

$connexion = mysqli_connect($serveur, $utilisateur, $mdp, $nombdd);
?>
