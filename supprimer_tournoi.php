<?php
session_start();
require("conf.php");

// 1. Contrôle des droits administrateur (Section 13 du modop)
if (!isset($_SESSION['id']) || $_SESSION['type'] != 1) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: tournois.php");
    exit;
}

$tournoi_id = (int)$_GET['id'];

// 2. Supprimer d'abord les inscriptions liées au tournoi (Section 12 du modop)
$req_del_inscrits = "DELETE FROM {$prefixe}inscrire WHERE tournoi_id = $tournoi_id";
mysqli_query($connexion, $req_del_inscrits);

// 3. Supprimer le tournoi (Section 12 du modop)
$req_del_tournoi = "DELETE FROM {$prefixe}tournoi WHERE id = $tournoi_id";
mysqli_query($connexion, $req_del_tournoi);

mysqli_close($connexion);
header("Location: tournois.php?msg=supprime");
exit;
?>
