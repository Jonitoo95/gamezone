<nav class="navbar">
    <div class="nav-container">
        <a href="index.php" class="logo">GAMEZONE</a>
        <div class="nav-links">
            <a href="tournois.php" class="nav-link">Tournois</a>
            <a href="jeux.php" class="nav-link">Jeux</a>
            <?php if (isset($_SESSION['type']) && $_SESSION['type'] == 1) { ?>
            <a href="joueurs.php" class="nav-link">Joueurs</a>
            <?php } ?>
            <div class="nav-auth">
                <?php if (isset($_SESSION['id'])) { ?>
                    <a href="deconnexion.php" class="btn-primary">Déconnexion</a>
                <?php } else { ?>
                    <a href="inscription.php" class="btn-secondary">S'inscrire</a>
                    <a href="connexion.php" class="btn-primary">Connexion</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>
