<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — Bienvenue</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    <main class="container">
        <div class="card hero-card">
            <span class="badge-tag">BIENVENUE SUR GAMEZONE</span>
            <h1 class="hero-title">Rejoignez le Tournoi !</h1>
            <p class="hero-subtitle">
                Déjà un compte ? Connectez-vous.<br>Sinon, inscrivez-vous pour participer.
            </p>
            <div class="hero-actions">
                <a href="connexion.php" class="btn-primary">SE CONNECTER</a>
                <a href="inscription.php" class="hero-btn-secondary">S'INSCRIRE</a>
            </div>
        </div>
    </main>
</body>
</html>