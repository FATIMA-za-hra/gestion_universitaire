<?php include('db.php'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Évaluations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
    canvas {
        width: 100% !important;
        height: 300px !important;
        max-height: 300px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
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
<h2 class="text-center">Gestion des Évaluations</h2>
<br>

<?php
// Modifier une évaluation
if (isset($_POST['modifier'])) {
    $sql = "UPDATE evaluations SET id_etudiant = ?, id_matiere = ?, note = ?, type = ? WHERE id_evaluation = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['id_etudiant'],
        $_POST['id_matiere'],
        $_POST['note'],
        $_POST['type'],
        $_POST['id_evaluation']
    ]);
    echo "<div class='alert alert-info'>Évaluation modifiée avec succès.</div>";
}

// Ajouter une évaluation
if (isset($_POST['ajouter'])) {
    $sql = "INSERT INTO evaluations (id_etudiant, id_matiere, note, type) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['id_etudiant'],
        $_POST['id_matiere'],
        $_POST['note'],
        $_POST['type']
    ]);
    echo "<div class='alert alert-success'>Évaluation enregistrée !</div>";
}

// Supprimer une évaluation
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM evaluations WHERE id_evaluation = ?");
    $stmt->execute([$_GET['delete']]);
    echo "<div class='alert alert-danger'>Évaluation supprimée.</div>";
}
?>

