<?php
session_start();
require("conf.php");

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$mdp = $_POST['mdp'];

$hash = password_hash($mdp, PASSWORD_DEFAULT);

$requete = "INSERT INTO {$prefixe}joueur (nom, prenom, email, mdp, type) VALUES ('$nom', '$prenom', '$email', '$hash', 0)";

if (mysqli_query($connexion, $requete)) {
    $id = mysqli_insert_id($connexion);
    $_SESSION['id'] = $id;
    $_SESSION['type'] = 0;
    $_SESSION['nom'] = $nom;
    
    // ENVOI DU MAIL DE CONFIRMATION (Étape 1 de la mission 3)
    require_once("mailer.php");
    envoyerConfirmationInscription($email, $prenom, $nom, "GameZone");
    
    header("Location: tournois.php");
    exit;
} else {
    echo "Erreur d'inscription : " . mysqli_error($connexion);
}

mysqli_close($connexion);
?>