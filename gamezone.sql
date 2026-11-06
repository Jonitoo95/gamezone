-- ===================================================
-- SCRIPT SQL DE CREATION DES TABLES GAMEZONE
-- TD WEB - BTS SIO : GAMEZONE TOURNAMENT
-- ===================================================

-- Suppression des anciennes tables si elles existent (dans l'ordre inverse des contraintes)
DROP TABLE IF EXISTS AP3_inscrire;
DROP TABLE IF EXISTS AP3_tournoi;
DROP TABLE IF EXISTS AP3_reset_password;
DROP TABLE IF EXISTS AP3_joueur;
DROP TABLE IF EXISTS AP3_jeux;

-- 1. Creation de la table AP3_jeux
CREATE TABLE AP3_jeux (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL,
  description TEXT,
  logo VARCHAR(100),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion des jeux
INSERT INTO `AP3_jeux` (`nom`, `description`, `logo`) VALUES 
('Valorant', 'Jeu de tir tactique en 5v5', 'valorant.png'),
('Rocket League', 'Football avec des voitures', 'rocketleague.png'),
('Fortnite', 'Battle Royale', 'fortnite.png'),
('League of Legends', 'MOBA competitif', 'lol.png'),
('Counter-Strike', 'Jeu de tir tactique', 'csgo.png');

-- 2. Creation de la table AP3_joueur (sans le lien direct jeu_id - Mission 4)
CREATE TABLE AP3_joueur (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL,
  prenom VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  mdp VARCHAR(255) NOT NULL,
  type INT DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO AP3_joueur (nom, prenom, email, mdp, type) VALUES
('DUPONT', 'Thomas', 'thomas@exemple.fr', 'mdp_test', 0),
('MARTIN', 'Julie', 'julie@exemple.fr', 'mdp_test', 0),
('BERNARD', 'Lucas', 'lucas@exemple.fr', 'mdp_test', 0);

-- 3. Creation de la table de reinitialisation de mot de passe (Mission 3)
CREATE TABLE AP3_reset_password (
    id INT PRIMARY KEY AUTO_INCREMENT,
    joueur_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiration DATETIME NOT NULL,
    utilise TINYINT NOT NULL DEFAULT 0,
    FOREIGN KEY (joueur_id) REFERENCES AP3_joueur(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Creation de la table AP3_tournoi (Mission 4)
CREATE TABLE AP3_tournoi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    jeu_id INT NOT NULL,
    FOREIGN KEY (jeu_id) REFERENCES AP3_jeux(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO AP3_tournoi (nom, date, jeu_id) VALUES
('Tournoi d\'Automne Valorant', '2026-11-15', 1),
('Rocket League Championship', '2026-11-20', 2),
('Fortnite Royale Duo', '2026-11-28', 3),
('League of Legends Clash', '2026-12-05', 4);

-- 5. Creation de la table d'association AP3_inscrire (Mission 4)
CREATE TABLE AP3_inscrire (
    joueur_id INT NOT NULL,
    tournoi_id INT NOT NULL,
    PRIMARY KEY (joueur_id, tournoi_id),
    FOREIGN KEY (joueur_id) REFERENCES AP3_joueur(id),
    FOREIGN KEY (tournoi_id) REFERENCES AP3_tournoi(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
