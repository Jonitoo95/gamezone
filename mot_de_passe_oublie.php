<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — Mot de passe oublié</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    <main class="container">
        <div class="card form-card">
            <div class="card-header">
                <h1>RÉCUPÉRATION</h1>
                <p class="subtitle">Entrez votre email pour recevoir un lien de réinitialisation</p>
            </div>

            <form action="traitement_oubli.php" method="post" class="game-form">
                <div class="form-group">
                    <label for="email">Adresse Email :</label>
                    <input type="email" id="email" name="email" placeholder="nom@exemple.fr" required>
                </div>

                <?php
                if (isset($_GET['msg'])) {
                    echo "<div class='error-banner-sm' style='margin-bottom: 15px; color: #a5d6a7; border-color: #a5d6a7; background: rgba(165, 214, 167, 0.15);'>";
                    if ($_GET['msg'] == 'envoye') {
                        echo "Un email a été envoyé avec les instructions.";
                    }
                    echo "</div>";
                }
                if (isset($_GET['erreur'])) {
                    echo "<div class='error-banner-sm' style='margin-bottom: 15px;'>";
                    if ($_GET['erreur'] == 'email') {
                        echo "Veuillez entrer une adresse email valide.";
                    } elseif ($_GET['erreur'] == 'serveur') {
                        echo "Une erreur est survenue lors de la création du lien.";
                    }
                    echo "</div>";
                }
                ?>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">ENVOYER LE LIEN</button>
                    <a href="connexion.php" class="btn-secondary" style="text-align: center; text-decoration: none; margin-top: 15px;">Retour</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
