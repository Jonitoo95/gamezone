<?php
session_start();
require("conf.php");

// Contrôle d'accès administrateur (Section 13 du modop)
if (!isset($_SESSION['id']) || $_SESSION['type'] != 1) {
    header("Location: connexion.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$est_modification = ($id > 0);

$nom = "";
$date = "";
$jeu_id = 0;
$erreur = "";

// Si modification, charger les données existantes
if ($est_modification) {
    $req = "SELECT * FROM {$prefixe}tournoi WHERE id = $id";
    $res = mysqli_query($connexion, $req);
    if ($t = mysqli_fetch_assoc($res)) {
        $nom = $t['nom'];
        $date = $t['date'];
        $jeu_id = (int)$t['jeu_id'];
    } else {
        header("Location: tournois.php?erreur=non_trouve");
        exit;
    }
}

// Traitement du formulaire lors de la soumission
if (isset($_POST['envoi'])) {
    $nom = trim($_POST['nom']);
    $date = trim($_POST['date']);
    $jeu_id = (int)$_POST['jeu_id'];

    if (empty($nom) || empty($date) || $jeu_id <= 0) {
        $erreur = "Veuillez remplir tous les champs.";
    } else {
        $nom_esc = mysqli_real_escape_string($connexion, $nom);
        $date_esc = mysqli_real_escape_string($connexion, $date);

        if ($est_modification) {
            // Modification (UPDATE du CRUD - Section 11 modop)
            $sql = "UPDATE {$prefixe}tournoi 
                    SET nom = '$nom_esc', date = '$date_esc', jeu_id = $jeu_id 
                    WHERE id = $id";
            if (mysqli_query($connexion, $sql)) {
                mysqli_close($connexion);
                header("Location: tournois.php?msg=modifie");
                exit;
            } else {
                $erreur = "Erreur lors de la modification : " . mysqli_error($connexion);
            }
        } else {
            // Création (CREATE du CRUD - Section 11 modop)
            $sql = "INSERT INTO {$prefixe}tournoi (nom, date, jeu_id) 
                    VALUES ('$nom_esc', '$date_esc', $jeu_id)";
            if (mysqli_query($connexion, $sql)) {
                mysqli_close($connexion);
                header("Location: tournois.php?msg=cree");
                exit;
            } else {
                $erreur = "Erreur lors de la création : " . mysqli_error($connexion);
            }
        }
    }
}

// Récupération de la liste des jeux disponibles pour le <select>
$req_jeux = "SELECT id, nom FROM {$prefixe}jeux ORDER BY nom ASC";
$res_jeux = mysqli_query($connexion, $req_jeux);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMEZONE — <?php echo $est_modification ? 'Modifier' : 'Créer'; ?> un tournoi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <main class="container">
        <div class="card form-card">
            <div class="card-header">
                <h1><?php echo $est_modification ? 'MODIFIER LE TOURNOI' : 'CRÉER UN TOURNOI'; ?></h1>
                <p class="subtitle"><?php echo $est_modification ? 'Mise à jour des informations du tournoi' : 'Ajouter un nouveau tournoi à la compétition'; ?></p>
            </div>

            <?php if (!empty($erreur)) { ?>
                <div class="error-banner" style="margin-bottom: 20px;">
                    <?php echo htmlspecialchars($erreur); ?>
                </div>
            <?php } ?>

            <form action="admin_tournoi.php<?php echo $est_modification ? '?id='.$id : ''; ?>" method="post" class="game-form">
                <div class="form-group">
                    <label for="nom">Nom du Tournoi :</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" placeholder="Ex: Tournoi d'Hiver Valorant" required>
                </div>

                <div class="form-group">
                    <label for="jeu_id">Jeu associé :</label>
                    <select id="jeu_id" name="jeu_id" required>
                        <option value="" disabled <?php echo ($jeu_id == 0) ? 'selected' : ''; ?>>Sélectionnez un jeu</option>
                        <?php while ($jeu = mysqli_fetch_assoc($res_jeux)) { ?>
                            <option value="<?php echo $jeu['id']; ?>" <?php echo ($jeu['id'] == $jeu_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($jeu['nom']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Date de l'événement :</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" name="envoi" class="btn-submit">
                        <?php echo $est_modification ? 'ENREGISTRER LES MODIFICATIONS' : 'CRÉER LE TOURNOI'; ?>
                    </button>
                    <a href="tournois.php" class="btn-secondary" style="text-align: center; text-decoration: none; margin-top: 15px;">Retour aux tournois</a>
                </div>
            </form>
        </div>
    </main>

    <?php mysqli_close($connexion); ?>
</body>
</html>
