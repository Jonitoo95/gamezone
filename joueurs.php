<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['type'] != 1) {
    header('Location: connexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — Liste des Joueurs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <main class="container container-large">
        <div class="card table-card">
            <div class="card-header-flex">
                <div>
                    <h1>Liste des Joueurs Inscrits</h1>
                    <p class="subtitle">Consultation des inscriptions enregistrées</p>
                </div>
            </div>

<?php
require("conf.php");

$requete = "SELECT id, nom, prenom, email, type FROM {$prefixe}joueur ORDER BY nom ASC";
$resultat = mysqli_query($connexion, $requete);

echo "<div class='table-responsive'>";
echo "<table class='game-table'>";
echo "<thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
        </tr>
      </thead>";
echo "<tbody>";

while ($donnees = mysqli_fetch_assoc($resultat)) {
    $roleBadge = ($donnees['type'] == 1) ? "<span class='badge-game' style='background: #ff2a3b; color: white;'>Administrateur</span>" : "<span class='badge-game'>Joueur</span>";
    echo "<tr>";
    echo "<td><span class='id-pill'>#" . $donnees['id'] . "</span></td>";
    echo "<td class='fw-bold'>" . htmlspecialchars($donnees['nom']) . "</td>";
    echo "<td>" . htmlspecialchars($donnees['prenom']) . "</td>";
    echo "<td>" . htmlspecialchars($donnees['email']) . "</td>";
    echo "<td>" . $roleBadge . "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
echo "</div>";

mysqli_close($connexion);
?>

        </div>
    </main>

</body>
</html>
