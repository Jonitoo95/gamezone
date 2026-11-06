<?php
session_start();
require("conf.php");

// Contrôle des droits administrateur (Section 13 du modop)
if (!isset($_SESSION['id']) || $_SESSION['type'] != 1) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['tournoi_id']) || !isset($_GET['joueur_id'])) {
    header("Location: tournois.php");
    exit;
}

$tournoi_id = (int)$_GET['tournoi_id'];
$joueur_id = (int)$_GET['joueur_id'];

// Suppression de l'inscription (Section 10 du modop)
$requete = "DELETE FROM {$prefixe}inscrire WHERE joueur_id = $joueur_id AND tournoi_id = $tournoi_id";
mysqli_query($connexion, $requete);

mysqli_close($connexion);
header("Location: admin_inscrits.php?tournoi_id=$tournoi_id&msg=supprime");
exit;
?>
