<?php
session_start();
require("conf.php");

// 1. Contrôle d'accès : seul un utilisateur connecté peut s'inscrire (Section 6 & 13 du modop)
if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}

// 2. Vérification du paramètre tournoi_id
if (!isset($_GET['tournoi_id']) || empty($_GET['tournoi_id'])) {
    header("Location: tournois.php");
    exit;
}

$joueur_id = (int)$_SESSION['id'];
$tournoi_id = (int)$_GET['tournoi_id'];

// 3. Vérifier que le tournoi existe bien en base
$req_tournoi = "SELECT t.nom AS tournoi_nom, j.nom AS jeu_nom 
                FROM {$prefixe}tournoi t
                INNER JOIN {$prefixe}jeux j ON t.jeu_id = j.id
                WHERE t.id = $tournoi_id";
$res_tournoi = mysqli_query($connexion, $req_tournoi);

if (!$tournoi = mysqli_fetch_assoc($res_tournoi)) {
    mysqli_close($connexion);
    header("Location: tournois.php?erreur=non_trouve");
    exit;
}

// 4. Vérifier une double inscription (Section 8 du modop)
$req_verif = "SELECT * FROM {$prefixe}inscrire 
              WHERE joueur_id = $joueur_id AND tournoi_id = $tournoi_id";
$res_verif = mysqli_query($connexion, $req_verif);

if (mysqli_num_rows($res_verif) > 0) {
    // Le joueur est déjà inscrit
    mysqli_close($connexion);
    header("Location: tournois.php?erreur=deja_inscrit");
    exit;
}

// 5. Enregistrer l'inscription dans la table AP3_inscrire (Section 7 du modop)
$req_insert = "INSERT INTO {$prefixe}inscrire (joueur_id, tournoi_id) 
               VALUES ($joueur_id, $tournoi_id)";

if (mysqli_query($connexion, $req_insert)) {
    // Récupérer les informations du joueur pour le mail de confirmation (facultatif)
    $req_user = "SELECT email, prenom, nom FROM {$prefixe}joueur WHERE id = $joueur_id";
    $res_user = mysqli_query($connexion, $req_user);
    if ($user = mysqli_fetch_assoc($res_user)) {
        require_once("mailer.php");
        @envoyerConfirmationInscription($user['email'], $user['prenom'], $user['nom'], $tournoi['tournoi_nom']);
    }

    mysqli_close($connexion);
    header("Location: tournois.php?msg=inscrit");
    exit;
} else {
    die("Erreur lors de l'inscription : " . mysqli_error($connexion));
}
?>
