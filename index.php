<?php
session_start();
include('db.php');

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil | Gestion Universitaire</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: url('ests_background.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      min-height: 100vh;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.7);
      min-height: 100vh;
      padding-top: 100px;
    }

    .welcome {
      text-align: center;
      font-size: 2rem;
      font-weight: bold;
      margin-top: 40px;
      animation: fadeIn 1s ease-in-out;
    }

    .subtitle {
      text-align: center;
      font-size: 1.2rem;
      font-style: italic;
      margin-top: 15px;
      color: #ccc;
      animation: fadeIn 1.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .btn-container {
      text-align: center;
      margin-top: 60px;
      animation: fadeIn 2s ease-in-out;
    }

    .btn-container a {
      margin: 10px;
    }

    .navbar-brand {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="overlay">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Gestion Universitaire</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!--<div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="etudiants.php">Étudiants</a></li>
            <li class="nav-item"><a class="nav-link" href="enseignant.php">Enseignants</a></li>
            <li class="nav-item"><a class="nav-link" href="matiere.php">Matières</a></li>
            <li class="nav-item"><a class="nav-link" href="filiere.php">Filières</a></li>
            <li class="nav-item"><a class="nav-link" href="evaluation.php">Évaluations</a></li>
          </ul>
        </div>-->
      </div>
    </nav>

    <div class="welcome">
      Bonjour Monsieur l’administrateur 👋
    </div>
    <div class="subtitle">
      Bienvenue sur le système de gestion universitaire. Merci pour votre engagement à l'excellence !
    </div>

    <div class="btn-container">
      <a href="etudiants.php" class="btn btn-outline-light">Gérer les Étudiants</a>
      <a href="enseignant.php" class="btn btn-outline-light">Gérer les Enseignants</a>
      <a href="matiere.php" class="btn btn-outline-light">Gérer les Matières</a>
      <a href="filiere.php" class="btn btn-outline-light">Gérer les Filières</a>
      <a href="evaluation.php" class="btn btn-outline-light">Gérer les Évaluations</a>
      <a class="btn btn-outline-light" href="emploi_temps.php">Emploi du temps</a>
      <a href="logout.php" class="btn btn-outline-light">Déconnexion</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
