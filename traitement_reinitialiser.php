<?php
session_start();
require("conf.php");

if (!isset($_POST['token']) || !isset($_POST['mdp'])) {
    die("Données manquantes.");
}

$token = $_POST['token'];
$mdp = $_POST['mdp'];

// 1. On revérifie le token par sécurité
$req = "SELECT id, joueur_id FROM {$prefixe}reset_password WHERE token = ? AND utilise = 0 AND expiration > NOW()";
$stmt = mysqli_prepare($connexion, $req);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $reset_id = $row['id'];
    $joueur_id = $row['joueur_id'];
    
    // 2. On chiffre le nouveau mot de passe (Point 6 du document)
    $hash = password_hash($mdp, PASSWORD_DEFAULT);
    
    // 3. On met à jour le mot de passe du joueur
    $update_joueur = "UPDATE {$prefixe}joueur SET mdp = ? WHERE id = ?";
    $stmt_update_joueur = mysqli_prepare($connexion, $update_joueur);
    mysqli_stmt_bind_param($stmt_update_joueur, "si", $hash, $joueur_id);
    mysqli_stmt_execute($stmt_update_joueur);
    
    // 4. On désactive (invalide) le token pour qu'il ne soit plus réutilisable (Point 7 du document)
    $update_token = "UPDATE {$prefixe}reset_password SET utilise = 1 WHERE id = ?";
    $stmt_update_token = mysqli_prepare($connexion, $update_token);
    mysqli_stmt_bind_param($stmt_update_token, "i", $reset_id);
    mysqli_stmt_execute($stmt_update_token);
    
    // 5. On redirige vers la connexion avec un message de succès
    header("Location: connexion.php?msg=mdp_modifie");
    exit;
} else {
    die("Erreur : le lien est invalide ou a expiré.");
}
?>
