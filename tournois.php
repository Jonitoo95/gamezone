<?php
session_start();
require("conf.php");

// Messages de confirmation ou d'erreur
$message = "";
$messageType = "";

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'inscrit') {
        $message = "Votre inscription au tournoi a bien été enregistrée !";
        $messageType = "success-banner";
    } elseif ($_GET['msg'] == 'desinscrit') {
        $message = "Votre désinscription a bien été prise en compte.";
        $messageType = "success-banner";
    } elseif ($_GET['msg'] == 'cree') {
        $message = "Le tournoi a été créé avec succès.";
        $messageType = "success-banner";
    } elseif ($_GET['msg'] == 'modifie') {
        $message = "Le tournoi a été modifié avec succès.";
        $messageType = "success-banner";
    } elseif ($_GET['msg'] == 'supprime') {
        $message = "Le tournoi et ses inscriptions ont été supprimés.";
        $messageType = "success-banner";
    }
}

if (isset($_GET['erreur'])) {
    if ($_GET['erreur'] == 'deja_inscrit') {
        $message = "Vous êtes déjà inscrit à ce tournoi !";
        $messageType = "error-banner";
    } elseif ($_GET['erreur'] == 'non_trouve') {
        $message = "Tournoi introuvable.";
        $messageType = "error-banner";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — Liste des Tournois</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <main class="container container-large">
        <div class="card table-card">
            <div class="card-header-flex">
                <div>
                    <h1>Tournois Disponibles</h1>
                    <p class="subtitle">Consultez les tournois et inscrivez-vous</p>
                </div>
                <?php if (isset($_SESSION['type']) && $_SESSION['type'] == 1) { ?>
                    <div>
                        <a href="admin_tournoi.php" class="btn-primary">+ Créer un tournoi</a>
                    </div>
                <?php } ?>
            </div>

            <?php if (!empty($message)) { ?>
                <div class="<?php echo $messageType; ?>" style="margin-bottom: 20px;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>

            <?php
            // Récupération des tournois avec le nom du jeu et son logo (Section 5 du modop)
            $requete = "SELECT t.id, t.nom, t.date, j.nom AS jeu, j.logo
                        FROM {$prefixe}tournoi t
                        INNER JOIN {$prefixe}jeux j ON t.jeu_id = j.id
                        ORDER BY t.date ASC";
            $resultat = mysqli_query($connexion, $requete);

            // Si le joueur est connecté, on récupère la liste des tournois auxquels il est déjà inscrit
            $mes_inscriptions = [];
            if (isset($_SESSION['id'])) {
                $joueur_id = (int)$_SESSION['id'];
                $req_insc = "SELECT tournoi_id FROM {$prefixe}inscrire WHERE joueur_id = $joueur_id";
                $res_insc = mysqli_query($connexion, $req_insc);
                while ($row_i = mysqli_fetch_assoc($res_insc)) {
                    $mes_inscriptions[] = $row_i['tournoi_id'];
                }
            }
            ?>

            <div class="table-responsive">
                <table class="game-table">
                    <thead>
                        <tr>
                            <th>Nom du Tournoi</th>
                            <th>Jeu</th>
                            <th>Date</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($resultat) == 0) { ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 30px;">Aucun tournoi disponible actuellement.</td>
                            </tr>
                        <?php } ?>

                        <?php while ($tournoi = mysqli_fetch_assoc($resultat)) { 
                            $est_inscrit = in_array($tournoi['id'], $mes_inscriptions);
                            $date_fr = date("d/m/Y", strtotime($tournoi['date']));
                        ?>
                            <tr>
                                <td class="fw-bold">
                                    <?php echo htmlspecialchars($tournoi['nom']); ?>
                                </td>
                                <td>
                                    <div class="table-logo-container">
                                        <?php if (!empty($tournoi['logo'])) { ?>
                                            <img src="<?php echo htmlspecialchars($tournoi['logo']); ?>" alt="Logo" class="table-logo">
                                        <?php } ?>
                                        <span class="badge-game"><?php echo htmlspecialchars($tournoi['jeu']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-family: var(--font-mono); color: var(--text-dim);"><?php echo $date_fr; ?></span>
                                </td>
                                <td style="text-align: right;">
                                    <?php
                                    // 1. Pour les administrateurs : boutons de gestion (Sections 9, 10, 11 du modop)
                                    if (isset($_SESSION['type']) && $_SESSION['type'] == 1) { ?>
                                        <a href="admin_inscrits.php?tournoi_id=<?php echo $tournoi['id']; ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 0.85rem; margin-right: 5px;">Inscrits</a>
                                        <a href="admin_tournoi.php?id=<?php echo $tournoi['id']; ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 0.85rem; margin-right: 5px;">Modifier</a>
                                        <a href="supprimer_tournoi.php?id=<?php echo $tournoi['id']; ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 0.85rem; color: #ff2a3b; border-color: rgba(255, 42, 59, 0.5);" onclick="return confirm('Confirmer la suppression de ce tournoi ?');">Supprimer</a>
                                    <?php 
                                    // 2. Pour les joueurs connectés : bouton d'inscription (Section 6 du modop)
                                    } elseif (isset($_SESSION['id'])) { 
                                        if ($est_inscrit) { ?>
                                            <span class="badge-game" style="background: rgba(34, 197, 94, 0.2); border-color: #22c55e; color: #4ade80; margin-right: 8px;">Inscrit ✓</span>
                                            <a href="desinscrire.php?tournoi_id=<?php echo $tournoi['id']; ?>" class="btn-secondary" style="padding: 5px 10px; font-size: 0.8rem;" onclick="return confirm('Se désinscrire de ce tournoi ?');">Annuler</a>
                                        <?php } else { ?>
                                            <a href="inscrire.php?tournoi_id=<?php echo $tournoi['id']; ?>" class="btn-primary" style="padding: 6px 16px; font-size: 0.85rem;">S'inscrire</a>
                                        <?php } ?>
                                    <?php 
                                    // 3. Pour les visiteurs non connectés
                                    } else { ?>
                                        <a href="connexion.php" class="btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;">Connexion requise</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php mysqli_close($connexion); ?>
        </div>
    </main>

</body>
</html>
