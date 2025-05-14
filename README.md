Rapport du Projet PHP MySQL - Gestion Universitaire
Description du Fonctionnement du Site


1. Contexte et Objectifs
Le site web développé dans le cadre de ce projet est une application de gestion universitaire permettant d'administrer et de suivre les données des étudiants, enseignants, matières, filières, ainsi que les évaluations (notes). L'objectif principal est de centraliser ces données et de fournir une interface web ergonomique pour les gérer efficacement.


2. Fonctionnalités Principales
Le site est organisé autour des fonctionnalités suivantes :
    Gestion des Étudiants
        •	Ajout/Modification/Suppression/Recherche : Les administrateurs peuvent gérer les profils des étudiants (nom, prénom, date de naissance, filière).
        •	Association à une filière : Chaque étudiant est rattaché à une filière spécifique.
        •	Consultation des notes : Les étudiants et les enseignants peuvent consulter les notes par étudiant.
        •	Liste par filière : Affichage des étudiants regroupés par filière.
    Gestion des Enseignants
        •	Ajout/Modification/Suppression : Les administrateurs peuvent gérer les profils des enseignants (nom, prénom, email).
        •	Association aux matières : Un enseignant peut être associé à une ou plusieurs matières.
        •	Liste complète : Affichage de tous les enseignants.
    Gestion des Matières
        •	Ajout/Modification/Suppression : Les administrateurs peuvent gérer les matières (nom, filière associée, enseignant responsable).
        •	Association aux filières et enseignants : Chaque matière est liée à une filière et un enseignant.
    Gestion des Filières
        •	Ajout/Modification/Suppression : Les administrateurs peuvent gérer les filières (nom).
        •	Liste des matières et étudiants : Affichage des matières et étudiants associés à une filière.
    Gestion des Évaluations
        •	Saisie des notes : Les enseignants peuvent ajouter ou modifier des notes pour les étudiants dans une matière spécifique , et voir la moyenne d’une matiere par filière sous forme de graphe .
        •	Calcul des moyennes : Le système calcule automatiquement les moyennes par matière et par étudiant.
        •	Génération de relevés : Possibilité de générer des relevés de notes pour les étudiants.


3. Technologies Utilisées
    •	Frontend : HTML, CSS, JavaScript ,Bootstrap .
    •	Backend : PHP (Orienté objet).
    •	Base de données : MySQL.
    •	Serveur local : XAMPP pour  le développement et les tests.


4. Structure de la Base de Données
La base de données Gestion_universitaire comprend les tables suivantes :
    •	utilisateurs : (id_utilisateur , nom , prenom , mot_de_passe , #id_etudiant , role ,#email ,             #id_enseignant ) .
    •	etudiants : (id_etudiant , nom , prenom , date_naissance , email , telephone , niveau , #id_filiere ).
    •	enseignants : (id_enseignant , nom , prenom , email ).
    •	matières : (id_matiere , nom_matiere , #id_filiere , #id_enseignant ).
    •	filières : (id_filiere , nom_filiere).
    •	evaluations : (id_evaluation , note , type , date_evaluation , #id_etudiant , #id_matiere ) .
    •	employ_temps : (id , niveau , jour , heure_debut , heure_fin , salle ,#id_filiere , #id_matiere ,#id_enseignant).
    •	demande_attestations : (id_demande , date_demande , statut , #id_etudiant ).


5. Utilisateurs et Accès
    •	Administrateur : Accès complet à toutes les fonctionnalités (CRUD sur toutes les entités) , voir les demandes d’attestations .
    •	Enseignant : Accès restreint (saisie et modification des notes, consultation des étudiants et matières associées).
    •	Étudiant : Accès restreint (consultation de ses propres notes et relevés , demender une attestation).


6. Sécurité et Validation
    •	Validation des données : Effectuée à la fois côté client (JavaScript) et côté serveur (PHP).
    •	Sécurité : Utilisation de requêtes préparées pour prévenir les injections SQL.
    •	Interface ergonomique : Conçue pour être intuitive et facile à utiliser.

## Comptes de test

| Rôle            | Email                                  | Mot de passe  |
|:-------------   |:---------------------------------------|:--------------|
| Admin           | fatimazahra_biyaali@um5.ac.ma          | 25082024      |
| Étudiant        | fatima05biyaali@gmail.com              | fatifati      |
| Enseignant      | ali.lsfer@gmail.com                    | aliali        |








Réaliser par :

FATIMA ZAHRA EL BIYAALI
MAJDOULINE KESSIA
DRISS LAHLAISSI