<?php
// Données pour les matières
$matiereData = [];
$stmt = $pdo->query("SELECT m.nom_matiere, ROUND(AVG(e.note), 2) AS moyenne
                     FROM evaluations e
                     JOIN matieres m ON e.id_matiere = m.id_matiere
                     GROUP BY m.nom_matiere");
while ($row = $stmt->fetch()) {
    $matiereData['labels'][] = $row['nom_matiere'];
    $matiereData['data'][] = $row['moyenne'];
}

// Données pour les filières
$filiereData = [];
$stmt = $pdo->query("SELECT f.nom_filiere, ROUND(AVG(e.note), 2) AS moyenne
                     FROM evaluations e
                     JOIN etudiants et ON e.id_etudiant = et.id_etudiant
                     JOIN filieres f ON et.id_filiere = f.id_filiere
                     GROUP BY f.nom_filiere");
while ($row = $stmt->fetch()) {
    $filiereData['labels'][] = $row['nom_filiere'];
    $filiereData['data'][] = $row['moyenne'];
}
?>

<?php
// Répartition validation
$validationData = ['Validé' => 0, 'Rattrapage' => 0, 'Ajourné' => 0];
$stmt = $pdo->query("SELECT note FROM evaluations");

$total = 0;
while ($row = $stmt->fetch()) {
    $note = $row['note'];
    if ($note >= 12) $validationData['Validé']++;
    elseif ($note >= 6) $validationData['Rattrapage']++;
    else $validationData['Ajourné']++;
    $total++;
}

// Calcul pourcentages
$validationPercentages = [];
foreach ($validationData as $key => $count) {
    $validationPercentages[$key] = round(($count / $total) * 100, 2);
}
?>


<!-- Formulaire ajout ou modification -->
<form method="post" class="row g-3 mb-4">
    <?php
    $isEditing = false;
    if (isset($_GET['edit'])) {
        $isEditing = true;
        $id_eval = $_GET['edit'];
        $stmt = $pdo->prepare("SELECT * FROM evaluations WHERE id_evaluation = ?");
        $stmt->execute([$id_eval]);
        $eval = $stmt->fetch();
    }
    ?>
    <input type="hidden" name="id_evaluation" value="<?= $isEditing ? $eval['id_evaluation'] : '' ?>">

    <div class="col-md-3">
        <select name="id_etudiant" class="form-select" required>
            <option value="">Étudiant</option>
            <?php
            $stmt = $pdo->query("SELECT id_etudiant, nom, prenom FROM etudiants");
            while ($etudiant = $stmt->fetch()) {
                $selected = ($isEditing && $etudiant['id_etudiant'] == $eval['id_etudiant']) ? "selected" : "";
                echo "<option value='{$etudiant['id_etudiant']}' $selected>{$etudiant['nom']} {$etudiant['prenom']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="id_matiere" class="form-select" required>
            <option value="">Matière</option>
            <?php
            $stmt = $pdo->query("SELECT id_matiere, nom_matiere FROM matieres");
            while ($matiere = $stmt->fetch()) {
                $selected = ($isEditing && $matiere['id_matiere'] == $eval['id_matiere']) ? "selected" : "";
                echo "<option value='{$matiere['id_matiere']}' $selected>{$matiere['nom_matiere']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-2">
        <input type="number" name="note" min="0" max="20" step="0.01" class="form-control" placeholder="Note" required value="<?= $isEditing ? $eval['note'] : '' ?>">
    </div>
    <div class="col-md-2">
        <select name="type" class="form-select" required>
            <option value="">Type</option>
            <option value="Examen" <?= $isEditing && $eval['type'] == 'Examen' ? 'selected' : '' ?>>Examen</option>
            <option value="Contrôle" <?= $isEditing && $eval['type'] == 'Contrôle' ? 'selected' : '' ?>>Contrôle</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" name="<?= $isEditing ? 'modifier' : 'ajouter' ?>" class="btn btn-<?= $isEditing ? 'warning' : 'success' ?> w-100">
            <?= $isEditing ? 'Mettre à jour' : 'Ajouter' ?>
        </button>
    </div>
</form>

<!-- Tableau des évaluations -->
<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th><th>Étudiant</th><th>Matière</th><th>Note</th><th>Type</th><th>Validation</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $sql = "SELECT e.id_evaluation, et.nom AS nom_etudiant, et.prenom, m.nom_matiere, e.note, e.type
                    FROM evaluations e
                    JOIN etudiants et ON e.id_etudiant = et.id_etudiant
                    JOIN matieres m ON e.id_matiere = m.id_matiere";
            $stmt = $pdo->query($sql);
            foreach ($stmt as $row) {
                // Détermination de la validation
                $validation = '';
                if ($row['note'] >= 12) {
                    $validation = "<span class='text-success fw-bold'>✅ Validé</span>";
                } elseif ($row['note'] >= 6) {
                    $validation = "<span class='text-warning fw-bold'>⚠️ Rattrapage</span>";
                } else {
                    $validation = "<span class='text-danger fw-bold'>❌ Ajourné</span>";
                }

                echo "<tr>
                    <td>{$row['id_evaluation']}</td>
                    <td>{$row['nom_etudiant']} {$row['prenom']}</td>
                    <td>{$row['nom_matiere']}</td>
                    <td>{$row['note']}</td>
                    <td>{$row['type']}</td>
                    <td>$validation</td>
                    <td>
                        <a href='?edit={$row['id_evaluation']}' class='btn btn-sm btn-primary'>Modifier</a>
                        <a href='?delete={$row['id_evaluation']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Supprimer cette évaluation ?\")'>Supprimer</a>
                    </td>
                </tr>";
            }
        ?>
    </tbody>
</table>

<h4 class="mt-5">Moyenne par étudiant</h4>
<table class="table table-bordered" id="tableMoyenneEtudiant">
<thead class="table-dark"><tr><th>Étudiant</th><th>Moyenne</th></tr></thead>
<tbody>
<?php
$stmt = $pdo->query("SELECT e.id_etudiant, et.nom, et.prenom, ROUND(AVG(e.note), 2) AS moyenne
                     FROM evaluations e
                     JOIN etudiants et ON e.id_etudiant = et.id_etudiant
                     GROUP BY e.id_etudiant");
while ($row = $stmt->fetch()) {
    echo "<tr><td>{$row['nom']} {$row['prenom']}</td><td>{$row['moyenne']}</td></tr>";
}
?>
</tbody>
</table>

<h4 class="mt-4">Moyenne par filière</h4>
<table class="table table-bordered" id="tableMoyenneFiliere">
<thead class="table-dark"><tr><th>Filière</th><th>Moyenne</th></tr></thead>
<tbody>
<?php
$stmt = $pdo->query("SELECT f.nom_filiere, ROUND(AVG(e.note), 2) AS moyenne
                     FROM evaluations e
                     JOIN etudiants et ON e.id_etudiant = et.id_etudiant
                     JOIN filieres f ON et.id_filiere = f.id_filiere
                     GROUP BY f.id_filiere");
while ($row = $stmt->fetch()) {
    echo "<tr><td>{$row['nom_filiere']}</td><td>{$row['moyenne']}</td></tr>";
}
?>
</tbody>
</table>

<button class="btn btn-outline-dark btn-generate-pdf">
    <i class="fas fa-file-pdf"></i>📄 Générer le PDF complet
</button>

<div class="row mt-5 text-center">
    <div class="col-md-4">
        <div class="p-3 shadow rounded bg-white">
            <h5>Moyenne par matière</h5>
            <canvas id="matiereChart"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 shadow rounded bg-white">
            <h5>Moyenne par filière</h5>
            <canvas id="filiereChart"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 shadow rounded bg-white">
            <h5>Répartition des validations</h5>
            <canvas id="validationChart"></canvas>
        </div>
    </div>
</div>



</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Configuration commune des graphiques
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: { font: { size: 14 } }
        }
    },
    scales: {
        x: {
            ticks: {
                maxRotation: 45,
                minRotation: 45,
                font: { size: 12 }
            }
        },
        y: {
            beginAtZero: true,
            max: 20,
            ticks: { font: { size: 12 } }
        }
    }
};

// Initialisation des graphiques
const matiereChart = new Chart(document.getElementById('matiereChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($matiereData['labels']) ?>,
        datasets: [{
            label: 'Moyenne',
            data: <?= json_encode($matiereData['data']) ?>,
            backgroundColor: 'rgba(13, 110, 253, 0.5)'
        }]
    },
    options: commonOptions
});

const filiereChart = new Chart(document.getElementById('filiereChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($filiereData['labels']) ?>,
        datasets: [{
            label: 'Moyenne',
            data: <?= json_encode($filiereData['data']) ?>,
            backgroundColor: 'rgba(25, 135, 84, 0.5)'
        }]
    },
    options: commonOptions
});

const validationChart = new Chart(document.getElementById('validationChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_keys($validationPercentages)) ?>,
        datasets: [{
            data: <?= json_encode(array_values($validationPercentages)) ?>,
            backgroundColor: [
                'rgba(25, 135, 84, 0.7)',
                'rgba(255, 193, 7, 0.7)',
                'rgba(220, 53, 69, 0.7)'
            ]
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

</script>


<script>
// Fonction principale pour générer le PDF
async function generateCompletePDF() {
    try {
        // 1. Initialisation
        console.log("Début de la génération du PDF...");
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });

        const logoData = 'logo_est.png'; 
        doc.addImage(logoData, 'PNG', 15, 15, 30, 20);

        // 2. En-tête institutionnel
        doc.setFont("helvetica", "bold");
        doc.setFontSize(8);
        doc.text("Royaume du Maroc", 140, 20);
        doc.text("Université Mohammed V de Rabat", 137, 26);
        doc.text("Ecole Supérieure de Technologie de Salé", 137, 32);

        // 3. Titre principal
        doc.setFontSize(16);
        doc.text("RELEVÉ DES NOTES OFFICIEL", 105, 45, {align: 'center'});
        doc.setFontSize(10);
        doc.text("Année Universitaire 2023/2024 - Généré le: " + new Date().toLocaleDateString('fr-FR'), 105, 52, {align: 'center'});

        // 4. Tableau principal
        const tableHeaders = ["ID", "Étudiant", "Matière", "Note", "Type", "Validation"];
        const tableRows = [];
        
        document.querySelectorAll('table.table-bordered tbody tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('td').forEach((td, index) => {
                if(index < 6) row.push(td.textContent.trim());
            });
            if(row.length === 6) tableRows.push(row);
        });

        doc.autoTable({
            startY: 60,
            head: [tableHeaders],
            body: tableRows,
            styles: { fontSize: 8 },
            headStyles: { 
                fillColor: [41, 128, 185],
                textColor: 255
            }
        });

        // 5. Tableaux de synthèse
        function addSummaryTable(selector, title) {
            const headers = [];
            const data = [];
            
            document.querySelectorAll(`${selector} thead th`).forEach(th => {
                headers.push(th.textContent.trim());
            });
            
            document.querySelectorAll(`${selector} tbody tr`).forEach(tr => {
                const row = [];
                tr.querySelectorAll('td').forEach(td => {
                    row.push(td.textContent.trim());
                });
                data.push(row);
            });
            
            doc.autoTable({
                startY: doc.lastAutoTable.finalY + 15,
                head: [headers],
                body: data,
                styles: { fontSize: 8 }
            });
        }

        addSummaryTable("#tableMoyenneEtudiant", "Moyennes par Étudiant");
        addSummaryTable("#tableMoyenneFiliere", "Moyennes par Filière");

        // 6. Graphiques (nouvelle page)
        doc.addPage();
        doc.setFontSize(14);
        doc.text("STATISTIQUES DES NOTES", 105, 20, {align: 'center'});

        // Fonction pour capturer les graphiques
        async function addChartToPDF(chartId, title, yPosition) {
            const canvas = document.getElementById(chartId);
            const imgData = await html2canvas(canvas, {
                scale: 2,
                backgroundColor: '#FFFFFF'
            }).then(canvas => canvas.toDataURL('image/png'));
            
            doc.addImage(imgData, 'PNG', 40, yPosition, 130, 60);
            doc.text(title, 105, yPosition - 5, {align: 'center'});
        }

        await addChartToPDF('matiereChart', "Moyennes par Matière", 30);
        await addChartToPDF('filiereChart', "Moyennes par Filière", 120);
        await addChartToPDF('validationChart', "Répartition des Validations", 210);

        // 7. Pied de page
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.line(20, 290, 190, 290);
        doc.text("Direction des Études - EST Salé", 105, 295, {align: 'center'});

        // 8. Sauvegarde
        doc.save('Releve_Notes_Complet_' + new Date().toISOString().slice(0,10) + '.pdf');
        console.log("PDF généré avec succès !");

    } catch (error) {
        console.error("Erreur lors de la génération:", error);
        alert("Erreur: " + error.message);
    }
}

// Associer la fonction au bouton
document.querySelector('.btn-generate-pdf').addEventListener('click', generateCompletePDF);
</script>
</body>
</html>
