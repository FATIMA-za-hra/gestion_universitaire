<?php include('db.php'); ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Matières</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .navbar-dark {
        background-color: #212529 !important;
    }

    .navbar-brand {
        font-weight: bold;
        font-size: 1.5rem;
        color: #ffffff !important;
        transition: all 0.3s ease;
    }

    .navbar-brand:hover {
        text-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
        transform: scale(1.05);
    }

    .navbar-nav {
        margin-left: auto;
    }

    .navbar-nav .nav-link {
        font-weight: 500;
        color: #ffffff !important;
        transition: all 0.3s ease;
        padding: 8px 15px;
        border-radius: 5px;
    }

    .navbar-nav .nav-link:hover {
        color: #0d6efd !important;
        background-color: rgba(255, 255, 255, 0.05);
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.5);
    }

    body {
        background-color: #f8f9fa;
    }

    h2 {
        margin-top: 20px;
        color: #343a40;
    }

    table th, table td {
        vertical-align: middle !important;
    }
</style>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Gestion Universitaire</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="etudiants.php">Étudiants</a></li>
            <li class="nav-item"><a class="nav-link" href="enseignant.php">Enseignants</a></li>
            <li class="nav-item"><a class="nav-link" href="matiere.php">Matières</a></li>
            <li class="nav-item"><a class="nav-link" href="filiere.php">Filières</a></li>
            <li class="nav-item"><a class="nav-link" href="evaluation.php">Évaluations</a></li>
            <li class="nav-item"><a class="nav-link" href="emploi_temps.php">Emploi du temps</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
            </ul>
        </div>
        </div>
    </nav>
    <div class="p-4">
    <h2 class="text-center">Gestion des Matières</h2>
    <br>

    
    <?php
    $id_matiere_edit = null;
    $matiere_edit = null;

    // Récupération des données pour modification
    if (isset($_GET['edit'])) {
        $id_matiere_edit = $_GET['edit'];
        $stmt = $pdo->prepare("SELECT * FROM matieres WHERE id_matiere = ?");
        $stmt->execute([$id_matiere_edit]);
        $matiere_edit = $stmt->fetch();
    }

    // Ajouter une matière
    if (isset($_POST['ajouter'])) {
        $sql = "INSERT INTO matieres (nom_matiere, id_filiere, id_enseignant) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['nom_matiere'],
            $_POST['id_filiere'],
            $_POST['id_enseignant']
        ]);
        echo "<div class='alert alert-success'>Matière ajoutée avec succès !</div>";
    }

    // Modifier une matière
    if (isset($_POST['modifier'])) {
        $sql = "UPDATE matieres SET nom_matiere = ?, id_filiere = ?, id_enseignant = ? WHERE id_matiere = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['nom_matiere'],
            $_POST['id_filiere'],
            $_POST['id_enseignant'],
            $_POST['id_matiere']
        ]);
        echo "<div class='alert alert-info'>Matière modifiée avec succès !</div>";
    }
    
    // Supprimer une matière
    if (isset($_GET['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM matieres WHERE id_matiere = ?");
        $stmt->execute([$_GET['delete']]);
        echo "<div class='alert alert-danger'>Matière supprimée.</div>";
    }
    ?>
    <!-- Formulaire ajout/modification matière -->
    <form method="post" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="nom_matiere" class="form-control" placeholder="Nom de la matière" required
                   value="<?= $matiere_edit['nom_matiere'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <select name="id_enseignant" class="form-select" required>
                <option value="">Choisir un enseignant</option>
                <?php
                    $stmt = $pdo->query("SELECT * FROM enseignants");
                    while ($ens = $stmt->fetch()) {
                        $selected = ($matiere_edit && $ens['id_enseignant'] == $matiere_edit['id_enseignant']) ? "selected" : "";
                        echo "<option value='{$ens['id_enseignant']}' $selected>{$ens['nom']} {$ens['prenom']}</option>";
                    }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="id_filiere" class="form-select" required>
                <option value="">Choisir une filière</option>
                <?php
                    $stmt = $pdo->query("SELECT * FROM filieres");
                    while ($f = $stmt->fetch()) {
                        $selected = ($matiere_edit && $f['id_filiere'] == $matiere_edit['id_filiere']) ? "selected" : "";
                        echo "<option value='{$f['id_filiere']}' $selected>{$f['nom_filiere']}</option>";
                    }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <?php if ($matiere_edit): ?>
                <input type="hidden" name="id_matiere" value="<?= $id_matiere_edit ?>">
                <button type="submit" name="modifier" class="btn btn-warning">Mettre à jour</button>
            <?php else: ?>
                <button type="submit" name="ajouter" class="btn btn-success w-100">Ajouter</button>
            <?php endif; ?>
        </div>
    </form>

    <!-- Liste des matières -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Nom</th><th>Filière</th><th>Enseignant</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT m.id_matiere, m.nom_matiere, e.nom AS nom_ens, e.prenom, f.nom_filiere
                        FROM matieres m
                        JOIN enseignants e ON m.id_enseignant = e.id_enseignant
                        JOIN filieres f ON m.id_filiere = f.id_filiere";
                $stmt = $pdo->query($sql);
                foreach ($stmt as $row) {
                    echo "<tr>
                        <td>{$row['id_matiere']}</td>
                        <td>{$row['nom_matiere']}</td>
                        <td>{$row['nom_filiere']}</td>
                        <td>{$row['nom_ens']} {$row['prenom']}</td>
                        <td>
                            <a href='?edit={$row['id_matiere']}' class='btn btn-sm btn-primary'>Modifier</a>
                            <a href='?delete={$row['id_matiere']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Supprimer ?\")'>Supprimer</a>
                        </td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>
    </div>
    <script>
        function showSection(id) {
        document.querySelectorAll('.content-section').forEach(el => el.style.display = 'none');
        document.getElementById(id).style.display = 'block';
        }
    </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
