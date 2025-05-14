<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Enseignants</title>
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
    .commentaire {
    font-style: italic;
    color: rgb(156, 165, 173);  
    text-align: center; 
    display: block;  
    width: 100%;  
    }

    .commentaire::before,
    .commentaire::after {
        content: '------------------------------------------------'; 
        color:rgb(171, 181, 190); 
        font-style: italic;  
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
    <h2 class="text-center">Gestion des Enseignants</h2>
    <br>
    

    <?php

    function genererMotDePasse($longueur = 8) {
        $lettres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chiffres = '0123456789';
        $symboles = '@#$%&*!?';

        $motDePasse = '';
        $motDePasse .= $lettres[random_int(0, strlen($lettres) - 1)];
        $motDePasse .= $symboles[random_int(0, strlen($symboles) - 1)];

        // Nombre de caractères restants à générer
        $reste = $longueur - strlen($motDePasse);

        // Tous les caractères possibles
        $tous = $lettres . $chiffres . $symboles;

        for ($i = 0; $i < $reste; $i++) {
            $motDePasse .= $tous[random_int(0, strlen($tous) - 1)];
        }

        // Mélanger les caractères pour plus d'aléatoire
        $motDePasse = str_shuffle($motDePasse);

        return $motDePasse;
    }

    $nom = $prenom = $specialite = $email = "";
    $editMode = false;

    if (isset($_POST['ajouter'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
   
        $sql = "INSERT INTO enseignants (nom, prenom, email) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $email]);
    
        $id_enseignant = $pdo->lastInsertId();
    
        $mot_de_passe = genererMotDePasse();
    
        $sql_user = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, id_enseignant)
                     VALUES (?, ?, ?, ?, 'enseignant', ?)";
        $stmt_user = $pdo->prepare($sql_user);
        $stmt_user->execute([$nom, $prenom, $email, $mot_de_passe, $id_enseignant]);
    
        echo "<div class='alert alert-success'>Enseignant ajouté avec succès !<br>
        <strong>Mot de passe généré :</strong> $mot_de_passe</div>";
    }
    
    

    if (isset($_GET['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM enseignants WHERE id_enseignant = ?");
        $stmt->execute([$_GET['delete']]);
        echo "<div class='alert alert-danger'>Enseignant supprimé.</div>";
    }

    if (isset($_GET['edit'])) {
        $stmt = $pdo->prepare("SELECT * FROM enseignants WHERE id_enseignant = ?");
        $stmt->execute([$_GET['edit']]);
        $enseignant = $stmt->fetch();
        if ($enseignant) {
            $nom = $enseignant['nom'];
            $prenom = $enseignant['prenom'];
            $email = $enseignant['email'];
            $editMode = true;
        }
    }

    if (isset($_POST['modifier'])) {
        $id = $_POST['id_enseignant'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
    
        $stmt = $pdo->prepare("UPDATE enseignants SET nom = ?, prenom = ?, email = ? WHERE id_enseignant = ?");
        $stmt->execute([$nom, $prenom, $email, $id]);
    
        $stmt_user = $pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ? WHERE id_enseignant = ?");
        $stmt_user->execute([$nom, $prenom, $email, $id]);
    
        echo "<div class='alert alert-info'>Enseignant modifié avec succès.</div>";
    }

    ?>

    <h4 class="commentaire">Ajout Des Enseignants</h4>
    <form method="post" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="nom" class="form-control" placeholder="Nom" required value="<?= htmlspecialchars($nom) ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="prenom" class="form-control" placeholder="Prénom" required value="<?= htmlspecialchars($prenom) ?>">
        </div>
        <div class="col-md-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required value="<?= htmlspecialchars($email) ?>">
        </div>
        <div class="col-md-3">
            <?php if ($editMode): ?>
                <input type="hidden" name="id_enseignant" value="<?= $_GET['edit'] ?>">
                <button type="submit" name="modifier" class="btn btn-warning">Mettre à jour</button>
            <?php else: ?>
                <button type="submit" name="ajouter" class="btn btn-success w-70">Ajouter Enseignant</button>
            <?php endif; ?>

        </div>
    </form>
    <h4 class="commentaire">Recherche Des Enseignants</h4>
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="email" name="search_email" class="form-control" placeholder="Rechercher par email" value="<?= isset($_GET['search_email']) ? htmlspecialchars($_GET['search_email']) : '' ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-info w-100">Rechercher</button>
        </div>
    </form>
    <h4 class="commentaire"></h4>
    <div class="col-md-2">
        <a href="enseignant.php" class="btn btn-secondary w-100">Réinitialiser</a>
    </div>
        <br>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Matières</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_GET['search_email']) && !empty($_GET['search_email'])) {
                    $search_email = $_GET['search_email'];
                    $stmt = $pdo->prepare("SELECT * FROM enseignants WHERE email LIKE ?");
                    $stmt->execute(["%$search_email%"]);
                } else {
                    $stmt = $pdo->query("
                        SELECT 
                            enseignants.id_enseignant, 
                            enseignants.nom, 
                            enseignants.prenom, 
                            enseignants.email, 
                            GROUP_CONCAT(matieres.nom_matiere SEPARATOR ', ') AS matieres
                        FROM 
                            enseignants
                        LEFT JOIN 
                            matieres ON enseignants.id_enseignant = matieres.id_enseignant
                        GROUP BY 
                            enseignants.id_enseignant
                    ");
                }

                foreach ($stmt as $row) {
                    $matieres = isset($row['matieres']) && $row['matieres'] !== null && $row['matieres'] !== '' 
                    ? $row['matieres'] 
                    : 'Aucune matière';

                
                    echo "<tr>
                        <td>{$row['id_enseignant']}</td>
                        <td>" . htmlspecialchars($row['nom']) . "</td>
                        <td>" . htmlspecialchars($row['prenom']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($matieres) . "</td>
                        <td>
                            <a href='?edit={$row['id_enseignant']}' class='btn btn-sm btn-primary'>Modifier</a>
                            <a href='?delete={$row['id_enseignant']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Supprimer cet enseignant ?\")'>Supprimer</a>
                        </td>
                    </tr>";
                }
                
                ?>
            </tbody>
        </table>
        <div class="text-center mb-4">
            <button onclick="exportToPDF()" class="btn btn-outline-dark">
                📄 Exporter la liste des enseignants en PDF
            </button>
        </div>
    </div>
    <script>
        function showSection(id) {
        document.querySelectorAll('.content-section').forEach(el => el.style.display = 'none');
        document.getElementById(id).style.display = 'block';
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: 'landscape',
        unit: 'mm',
        format: 'a4'
    });

    // 1. Titre et en-tête
    doc.setFontSize(18);
    doc.setTextColor(40);
    doc.setFont("helvetica", "bold");
    doc.text("LISTE DES ENSEIGNANTS", 105, 20, { align: 'center' });

    doc.setFontSize(12);
    doc.setFont("helvetica", "normal");
    doc.text("Établie le: " + new Date().toLocaleDateString('fr-FR'), 105, 27, { align: 'center' });

    const logoData = 'logo_est.png'; // Remplace par ton logo base64 si nécessaire
    doc.addImage(logoData, 'PNG', 20, 10, 30, 25);

    // 2. Préparation des données
    const headers = ["ID", "Nom", "Prénom", "Email", "Matières"];
    const rows = [];

    document.querySelectorAll('table tbody tr').forEach(tr => {
        const row = [];
        tr.querySelectorAll('td').forEach((td, index) => {
            if (index < 5) { // maintenant on récupère les 5 premières colonnes
                row.push(td.textContent.trim());
            }
        });
        if (row.length === 5) rows.push(row);
    });

    // 3. Vérification des données
    if (rows.length === 0) {
        alert("Aucun enseignant à exporter !");
        return;
    }

    // 4. Génération du tableau
    doc.autoTable({
        startY: 40,
        startX: 20,
        head: [headers],
        body: rows,
        styles: {
            fontSize: 12,
            cellPadding: 3,
            overflow: 'linebreak'
        },
        headStyles: {
            fillColor: [41, 128, 185],
            textColor: 255,
            fontStyle: 'bold'
        },
        columnStyles: {
            0: { cellWidth: 15 },  // ID
            1: { cellWidth: 30 },  // Nom
            2: { cellWidth: 30 },  // Prénom
            3: { cellWidth: 80 },  // Email
            4: { cellWidth: 60 }   // Matières
        }
    });

    // 5. Pied de page
    doc.setFontSize(10);
    doc.setTextColor(100);
    doc.text("École Supérieure de Technologie", 105, 200, { align: 'center' });

    // 6. Sauvegarde
    doc.save('liste_enseignants_' + new Date().toISOString().slice(0, 10) + '.pdf');
}

</script>

</body>
</html>