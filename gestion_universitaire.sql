-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 14 mai 2025 à 00:14
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_universitaire`
--

-- --------------------------------------------------------

--
-- Structure de la table `demande_attestations`
--

CREATE TABLE `demande_attestations` (
  `id_demande` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `date_demande` datetime DEFAULT current_timestamp(),
  `statut` varchar(50) DEFAULT 'En attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demande_attestations`
--

INSERT INTO `demande_attestations` (`id_demande`, `id_etudiant`, `date_demande`, `statut`) VALUES
(8, 6, '2025-04-25 11:23:45', 'Acceptée'),
(9, 15, '2025-04-25 20:13:14', 'Acceptée');

-- --------------------------------------------------------

--
-- Structure de la table `emploi_temps`
--

CREATE TABLE `emploi_temps` (
  `id` int(11) NOT NULL,
  `id_filiere` int(11) NOT NULL,
  `niveau` varchar(50) NOT NULL,
  `jour` varchar(20) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `salle` varchar(50) DEFAULT NULL,
  `id_enseignant` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `emploi_temps`
--

INSERT INTO `emploi_temps` (`id`, `id_filiere`, `niveau`, `jour`, `heure_debut`, `heure_fin`, `id_matiere`, `salle`, `id_enseignant`) VALUES
(2, 1, 'DUT', '2025-04-18', '13:30:00', '15:30:00', 2, '1', 4);

-- --------------------------------------------------------

--
-- Structure de la table `enseignants`
--

CREATE TABLE `enseignants` (
  `id_enseignant` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignants`
--

INSERT INTO `enseignants` (`id_enseignant`, `nom`, `prenom`, `email`) VALUES
(4, 'lsfer', 'ali', 'ali.lsfer@gmail.com'),
(5, 'amrani', 'mayssan', 'mayssaneamrani@gmail.com'),
(6, 'gueddari', 'ilyas', 'ilyasgueddari@um5.ac.ma'),
(8, 'Karra', 'Rachid', 'rachidkarra@um5.ac.ma'),
(9, 'Elbouchti', 'karim', 'bouchtikarim@um5.ac.ma');

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id_etudiant` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `date_naissance` date NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `id_filiere` int(11) NOT NULL,
  `niveau` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id_etudiant`, `nom`, `prenom`, `date_naissance`, `email`, `telephone`, `id_filiere`, `niveau`) VALUES
(1, 'harbal', 'hiba', '2006-05-21', 'harbalhiba@gmail.com', '0645892330', 1, 'DUT'),
(6, 'biyaali', 'fatima zahra', '2006-10-08', 'fatima05biyaali@gmail.com', '0623731642', 1, 'DUT'),
(15, 'maarouf', 'ayman', '2006-05-15', 'aymanmaarouf@um5.ac.ma', '0615475044', 1, 'DUT'),
(34, 'izem', 'aya', '2006-07-17', 'ayaizem@gmail.com', '0650606375', 2, 'DUT'),
(46, 'biyaali', 'hatim', '2007-08-28', 'hatimbiyaali.@um5.ac.ma', '0642905104', 1, 'DUT'),
(345, 'Oulhou', 'mariam', '2006-10-06', 'oualmariam123@gmail.com', '0650413838', 5, 'DUT'),
(2345, 'kessia', 'majdouline', '2007-01-11', 'majdoulinekessia@gmail.com', '0655262285', 1, 'DUT');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id_evaluation` int(11) NOT NULL,
  `id_etudiant` int(11) DEFAULT NULL,
  `id_matiere` int(11) DEFAULT NULL,
  `note` decimal(5,2) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `date_evaluation` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id_evaluation`, `id_etudiant`, `id_matiere`, `note`, `type`, `date_evaluation`) VALUES
(1, 6, 1, 18.00, 'Contrôle', '2025-04-12'),
(3, 345, 2, 18.00, 'Examen', '2025-04-14'),
(4, 2345, 1, 14.00, 'Contrôle', '2025-04-17'),
(5, 34, 2, 17.00, 'Examen', '2025-04-17'),
(6, 15, 1, 15.00, 'Examen', '2025-04-20'),
(8, 6, 2, 15.00, 'Contrôle', '2025-04-25');

-- --------------------------------------------------------

--
-- Structure de la table `filieres`
--

CREATE TABLE `filieres` (
  `id_filiere` int(11) NOT NULL,
  `nom_filiere` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `filieres`
--

INSERT INTO `filieres` (`id_filiere`, `nom_filiere`) VALUES
(1, 'Génie informatique'),
(2, 'Génie civil'),
(3, 'Génie industrielle'),
(5, 'Système Informatique Réseau'),
(6, 'Génie Electrique et Informatique'),
(7, 'Techniques De Management'),
(8, 'Marketing Digital et Commercial'),
(9, 'Génie de l\'Eau et l\'Environnement');

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `id_matiere` int(11) NOT NULL,
  `nom_matiere` varchar(100) DEFAULT NULL,
  `id_filiere` int(11) NOT NULL,
  `id_enseignant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id_matiere`, `nom_matiere`, `id_filiere`, `id_enseignant`) VALUES
(1, 'php', 1, 4),
(2, 'base donnee', 1, 4),
(3, 'système d\'exploitation', 5, 6),
(5, 'algèbre', 3, 5),
(6, 'SGBD sql', 6, 9),
(7, 'Structure Donnée', 7, 8);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(30) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `id_etudiant` int(11) DEFAULT NULL,
  `role` enum('admin','enseignant','etudiant') NOT NULL,
  `id_enseignant` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `id_etudiant`, `role`, `id_enseignant`) VALUES
(1, 'biyaali', 'fatima', 'fatima05biyaali@gmail.com', 'fatifati', 6, 'etudiant', NULL),
(2, 'lsfer', 'ali', 'ali.lsfer@gmail.com', 'aliali', NULL, 'enseignant', 4),
(3, 'el biyaali', 'fatima zahra', 'fatimazahra_biyaali@um5.ac.ma', '25082024', NULL, 'admin', NULL),
(5, 'gueddari', 'ilyas', 'ilyasgueddari@um5.ac.ma', '#oxKN!8Q', NULL, 'enseignant', 6),
(7, 'maarouf', 'ayman', 'aymanmaarouf@um5.ac.ma', 'jtC*w0#Y', 15, 'etudiant', NULL),
(8, 'amrani', 'mayssan', 'mayssaneamrani@gmail.com', 'A@fg64/2', NULL, 'enseignant', 5),
(9, 'biyaali', 'hatim', 'hatimbiyaali.@gmail.com', 'O0R&L8@n', 23, 'etudiant', NULL),
(10, 'biyaali', 'hatim', 'hatimbiyaali.@um5.ac.ma', 'hvhcu$?A', 46, 'etudiant', NULL),
(11, 'Karra', 'Rachid', 'rachidkarra@um5.ac.ma', 'Ij!!1w$v', NULL, 'enseignant', 8),
(12, 'Elbouchti', 'karim', 'bouchtikarim@um5.ac.ma', 'A&DslMY#', NULL, 'enseignant', 9),
(13, 'harbal', 'hiba', 'harbalhiba@gmail.com', 'MRXCDm*0', 1, 'etudiant', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `demande_attestations`
--
ALTER TABLE `demande_attestations`
  ADD PRIMARY KEY (`id_demande`),
  ADD KEY `id_etudiant` (`id_etudiant`);

--
-- Index pour la table `emploi_temps`
--
ALTER TABLE `emploi_temps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_filiere` (`id_filiere`),
  ADD KEY `emploi_temps_enseignant_fk` (`id_enseignant`),
  ADD KEY `fk_emploi_matiere` (`id_matiere`);

--
-- Index pour la table `enseignants`
--
ALTER TABLE `enseignants`
  ADD PRIMARY KEY (`id_enseignant`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id_etudiant`),
  ADD KEY `fk_etudiants_filiere` (`id_filiere`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id_evaluation`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_matiere` (`id_matiere`);

--
-- Index pour la table `filieres`
--
ALTER TABLE `filieres`
  ADD PRIMARY KEY (`id_filiere`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`id_matiere`),
  ADD KEY `fk_matieres_enseignant` (`id_enseignant`),
  ADD KEY `fk_matieres_filiere` (`id_filiere`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_enseignant` (`id_enseignant`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `demande_attestations`
--
ALTER TABLE `demande_attestations`
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `emploi_temps`
--
ALTER TABLE `emploi_temps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `enseignants`
--
ALTER TABLE `enseignants`
  MODIFY `id_enseignant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id_evaluation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `filieres`
--
ALTER TABLE `filieres`
  MODIFY `id_filiere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id_matiere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `demande_attestations`
--
ALTER TABLE `demande_attestations`
  ADD CONSTRAINT `demande_attestations_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`id_etudiant`);

--
-- Contraintes pour la table `emploi_temps`
--
ALTER TABLE `emploi_temps`
  ADD CONSTRAINT `emploi_temps_enseignant_fk` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`),
  ADD CONSTRAINT `emploi_temps_ibfk_1` FOREIGN KEY (`id_filiere`) REFERENCES `filieres` (`id_filiere`),
  ADD CONSTRAINT `fk_emploi_matiere` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id_matiere`);

--
-- Contraintes pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD CONSTRAINT `fk_etudiants_filiere` FOREIGN KEY (`id_filiere`) REFERENCES `filieres` (`id_filiere`);

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`id_etudiant`),
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`id_matiere`) REFERENCES `matieres` (`id_matiere`);

--
-- Contraintes pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD CONSTRAINT `fk_matieres_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`),
  ADD CONSTRAINT `fk_matieres_filiere` FOREIGN KEY (`id_filiere`) REFERENCES `filieres` (`id_filiere`);

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `fk_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
