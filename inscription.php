<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <main class="container">
        <div class="card form-card">
            <div class="card-header">
                <h1>GAMEZONE</h1>
                <p class="subtitle">Formulaire d'inscription au tournoi</p>
            </div>

            <form action="traitement_inscription.php" method="post" class="game-form">
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
                </div>

                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required>
                </div>

                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" placeholder="nom@exemple.fr" required>
                </div>

                
                <div class="form-group">
                    <label for="mdp">Mot de passe :</label>
                    <input type="password" id="mdp" name="mdp" placeholder="Créez votre mot de passe" required>
                </div>

                

                <div class="form-actions">
                    <button type="submit" name="envoi" class="btn-submit">
                        S’INSCRIRE
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
