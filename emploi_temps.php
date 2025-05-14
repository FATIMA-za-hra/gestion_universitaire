<?php include('db.php'); ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emploi du Temps - Admin</title>
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
    <h2 class="text-center">Emploi du Temps</h2>
<?php
    $isEditing = false;
    $editData = [];

    if (isset($_POST['edit'])) {
        $isEditing = true;
        $editData = [
            'id' => $_POST['id'],
            'id_filiere' => $_POST['id_filiere'],
            'niveau' => $_POST['niveau'],
            'jour' => $_POST['jour'],
            'heure_debut' => $_POST['heure_debut'],
            'heure_fin' => $_POST['heure_fin'],
            'id_matiere' => $_POST['id_matiere'],
            'salle' => $_POST['salle'],
            'id_enseignant' => $_POST['id_enseignant'],
        ];
    }

    if (isset($_POST['ajouter'])) {
        $stmt = $pdo->prepare("INSERT INTO emploi_temps (id_filiere, niveau, jour, heure_debut, heure_fin, id_matiere, salle, id_enseignant) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['id_filiere'], $_POST['niveau'], $_POST['jour'],
            $_POST['heure_debut'], $_POST['heure_fin'], $_POST['id_matiere'],
            $_POST['salle'], $_POST['id_enseignant']
        ]);
        echo "<div class='alert alert-success'>Séance ajoutée !</div>";
    }

    if (isset($_POST['modifier'])) {
        $stmt = $pdo->prepare("UPDATE emploi_temps SET id_filiere=?, niveau=?, jour=?, heure_debut=?, heure_fin=?, id_matiere=?, salle=?, id_enseignant=? WHERE id=?");
        $stmt->execute([
            $_POST['id_filiere'], $_POST['niveau'], $_POST['jour'],
            $_POST['heure_debut'], $_POST['heure_fin'], $_POST['id_matiere'],
            $_POST['salle'], $_POST['id_enseignant'], $_POST['id']
        ]);
        echo "<div class='alert alert-warning'>Séance mise à jour.</div>";
    }

    if (isset($_GET['supprimer'])) {
        $stmt = $pdo->prepare("DELETE FROM emploi_temps WHERE id = ?");
        $stmt->execute([$_GET['supprimer']]);
        echo "<div class='alert alert-danger'>Séance supprimée.</div>";
    }
    ?>

    

    <form method="POST" class="row g-3 mt-3 mb-4">
        <?php if ($isEditing): ?>
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <?php endif; ?>

        <!-- Filière -->
        <div class="col-md-3">
            <select name="id_filiere" class="form-select" required>
                <option value="">Choisir une filière</option>
                <?php
                $filieres = $pdo->query("SELECT * FROM filieres");
                while ($f = $filieres->fetch()) {
                    $selected = ($isEditing && $f['id_filiere'] == $editData['id_filiere']) ? "selected" : "";
                    echo "<option value='{$f['id_filiere']}' $selected>{$f['nom_filiere']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Niveau -->
        <div class="col-md-3">
            <select name="niveau" class="form-select" required>
                <option value="">Choisir un niveau</option>
                <option value="DUT" <?= ($isEditing && $editData['niveau'] == "DUT") ? "selected" : "" ?>>DUT</option>
                <option value="LICENSE" <?= ($isEditing && $editData['niveau'] == "LICENSE") ? "selected" : "" ?>>License</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="date" name="jour" class="form-control" required value="<?= $isEditing ? $editData['jour'] : '' ?>">
        </div>
        <div class="col-md-2">
            <input type="time" name="heure_debut" class="form-control" required value="<?= $isEditing ? $editData['heure_debut'] : '' ?>">
        </div>
        <div class="col-md-2">
            <input type="time" name="heure_fin" class="form-control" required value="<?= $isEditing ? $editData['heure_fin'] : '' ?>">
        </div>

        <!-- Matière -->
        <div class="col-md-3">
            <select name="id_matiere" class="form-select" required>
                <option value="">Matière</option>
                <?php
                $stmt = $pdo->query("SELECT id_matiere, nom_matiere FROM matieres");
                while ($matiere = $stmt->fetch()) {
                    $selected = ($isEditing && $matiere['id_matiere'] == $editData['id_matiere']) ? "selected" : "";
                    echo "<option value='{$matiere['id_matiere']}' $selected>{$matiere['nom_matiere']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Salle -->
        <div class="col-md-3">
            <input type="number" name="salle" class="form-control" placeholder="Salle" required value="<?= $isEditing ? $editData['salle'] : '' ?>">
        </div>

        <!-- Enseignant -->
        <div class="col-md-3">
            <select name="id_enseignant" class="form-select" required>
                <option value="">Choisir un enseignant</option>
                <?php
                $enseignants = $pdo->query("SELECT * FROM enseignants");
                while ($e = $enseignants->fetch()) {
                    $selected = ($isEditing && $e['id_enseignant'] == $editData['id_enseignant']) ? "selected" : "";
                    echo "<option value='{$e['id_enseignant']}' $selected>{$e['nom']} {$e['prenom']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-12">
            <?php if ($isEditing): ?>
                <button type="submit" name="modifier" class="btn btn-warning">Mettre à jour</button>
            <?php else: ?>
                <button type="submit" name="ajouter" class="btn btn-success">Ajouter</button>
            <?php endif; ?>
        </div>
    </form>

    <!-- TABLEAU -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Filière</th><th>Niveau</th><th>Jour</th><th>Heure</th><th>Matière</th><th>Salle</th><th>Enseignant</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT e.*, f.nom_filiere, m.nom_matiere, ens.nom AS nom_ens, ens.prenom AS prenom_ens 
                    FROM emploi_temps e
                    JOIN filieres f ON f.id_filiere = e.id_filiere
                    JOIN enseignants ens ON ens.id_enseignant = e.id_enseignant
                    JOIN matieres m ON m.id_matiere = e.id_matiere
                    ORDER BY f.nom_filiere, e.jour, e.heure_debut";
            $result = $pdo->query($sql);
            while ($row = $result->fetch()) {
                echo "<tr>
                    <td>{$row['nom_filiere']}</td>
                    <td>{$row['niveau']}</td>
                    <td>{$row['jour']}</td>
                    <td>{$row['heure_debut']} - {$row['heure_fin']}</td>
                    <td>{$row['nom_matiere']}</td>
                    <td>{$row['salle']}</td>
                    <td>{$row['nom_ens']} {$row['prenom_ens']}</td>
                    <td class='d-flex gap-1'>
                        <form method='post'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <input type='hidden' name='id_filiere' value='{$row['id_filiere']}'>
                            <input type='hidden' name='niveau' value='{$row['niveau']}'>
                            <input type='hidden' name='jour' value='{$row['jour']}'>
                            <input type='hidden' name='heure_debut' value='{$row['heure_debut']}'>
                            <input type='hidden' name='heure_fin' value='{$row['heure_fin']}'>
                            <input type='hidden' name='id_matiere' value='{$row['id_matiere']}'>
                            <input type='hidden' name='salle' value='{$row['salle']}'>
                            <input type='hidden' name='id_enseignant' value='{$row['id_enseignant']}'>
                            <button type='submit' name='edit' class='btn btn-sm btn-primary'>Modifier</button>
                        </form>
                        <a href='?supprimer={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Supprimer cette séance ?\")'>Supprimer</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
