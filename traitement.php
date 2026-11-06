<?php
session_start();
require("conf.php");

$email = $_POST['email'];
$mdp = $_POST['mdp'];

$requete = "SELECT * FROM {$prefixe}joueur WHERE email = '$email'";
$resultat = mysqli_query($connexion, $requete);

if ($joueur = mysqli_fetch_assoc($resultat)) {
    if (password_verify($mdp, $joueur['mdp'])) {
        $_SESSION['id'] = $joueur['id'];
        $_SESSION['type'] = $joueur['type'];
        $_SESSION['nom'] = $joueur['nom'];
        header("Location: tournois.php");
        exit;
    } else {
        header("Location: connexion.php?erreur=mdp");
        exit;
    }
} else {
    header("Location: connexion.php?erreur=email");
    exit;
}
mysqli_close($connexion);
?>