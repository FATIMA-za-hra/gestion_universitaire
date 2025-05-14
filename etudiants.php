<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Étudiants</title>
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
    <h2 class="text-center">Gestion des Étudiants</h2>
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

    $id_etudiant = $nom = $prenom = $date_naissance = $email = $telephone = $niveau = $id_filiere = "";
    $update = false;
    $id_update = null;
    $cherche = false;

    if (isset($_GET['edit'])) {
        $id_update = $_GET['edit'];
        $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id_etudiant = ?");
        $stmt->execute([$id_update]);
        $etudiant = $stmt->fetch();

        if ($etudiant) {
            $id_etudiant = $etudiant['id_etudiant'];
            $nom = $etudiant['nom'];
            $prenom = $etudiant['prenom'];
            $date_naissance = $etudiant['date_naissance'];
            $email = $etudiant['email'];
            $telephone = $etudiant['telephone'];
            $niveau = $etudiant['niveau'];
            $id_filiere = $etudiant['id_filiere'];
            $update = true;
        }
    }

    if (isset($_POST['ajouter'])) {
        $id_etudiant = $_POST['id_etudiant'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $date_naissance = $_POST['date_naissance'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $niveau = $_POST['niveau'];
        $id_filiere = $_POST['id_filiere'];
    
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM etudiants WHERE id_etudiant = ?");
        $stmt->execute([$id_etudiant]);
        if ($stmt->fetchColumn() > 0) {
            echo "<div class='alert alert-danger'>Un étudiant avec ce code Apogée existe déjà.</div>";
        } else {
            $sql = "INSERT INTO etudiants (id_etudiant, nom, prenom, date_naissance, email, telephone, niveau, id_filiere)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id_etudiant, $nom, $prenom, $date_naissance, $email, $telephone, $niveau, $id_filiere]);
        
            $mot_de_passe = genererMotDePasse();
        
            $sql_user = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_etudiant, role)
                        VALUES (?, ?, ?, ?, ?, 'etudiant')";
            $stmt_user = $pdo->prepare($sql_user);
            $stmt_user->execute([$nom, $prenom, $email, $mot_de_passe, $id_etudiant]);
        
            echo "<div class='alert alert-success'>Étudiant ajouté avec succès !<br>
                <strong>Mot de passe généré :</strong> $mot_de_passe</div>";
        }

    }
    
    if (isset($_POST['modifier'])) {
        $sql = "UPDATE etudiants SET id_etudiant = ?, nom = ?, prenom = ?, date_naissance = ?, email = ?, telephone = ?, niveau= ?, id_filiere = ? WHERE id_etudiant = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['id_etudiant'],$_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['email'], $_POST['telephone'],$_POST['niveau'], $_POST['id_filiere'], $_POST['id_etudiant']]);

        $sql_user = "UPDATE utilisateurs SET nom = ?, prenom = ?, email = ? WHERE id_etudiant = ?";
        $stmt_user = $pdo->prepare($sql_user);
        $stmt_user->execute([
            $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['id_etudiant']
        ]);

        echo "<div class='alert alert-warning'>Étudiant mis à jour avec succès !</div>";
    }

    if (isset($_GET['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM etudiants WHERE id_etudiant = ?");
        $stmt->execute([$_GET['delete']]);
        echo "<div class='alert alert-danger'>Étudiant supprimé.</div>";
    }
    $sql = "SELECT e.*, f.nom_filiere 
        FROM etudiants e 
        JOIN filieres f ON e.id_filiere = f.id_filiere";

    $conditions = [];
    $params = [];

    // Gestion recherche (POST)
    if (!empty($_POST['cherche_id'])) {
        $conditions[] = "e.id_etudiant = ?";
        $params[] = $_POST['cherche_id'];
    }

    // Gestion filtres (GET)
    if (!empty($_GET['filtre_id'])) {
        $conditions[] = "e.id_etudiant = ?";
        $params[] = $_GET['filtre_id'];
    }

    if (!empty($_GET['filtre_nom'])) {
        $conditions[] = "e.nom LIKE ?";
        $params[] = '%' . $_GET['filtre_nom'] . '%';
    }

    if (!empty($_GET['filtre_prenom'])) {
        $conditions[] = "e.prenom LIKE ?";
        $params[] = '%' . $_GET['filtre_prenom'] . '%';
    }

    if (!empty($_GET['filtre_naissance'])) {
        $conditions[] = "e.date_naissance = ?";
        $params[] = $_GET['filtre_naissance'];
    }

    if (!empty($_GET['filtre_email'])) {
        $conditions[] = "e.email LIKE ?";
        $params[] = '%' . $_GET['filtre_email'] . '%';
    }

    if (!empty($_GET['filtre_niveau'])) {
        $conditions[] = "e.niveau = ?";
        $params[] = $_GET['filtre_niveau'];
    }

    if (!empty($_GET['filtre_filiere'])) {
        $conditions[] = "e.id_filiere = ?";
        $params[] = $_GET['filtre_filiere'];
    }

    // Construction finale de la requête
    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY e.nom";

    // Exécution
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $etudiants = $stmt->fetchAll();
    
    ?>

    <form method="post" class="row g-3 mb-4">
    <h4 class="commentaire">Ajout Des Étudiants</h4>
        <?php if ($update): ?>
        <input type="hidden" name="id_etudiant" value="<?= $id_etudiant ?>">
        <?php endif ; ?>
        <div class="col-md-3">
            <input type="number" name="id_etudiant" class="form-control" placeholder="Code apogée" value="<?= $id_etudiant ?>" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="nom" class="form-control" placeholder="Nom" value="<?= $nom ?>" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="prenom" class="form-control" placeholder="Prénom" value="<?= $prenom ?>" required>
        </div>
        <div class="col-md-3">
            <input type="date" name="date_naissance" class="form-control" value="<?= $date_naissance ?>" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?>" required>
        </div>
        <div class="col-md-3">
            <input type="tel" name="telephone" class="form-control" placeholder="Numéro de téléphone" value="<?= $telephone ?>" required>
        </div>
        <div class="col-md-3">
            <select name="niveau" class="form-select" required>
                <option value="">Choisir un diplome</option>
                <option value="DUT" <?= ($niveau == 'DUT') ? 'selected' : '' ?>>DUT</option>
                <option value="LICENSE" <?= ($niveau == 'LICENSE') ? 'selected' : '' ?>>License</option>

            </select>
        </div>
        <div class="col-md-3">
            <select name="id_filiere" class="form-select" required>
                <option value="">Choisir une filière</option>
                <?php
                $stmt = $pdo->query("SELECT * FROM filieres");
                while ($f = $stmt->fetch()) {
                    $selected = ($f['id_filiere'] == $id_filiere) ? "selected" : "";
                    echo "<option value='{$f['id_filiere']}' $selected>{$f['nom_filiere']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-12">
            <?php if ($update): ?>
                <button type="submit" name="modifier" class="btn btn-warning">Mettre à jour</button>
                <a href="etudiants.php" class="btn btn-secondary">Annuler</a>
            <?php else: ?>
                <button type="submit" name="ajouter" class="btn btn-success">Ajouter Étudiant</button>
            <?php endif; ?>
        </div>
    </form>
    <h4 class="commentaire">Recherche Des Étudiants</h4>
    <form method="post" class="row g-2 align-items-center mb-3">
        <div class="col-md-3">
            <input type="text" name="cherche_id" class="form-control" placeholder="Code Apogée" value="<?= $_POST['cherche_id'] ?? '' ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-info w-70">Rechercher</button>
        </div>
    </form>


    <h4 class="commentaire">Filtrage Des Étudiants</h4>

<!-- Formulaire de filtre -->
<form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="filtre_id" class="form-control" placeholder="Code Apogée" value="<?= $_GET['filtre_id'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <input type="text" name="filtre_nom" class="form-control" placeholder="Nom" value="<?= $_GET['filtre_nom'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <input type="text" name="filtre_prenom" class="form-control" placeholder="Prénom" value="<?= $_GET['filtre_prenom'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <input type="date" name="filtre_naissance" class="form-control" value="<?= $_GET['filtre_naissance'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <input type="text" name="filtre_email" class="form-control" placeholder="Email" value="<?= $_GET['filtre_email'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <select name="filtre_niveau" class="form-select">
            <option value="">Tous les niveaux</option>
            <option value="DUT" <?= ($_GET['filtre_niveau'] ?? '') == 'DUT' ? 'selected' : '' ?>>DUT</option>
            <option value="LICENSE" <?= ($_GET['filtre_niveau'] ?? '') == 'LICENSE' ? 'selected' : '' ?>>LICENSE</option>
        </select>
    </div>
    <div class="col-md-3">
        <select name="filtre_filiere" class="form-select">
            <option value="">Toutes les filières</option>
            <?php
            $stmt_f = $pdo->query("SELECT * FROM filieres");
            while ($f = $stmt_f->fetch()) {
                $selected = ($_GET['filtre_filiere'] ?? '') == $f['id_filiere'] ? 'selected' : '';
                echo "<option value='{$f['id_filiere']}' $selected>{$f['nom_filiere']}</option>";
            }
            ?>
        </select>
    </div>
    <br>
    <div class="col-md-1 mt-2">
        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
    </div>
</form>

<h4 class="commentaire"></h4>
<div class="col-md-2">
    <a href="etudiants.php" class="btn btn-secondary w-100">Réinitialiser</a>
    <br>
</div>

<!-- Affichage du tableau des étudiants -->
<br>
<div id="table-etudiants">
    <table class="table table-bordered table-hover table-striped">
    <thead class="table-dark">
        <tr>
            <th>C.A</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date de Naissance</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Niveau</th>
            <th>Filière</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($etudiants as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id_etudiant']) ?></td>
                <td><?= htmlspecialchars($row['nom']) ?></td>
                <td><?= htmlspecialchars($row['prenom']) ?></td>
                <td><?= htmlspecialchars($row['date_naissance']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['telephone']) ?></td>
                <td><?= htmlspecialchars($row['niveau']) ?></td>
                <td><?= htmlspecialchars($row['nom_filiere']) ?></td>
                <td>
                    <a href="?edit=<?= $row['id_etudiant'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                    <a href="?delete=<?= $row['id_etudiant'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet étudiant ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
    </div>
    <script>
        function showSection(id) {
        document.querySelectorAll('.content-section').forEach(el => el.style.display = 'none');
        document.getElementById(id).style.display = 'block';
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!--Bouton Export PDF -->
    <div class="text-center mb-4">
        <button onclick="exportToPDF()" class="btn btn-outline-dark">
            📄 Exporter la liste des étudiants en PDF
        </button>
    </div>
    <div class="text-center mb-4">
        <a href="admin_attestation.php">
            <button class="btn btn-outline-dark"> 📄 Voir les demandes d'attestations</button>
        </a>
    </div>

    <!-- Librairies jsPDF et html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
function exportToPDF() {
    // 1. Vérification des bibliothèques
    if (!window.jspdf || !window.jspdf.jsPDF) {
        alert("Erreur : La bibliothèque jsPDF n'est pas chargée !");
        console.error("jsPDF non détecté");
        return;
    }

    const { jsPDF } = window.jspdf;
    
    try {
        // 2. Création du document
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        const logoData = 'logo_est.png'; // Votre logo en base64
        doc.addImage(logoData, 'PNG', 20, 5, 30, 25);

        // 3. Titre et méta-données
        doc.setFont("helvetica", "bold");
        doc.setFontSize(16);
        doc.text("LISTE DES ÉTUDIANTS", 105, 20, {align: 'center'});
        
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        doc.text(`Généré le: ${new Date().toLocaleDateString()}`, 105, 27, {align: 'center'});

        // 4. Préparation des données
        const headers = [];
        const rows = [];

        
        // Récupération des en-têtes (exclure Actions)
        document.querySelectorAll('#table-etudiants thead th').forEach((th, index) => {
            if(index < 8) headers.push(th.textContent.trim());
        });

        // Récupération des lignes
        document.querySelectorAll('#table-etudiants tbody tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('td').forEach((td, index) => {
                if(index < 8) row.push(td.textContent.trim());
            });
            if(row.length > 0) rows.push(row);
        });

        // 5. Vérification des données
        if(rows.length === 0) {
            alert("Aucune donnée à exporter !");
            return;
        }

        // 6. Génération du tableau
        doc.autoTable({
            startY: 40,
            head: [headers],
            body: rows,
            styles: {
                fontSize: 8,
                cellPadding: 3,
                overflow: 'linebreak'
            },
            columnStyles: {
                0: {cellWidth: 25}, // Code Apogée
                1: {cellWidth: 20}, // Nom
                2: {cellWidth: 20}, // Prénom
                3: {cellWidth: 35}, // Date Naissance
                4: {cellWidth: 45}, // Email
                5: {cellWidth: 25}, // Téléphone
                6: {cellWidth: 20}, // Niveau
                7: {cellWidth: 45}  // Filière
            }
        });

        // 7. Sauvegarde
        doc.save(`liste_etudiants_${new Date().toISOString().slice(0,10)}.pdf`);
        
    } catch (error) {
        console.error("Erreur lors de la génération PDF:", error);
        alert("Une erreur est survenue lors de la génération du PDF");
    }
}
</script>


</body>
</html>