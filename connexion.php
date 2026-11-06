<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    <main class="container">
        <div class="card form-card">
            <div class="card-header">
                <h1>CONNEXION</h1>
                <p class="subtitle">Connectez-vous pour accéder à votre espace</p>
            </div>

            <form action="traitement.php" method="post" class="game-form">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" placeholder="nom@exemple.fr" required>
                </div>
                <div class="form-group">
                    <label for="mdp">Mot de passe :</label>
                    <input type="password" id="mdp" name="mdp" placeholder="Entrez votre mot de passe" required>
                    <a href="mot_de_passe_oublie.php" class="email-link" style="display: block; text-align: right; margin-top: 5px; font-size: 0.9em;">Mot de passe oublié ?</a>
                </div>

                <?php
                if (isset($_GET['msg']) && $_GET['msg'] == 'mdp_modifie') {
                    echo "<div class='error-banner-sm' style='margin-bottom: 15px; color: #a5d6a7; border-color: #a5d6a7; background: rgba(165, 214, 167, 0.15);'>";
                    echo "Votre mot de passe a bien été modifié. Vous pouvez vous connecter.";
                    echo "</div>";
                }
                if (isset($_GET['erreur'])) {
                    echo "<div class='error-banner-sm' style='margin-bottom: 15px;'>";
                    if ($_GET['erreur'] == 'mdp') {
                        echo "Mot de passe incorrect.";
                    } elseif ($_GET['erreur'] == 'email') {
                        echo "Aucun compte trouvé avec cet email.";
                    }
                    echo "</div>";
                }
                ?>

                <div class="form-actions">
                    <button type="submit" name="envoi" class="btn-submit">SE CONNECTER</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>