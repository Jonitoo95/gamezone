<?php
session_start();
require("conf.php");

// On inclut PHPMailer directement sans utiliser de fonction compliquée
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

if (!isset($_POST['email']) || empty($_POST['email'])) {
    header("Location: mot_de_passe_oublie.php?erreur=email");
    exit;
}

$email = $_POST['email'];

// Vérification de l'email avec une requête préparée
$req = "SELECT id FROM {$prefixe}joueur WHERE email = ?";
$stmt = mysqli_prepare($connexion, $req);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $joueur_id = $row['id'];
    
    // Génération du token
    $token = bin2hex(random_bytes(32));
    
    // Expiration dans 30 minutes
    $expiration = date("Y-m-d H:i:s", time() + 1800);
    
    // Insertion dans la table
    $insert = "INSERT INTO {$prefixe}reset_password (joueur_id, token, expiration, utilise) VALUES (?, ?, ?, 0)";
    $stmt_insert = mysqli_prepare($connexion, $insert);
    mysqli_stmt_bind_param($stmt_insert, "iss", $joueur_id, $token, $expiration);
    
    if (mysqli_stmt_execute($stmt_insert)) {
        
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'contact@sioslam.com';
            $mail->Password   = 'f>O@Xrv&OD7';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('contact@sioslam.com', 'GameZone');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Réinitialisation de votre mot de passe";
            
            $lien = "http://localhost/gamezone/reinitialiser.php?token=" . urlencode($token);
            $mail->Body    = "Pour réinitialiser votre mot de passe, cliquez ici : <a href='$lien'>Lien de réinitialisation</a>";

            $mail->send();
        } catch (Exception $e) {
            // En cas d'erreur on ne fait rien pour la sécurité
        }
    }
}

// Redirection
header("Location: mot_de_passe_oublie.php?msg=envoye");
exit;
?>
