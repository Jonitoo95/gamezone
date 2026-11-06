<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

function envoyerConfirmationInscription($destinataire, $prenom, $nom, $jeu) {
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
        $mail->addAddress($destinataire, "$prenom $nom");

        $mail->isHTML(true);
        $mail->Subject = "Confirmation d'inscription au tournoi GameZone";
        $mail->Body    = "Bonjour $prenom $nom,<br><br>Votre inscription au tournoi GameZone pour le tournoi <b>$jeu</b> a bien été enregistrée !<br><br>Préparez-vous pour le tournoi !";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>



