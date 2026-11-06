<?php
session_start();
require("conf.php");

// Contrôle de session
if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['tournoi_id']) || empty($_GET['tournoi_id'])) {
    header("Location: tournois.php");
    exit;
}

$joueur_id = (int)$_SESSION['id'];
$tournoi_id = (int)$_GET['tournoi_id'];

// Suppression de l'inscription pour ce joueur et ce tournoi
$req_delete = "DELETE FROM {$prefixe}inscrire WHERE joueur_id = $joueur_id AND tournoi_id = $tournoi_id";
mysqli_query($connexion, $req_delete);

mysqli_close($connexion);
header("Location: tournois.php?msg=desinscrit");
exit;
?>
