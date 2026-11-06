<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE - Liste des Jeux</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <main class="container container-large">
        <div class="card table-card">
            <div class="card-header-flex">
                <div>
                    <h1>Liste des Jeux Disponibles</h1>
                    <p class="subtitle">Consultation des jeux du tournoi</p>
                </div>
            </div>

<?php
require("conf.php");

$requete = "SELECT * FROM {$prefixe}jeux ORDER BY nom ASC";
$resultat = mysqli_query($connexion, $requete);

echo "<div class='table-responsive'>";
echo "<table class='game-table'>";
echo "<thead>
        <tr>
            <th>Nom du Jeu</th>
            <th>Description</th>
        </tr>
      </thead>";
echo "<tbody>";

while ($donnees = mysqli_fetch_assoc($resultat)) {
    $logoUrl = htmlspecialchars($donnees['logo']);
    // Affichage du logo en arrière-plan sur la droite
    $bgStyle = "background-image: url('{$logoUrl}');";
    
    echo "<tr class='game-row' style=\"$bgStyle\">";
    echo "<td class='game-cell'><span class='badge-game game-badge'>" . htmlspecialchars($donnees['nom']) . "</span></td>";
    echo "<td class='game-cell game-desc'>" . htmlspecialchars($donnees['description']) . "</td>";
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
