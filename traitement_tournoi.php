<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: connexion.php");
    exit;
}

require("conf.php");
require("mailer.php");

$jeu = $_POST['jeu'];
$mdp = $_POST['mdp'];
$user_id = $_SESSION['id'];
$user_nom = $_SESSION['nom'];

// Verify password before updating
$req_user = "SELECT email, prenom, mdp FROM {$prefixe}joueur WHERE id = '$user_id'";
$res_user = mysqli_query($connexion, $req_user);
$user_data = mysqli_fetch_assoc($res_user);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — Inscription au Tournoi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    <main class="container">
        <div class="card result-card">
            
<?php
if ($user_data && password_verify($mdp, $user_data['mdp'])) {
    // Password is correct, update the game
    $update_req = "UPDATE {$prefixe}joueur SET id_jeu = '$jeu' WHERE id = '$user_id'";
    
    if (mysqli_query($connexion, $update_req)) {
        // Fetch game name for the email
        $req_jeu = "SELECT nom FROM {$prefixe}jeux WHERE id = '$jeu'";
        $res_jeu = mysqli_query($connexion, $req_jeu);
        $jeu_donnees = mysqli_fetch_assoc($res_jeu);
        $nom_jeu = $jeu_donnees ? $jeu_donnees['nom'] : 'Jeu inconnu';
        
        // Send email
        $email = $user_data['email'];
        $prenom = $user_data['prenom'];
        envoyerConfirmationInscription($email, $prenom, $user_nom, $nom_jeu);
        
        echo "<div class='success-banner'>";
        echo "<h2>Inscription confirmée !</h2>";
        echo "<p>Félicitations $prenom $user_nom, vous êtes bien inscrit au tournoi de <strong>$nom_jeu</strong>.</p>";
        echo "<p>Un email de confirmation a été envoyé à $email.</p>";
        echo "</div>";
    } else {
        echo "<div class='error-banner'>Erreur lors de la mise à jour : " . mysqli_error($connexion) . "</div>";
    }
} else {
    echo "<div class='error-banner'>Mot de passe incorrect. Impossible de valider l'inscription.</div>";
    echo "<div class='actions-group'><a href='tournoi.php' class='btn-secondary'>Réessayer</a></div>";
}

mysqli_close($connexion);
?>
        </div>
    </main>
</body>
</html>
