<?php
session_start();
require("conf.php");

// 1. On vérifie qu'il y a bien un token dans l'URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Lien invalide ou manquant.");
}

$token = $_GET['token'];

// 2. On vérifie si le token existe, n'est pas utilisé, et n'est pas expiré
$req = "SELECT id FROM {$prefixe}reset_password WHERE token = ? AND utilise = 0 AND expiration > NOW()";
$stmt = mysqli_prepare($connexion, $req);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Ce lien est invalide, expiré, ou a déjà été utilisé.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — Nouveau mot de passe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    <main class="container">
        <div class="card form-card">
            <div class="card-header">
                <h1>NOUVEAU MOT DE PASSE</h1>
                <p class="subtitle">Créez votre nouveau mot de passe sécurisé</p>
            </div>

            <form action="traitement_reinitialiser.php" method="post" class="game-form">
                <!-- On cache le token dans le formulaire pour l'envoyer au traitement -->
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group">
                    <label for="mdp">Nouveau mot de passe :</label>
                    <input type="password" id="mdp" name="mdp" placeholder="Votre nouveau mot de passe" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">VALIDER</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
