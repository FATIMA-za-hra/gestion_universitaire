<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Filières</title>
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
    .toggle-btn {
        display: block;
        width: 100%;
        text-align: center;
        background-color: #212529;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s, transform 0.2s;
    }

    .toggle-btn:hover {
        background-color: #212529;
        cursor: pointer;
        transform: scale(1.01);
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

    <div  class="p-4">
    <h2 class="text-center">Gestion des Filières</h2>
    <br>

    <?php
    // Variables d'affichage
    $nomFiliere = "";
    $editMode = false;

    // Ajouter filière
    if (isset($_POST['ajouter'])) {
        $sql = "INSERT INTO filieres (nom_filiere) VALUES (?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['nom_filiere']]);
        echo "<div class='alert alert-success'>Filière ajoutée avec succès !</div>";
    }

    // Supprimer filière
    if (isset($_GET['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM filieres WHERE id_filiere = ?");
        $stmt->execute([$_GET['delete']]);
        echo "<div class='alert alert-danger'>Filière supprimée.</div>";
    }

    // Récupérer les données pour modification
    if (isset($_GET['edit'])) {
        $stmt = $pdo->prepare("SELECT * FROM filieres WHERE id_filiere = ?");
        $stmt->execute([$_GET['edit']]);
        $filiere = $stmt->fetch();
        $nomFiliere = $filiere['nom_filiere'];
        $editMode = true;
    }

    // Modifier la filière
    if (isset($_POST['modifier'])) {
        $stmt = $pdo->prepare("UPDATE filieres SET nom_filiere = ? WHERE id_filiere = ?");
        $stmt->execute([$_POST['nom_filiere'], $_POST['id_filiere']]);
        echo "<div class='alert alert-info'>Filière modifiée avec succès.</div>";
    }
    ?>

    <!-- Formulaire ajout / modification -->
    <form method="post" class="row g-3 mb-4">
        <div class="col-md-8">
            <input type="text" name="nom_filiere" class="form-control" placeholder="Nom de la filière" required value="<?= htmlspecialchars($nomFiliere) ?>">
            <?php if ($editMode): ?>
                <input type="hidden" name="id_filiere" value="<?= $_GET['edit'] ?>">
            <?php endif; ?>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <?php if ($editMode): ?>
                <button type="submit" name="modifier" class="btn btn-warning w-50">Mettre à jour</button>
                <a href="filiere.php" class="btn btn-secondary w-50">Annuler</a>
            <?php else: ?>
                <button type="submit" name="ajouter" class="btn btn-success w-50">Ajouter</button>
                <a href="filiere.php" class="btn btn-secondary w-50">Réinitialiser</a>
            <?php endif; ?>
        </div>
    </form>


    <!-- Liste des filières -->
    
    <div class="row">
<?php
$stmt = $pdo->query("SELECT * FROM filieres");
foreach ($stmt as $row) {
    // Récupérer les étudiants
    $stmtEtudiants = $pdo->prepare("SELECT nom, prenom FROM etudiants WHERE id_filiere = ?");
    $stmtEtudiants->execute([$row['id_filiere']]);
    $etudiants = $stmtEtudiants->fetchAll();

    // Récupérer les matières
    $stmtMatieres = $pdo->prepare("SELECT nom_matiere FROM matieres WHERE id_filiere = ?");
    $stmtMatieres->execute([$row['id_filiere']]);
    $matieres = $stmtMatieres->fetchAll();
?>
    <div class="col-md-6 mb-4">
    <div class="card border-dark shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= htmlspecialchars($row['nom_filiere']) ?></h5>
            <div>
                <a href="?edit=<?= $row['id_filiere'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                <a href="?delete=<?= $row['id_filiere'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette filière ?')">Supprimer</a>
            </div>
        </div>
        <div class="card-body row">
            <?php $maxShow = 3; ?>
            <div class="col-md-6">
                <h6 class="text-dark">👩‍🎓 Étudiants :</h6>
                <?php if (count($etudiants) > 0): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($etudiants as $index => $etudiant): ?>
                            <li class="list-group-item etudiant-<?= $row['id_filiere'] ?>" style="<?= $index >= $maxShow ? 'display:none;' : '' ?>">
                                <?= htmlspecialchars($etudiant['nom']) . ' ' . htmlspecialchars($etudiant['prenom']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Aucun étudiant.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h6 class="text-dark">📚 Matières :</h6>
                <?php if (count($matieres) > 0): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($matieres as $index => $matiere): ?>
                            <li class="list-group-item matiere-<?= $row['id_filiere'] ?>" style="<?= $index >= $maxShow ? 'display:none;' : '' ?>">
                                <?= htmlspecialchars($matiere['nom_matiere']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Aucune matière.</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (count($etudiants) > $maxShow || count($matieres) > $maxShow): ?>
            <button class="toggle-btn" onclick="toggleContenu(<?= $row['id_filiere'] ?>, this)">Voir tout ↓</button>
        <?php endif; ?>
    </div>
</div>

<?php } ?>
</div>
     
    <script>
    function showSection(id) {
      document.querySelectorAll('.content-section').forEach(el => el.style.display = 'none');
      document.getElementById(id).style.display = 'block';
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleContenu(filiereId, btn) {
            const etudiants = document.querySelectorAll(`.etudiant-${filiereId}`);
            const matieres = document.querySelectorAll(`.matiere-${filiereId}`);
            const isHidden = etudiants[3]?.style.display !== 'none';

            for (let i = 3; i < etudiants.length; i++) {
                etudiants[i].style.display = isHidden ? 'none' : '';
            }

            for (let i = 3; i < matieres.length; i++) {
                matieres[i].style.display = isHidden ? 'none' : '';
            }

            btn.innerText = isHidden ? 'Voir tout ↓' : 'Voir moins ↑';
        }
    </script>
</body>
</html>
