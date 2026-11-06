<?php
session_start();
require("conf.php");

// 1. Contrôle des droits administrateur (Section 13 du modop)
if (!isset($_SESSION['id']) || $_SESSION['type'] != 1) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['tournoi_id']) || empty($_GET['tournoi_id'])) {
    header("Location: tournois.php");
    exit;
}

$tournoi_id = (int)$_GET['tournoi_id'];

// Récupérer les informations du tournoi
$req_tournoi = "SELECT t.nom, t.date, j.nom AS jeu 
                FROM {$prefixe}tournoi t
                INNER JOIN {$prefixe}jeux j ON t.jeu_id = j.id
                WHERE t.id = $tournoi_id";
$res_tournoi = mysqli_query($connexion, $req_tournoi);
$tournoi = mysqli_fetch_assoc($res_tournoi);

if (!$tournoi) {
    header("Location: tournois.php?erreur=non_trouve");
    exit;
}

$date_fr = date("d/m/Y", strtotime($tournoi['date']));

// Récupérer la liste des inscrits au tournoi (Section 9 du modop)
$requete = "SELECT j.id, j.nom, j.prenom, j.email
            FROM {$prefixe}inscrire i
            INNER JOIN {$prefixe}joueur j ON i.joueur_id = j.id
            WHERE i.tournoi_id = $tournoi_id
            ORDER BY j.nom ASC";
$resultat = mysqli_query($connexion, $requete);
$nb_inscrits = mysqli_num_rows($resultat);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — Inscrits au tournoi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <main class="container container-large">
        <div class="card table-card">
            <div class="card-header-flex">
                <div>
                    <h1>Inscrits : <?php echo htmlspecialchars($tournoi['nom']); ?></h1>
                    <p class="subtitle">Jeu : <strong><?php echo htmlspecialchars($tournoi['jeu']); ?></strong> | Date : <strong><?php echo $date_fr; ?></strong> (<?php echo $nb_inscrits; ?> inscrit<?php echo $nb_inscrits > 1 ? 's' : ''; ?>)</p>
                </div>
                <div>
                    <a href="tournois.php" class="btn-secondary">← Retour aux tournois</a>
                </div>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'supprime') { ?>
                <div class="success-banner" style="margin-bottom: 20px;">
                    L'inscription a été supprimée avec succès.
                </div>
            <?php } ?>

            <div class="table-responsive">
                <table class="game-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($nb_inscrits == 0) { ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px;">Aucun joueur n'est encore inscrit à ce tournoi.</td>
                            </tr>
                        <?php } ?>

                        <?php while ($joueur = mysqli_fetch_assoc($resultat)) { ?>
                            <tr>
                                <td><span class="id-pill">#<?php echo $joueur['id']; ?></span></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($joueur['nom']); ?></td>
                                <td><?php echo htmlspecialchars($joueur['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($joueur['email']); ?></td>
                                <td style="text-align: right;">
                                    <!-- Suppression d'une inscription (Section 10 du modop) -->
                                    <a href="supprimer_inscription.php?tournoi_id=<?php echo $tournoi_id; ?>&joueur_id=<?php echo $joueur['id']; ?>" 
                                       class="btn-secondary" 
                                       style="padding: 6px 12px; font-size: 0.85rem; color: #ff2a3b; border-color: rgba(255, 42, 59, 0.5);"
                                       onclick="return confirm('Êtes-vous sûr de vouloir retirer ce joueur du tournoi ?');">
                                        Retirer
                                    </a>
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
