<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'enseignant') {
    header('Location: login.php');
    exit;
}

$enseignant = $_SESSION['utilisateur'];
$id_enseignant = $enseignant['id_enseignant'];

//$id_enseignant = $enseignant['utilisateur']['id_enseignant'];

$active_section = 'accueil';

if (!empty($_GET['matiere']) || !empty($_GET['filiere']) || ($_SERVER['REQUEST_METHOD'] === 'POST')) {
    $active_section = 'gestion';
}

$alert = "";

// Traitement des notes
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $id_etudiant = $_POST['id_etudiant'];
    $id_matiere = $_POST['id_matiere'];
    $note = $_POST['note'] ?? null;
    $type = $_POST['type'] ?? null;
    $action = $_POST['action'];

    if ($action == 'ajouter') {
        // Ensure id_enseignant is not included if it doesn't exist in the table
        $stmt = $pdo->prepare("INSERT INTO evaluations (id_etudiant, id_matiere, note, type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_etudiant, $id_matiere, $note, $type]);
        $alert = "✅ Note ajoutée avec succès.";
    } elseif ($action == 'modifier') {
        $stmt = $pdo->prepare("UPDATE evaluations SET note = ?, type = ? WHERE id_etudiant = ? AND id_matiere = ?");
        $stmt->execute([$note, $type, $id_etudiant, $id_matiere]);
        $alert = "✏️ Note modifiée avec succès.";
    } elseif ($action == 'supprimer') {
        $stmt = $pdo->prepare("DELETE FROM evaluations WHERE id_etudiant = ? AND id_matiere = ?");
        $stmt->execute([$id_etudiant, $id_matiere]);
        $alert = "🗑️ Note supprimée avec succès.";
    }
}

// Check if a subject and branch are selected
$selected_matiere = isset($_GET['matiere']) ? $_GET['matiere'] : '';
$selected_filiere = isset($_GET['filiere']) ? $_GET['filiere'] : '';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Enseignant</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            margin: 0; padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        body {
            background-image: url('ests_col.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #222;
            min-height: 100vh;
        }
        nav {
            background: rgba(255,255,255,0.95);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }  nav img {
            height: 40px;
        }
        nav .links a , .logout-btn{
            margin-left: 25px;
            text-decoration: none;
            font-weight: bold;
            color: #0077cc;
            transition: color 0.3s;
            background: none; 
            border: none; 
            cursor: pointer;
            display: inline-flex;
            align-items: center; 
        }
        nav .links a:hover ,.logout-btn:hover {
            color: #005ea0;
        }

        .logout-btn {
            color: red; 
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: rgba(255,255,255,0.94);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        .content-section {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        .active-section {
            display: block;
        }
        .section-header {
            font-size: 22px;
            color: #0077cc;
            margin-bottom: 20px;
            border-bottom: 2px solid #0077cc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f0f8ff;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn {
            padding: 10px 15px;
            background-color: #0077cc;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #005ea0;
        }
        .alert {
            padding: 10px;
            background: #e7f3ff;
            color: #055160;
            border-left: 5px solid #0077cc;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        @media (max-width: 600px) {
            nav {
                flex-direction: column;
                align-items: flex-start;
            }
            nav .links a {
                margin: 10px 0;
            }
        }
    
        body.dark {
            background-color: #1e1e1e;
            background-image: url('ests_col.jpg');
            color: #ddd;
        }

        body.dark .container {
            background: rgba(40, 40, 40, 0.96);
            color: #eee;
        }

        body.dark nav {
            background: rgba(30,30,30,0.95);
        }

        body.dark nav .links a {
            color: #4faaff;
        }

        body.dark nav .links a:hover {
            color: #77caff;
        }
        body.dark .section-header {
            color: #4faaff;
            border-bottom-color: #4faaff;
        }

        body.dark table th {
            background-color: #333;
            color: #fff;
        }

        body.dark table td {
            background-color: #222;
            color: #ddd;
        }

        body.dark input, body.dark select {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
        }

        body.dark .btn {
            background-color: #4faaff;
            color: #000;
        }
        body.dark .btn:hover {
            background-color: #2f7abf;
        }

        .toggle-darkmode {
            cursor: pointer;
            background: none;
            border: none;
            font-size: 18px;
            margin-left: 20px;
            color: #0077cc;
            transition: color 0.3s;
        }

        body.dark .toggle-darkmode {
            color: #4faaff;
        }

        .links a {
        z-index: 1000;
        position: relative;
    }

        </style>
</head>
<body>
<nav>
    <img src="logo_est.png" alt="EST Salé">
    <div class="links">
        <a href="#accueil">🏠 Accueil</a>
        <a href="#etudiants">👨‍🎓 Mes Étudiants</a>
        <a href="#emploi">🗓  Emploi du Temps</a>
        <a href="#stats">📊 Statistiques</a>
        <a href="#gestion">📘 Gestion des Notes</a>
        <form action="logout.php" method="POST" style="display:inline;">
            <button type="submit" class="logout-btn">🚪 Déconnexion</button>
        </form>
        <button class="toggle-darkmode" onclick="toggleDarkMode()">🌓</button>
    </div>
</nav>

    <div class="container">
        <!-- Accueil -->
        <section id="accueil" class="content-section <?= ($active_section == 'accueil') ? 'active-section' : '' ?>">
            <h2 class="section-header">Bienvenue à l’EST Salé</h2>
            <div class="alert">
                Bonjour <?php echo $_SESSION['utilisateur']['nom'] . ' ' . $_SESSION['utilisateur']['prenom']; ?>, vous êtes connecté en tant qu'enseignant.
            </div>
            <p>L’espace enseignant vous permet de gérer vos étudiants, emploi du temps et évaluations.</p>
        </section>

        <!-- Étudiants -->
        <section id="etudiants" class="content-section">
            <h2 class="section-header">Mes Étudiants et leurs Évaluations</h2>
            <?php
            $stmt = $pdo->prepare("
                SELECT m.nom_matiere, f.nom_filiere, e.nom, e.prenom, ev.note, ev.type,
                CASE 
                    WHEN ev.note >= 12 THEN 'Validé'
                    WHEN ev.note >= 6 THEN 'Rattrapage'
                    ELSE 'Ajourné' 
                END AS validation
                FROM evaluations ev
                JOIN etudiants e ON ev.id_etudiant = e.id_etudiant
                JOIN matieres m ON ev.id_matiere = m.id_matiere
                JOIN filieres f ON e.id_filiere = f.id_filiere
                WHERE m.id_enseignant = ?
                ORDER BY m.nom_matiere, f.nom_filiere, e.nom
            ");
            $stmt->execute([$id_enseignant]);
            $evaluations = $stmt->fetchAll();

            $groupeActuel = '';
            foreach ($evaluations as $row) {
                $groupeCle = $row['nom_matiere'] . '|' . $row['nom_filiere'];

                if ($groupeCle !== $groupeActuel) {
                    // Fermer la table précédente si on change de matière ou de filière
                    if ($groupeActuel !== '') {
                        echo "</table><br>";
                    }

                    // Nouveau groupe : matière + filière
                    $groupeActuel = $groupeCle;
                    echo "<h3 style='margin-top: 30px; color: #004080;'>
                            Matière : {$row['nom_matiere']} – Filière : {$row['nom_filiere']}
                        </h3>";
                    echo "<table>
                            <tr>
                                <th>Nom</th><th>Prénom</th><th>Note</th><th>Type</th><th>Validation</th>
                            </tr>";
                }

                echo "<tr>
                    <td>{$row['nom']}</td>
                    <td>{$row['prenom']}</td>
                    <td>{$row['note']}</td>
                    <td>{$row['type']}</td>
                    <td>{$row['validation']}</td>
                </tr>";
            }

            // Fermer la dernière table
            if (!empty($evaluations)) {
                echo "</table>";
            }
            ?>
        </section>



        <!-- Emploi du Temps -->
        <section id="emploi" class="content-section">
            <h2 class="section-header">Emploi du Temps</h2>
            <table>
                <tr><th>Jour</th><th>Heure Début</th><th>Heure Fin</th><th>Matière</th><th>Filière</th><th>Niveau</th><th>Salle</th></tr>
                <?php
                $stmt = $pdo->prepare("
                    SELECT et.jour, et.heure_debut, et.heure_fin, m.nom_matiere, et.salle, et.niveau, f.nom_filiere
                    FROM emploi_temps et
                    JOIN matieres m ON et.id_matiere = m.id_matiere
                    JOIN filieres f ON m.id_filiere = f.id_filiere
                    WHERE et.id_enseignant = ?
                ");
                $stmt->execute([$id_enseignant]);
                foreach ($stmt->fetchAll() as $e) {
                    echo "<tr>
                        <td>{$e['jour']}</td>
                        <td>{$e['heure_debut']}</td>
                        <td>{$e['heure_fin']}</td>
                        <td>{$e['nom_matiere']}</td>
                        <td>{$e['nom_filiere']}</td>
                        <td>{$e['niveau']}</td>
                        <td>{$e['salle']}</td>
                    </tr>";
                }
                ?>
            </table>
        </section>


        <!-- Statistiques -->
        <section id="stats" class="content-section">
            <h2 class="section-header">Statistiques par Matière et Filière</h2>
            <?php
            // Récupérer les données
            $stmt = $pdo->prepare("
                SELECT m.nom_matiere, f.nom_filiere, AVG(ev.note) AS moyenne
                FROM evaluations ev
                JOIN matieres m ON ev.id_matiere = m.id_matiere
                JOIN etudiants e ON ev.id_etudiant = e.id_etudiant
                JOIN filieres f ON e.id_filiere = f.id_filiere
                WHERE m.id_enseignant = ?
                GROUP BY m.nom_matiere, f.nom_filiere
            ");
            $stmt->execute([$id_enseignant]);
            $results = $stmt->fetchAll();

            // Organiser les données par matière
            $matiereData = [];
            foreach ($results as $row) {
                $matiere = $row['nom_matiere'];
                $filiere = $row['nom_filiere'];
                $moyenne = round($row['moyenne'], 2);
                $matiereData[$matiere]['labels'][] = $filiere;
                $matiereData[$matiere]['data'][] = $moyenne;
            }

            $index = 0; // pour des IDs uniques
            foreach ($matiereData as $matiere => $chartData) {
                $chartId = "chart_" . $index++;
                echo "<h3 style='margin-top:30px;'>📘 Matière : $matiere</h3>";
                echo "<canvas id='$chartId' width='400' height='200'></canvas>";

                // Injecter les scripts Chart.js pour chaque matière
                echo "
                <script>
                    const ctx_$chartId = document.getElementById('$chartId').getContext('2d');
                    new Chart(ctx_$chartId, {
                        type: 'bar',
                        data: {
                            labels: " . json_encode($chartData['labels']) . ",
                            datasets: [{
                                label: 'Moyenne des notes par filière',
                                data: " . json_encode($chartData['data']) . ",
                                backgroundColor: 'rgba(0, 119, 204, 0.7)'
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 20
                                }
                            }
                        }
                    });
                </script>";
            }
            ?>
        </section>

        <!-- Gestion des Notes -->
        <section id="gestion" class="content-section <?= ($active_section == 'gestion') ? 'active-section' : '' ?>">
    <h2 class="section-header">Gestion des Notes</h2>

    <form method="GET" class="form-style">
        <label for="matiere">Matière :</label>
        <select name="matiere" id="matiere" onchange="this.form.submit()">
            <option value="">-- Choisir une matière --</option>
            <?php
            $stmt = $pdo->prepare("SELECT id_matiere, nom_matiere FROM matieres WHERE id_enseignant = ?");
            $stmt->execute([$id_enseignant]);
            foreach ($stmt->fetchAll() as $matiere) {
                $selected = ($selected_matiere == $matiere['id_matiere']) ? 'selected' : '';
                echo "<option value='{$matiere['id_matiere']}' $selected>{$matiere['nom_matiere']}</option>";
            }
            ?>
        </select>
    </form>

    <?php if ($selected_matiere): ?>
        <form method="GET" class="form-style">
            <input type="hidden" name="matiere" value="<?= $selected_matiere ?>">
            <label for="filiere">Filière :</label>
            <select name="filiere" id="filiere" onchange="this.form.submit()">
                <option value="">-- Choisir une filière --</option>
                <?php
                $stmt = $pdo->prepare("SELECT DISTINCT f.id_filiere, f.nom_filiere 
                                       FROM filieres f 
                                       JOIN matieres m ON m.id_filiere = f.id_filiere 
                                       WHERE m.id_matiere = ?");
                $stmt->execute([$selected_matiere]);
                foreach ($stmt->fetchAll() as $filiere) {
                    $selected = ($selected_filiere == $filiere['id_filiere']) ? 'selected' : '';
                    echo "<option value='{$filiere['id_filiere']}' $selected>{$filiere['nom_filiere']}</option>";
                }
                ?>
            </select>
        </form>
    <?php endif; ?>

    <?php if ($selected_matiere && $selected_filiere): ?>
        <form method="POST" class="form-style">
            <input type="hidden" name="id_matiere" value="<?= $selected_matiere ?>">
            <input type="hidden" name="id_filiere" value="<?= $selected_filiere ?>">

            <label>Étudiant :</label>
            <select name="id_etudiant" required>
                <?php
                $stmt = $pdo->prepare("SELECT id_etudiant, nom, prenom FROM etudiants WHERE id_filiere = ?");
                $stmt->execute([$selected_filiere]);
                echo "<option value=''>-- Choisir un(e) étudiant(e) --</option>";
                foreach ($stmt->fetchAll() as $etudiant) {
                    // Pré-remplir le champ si une note existe déjà
                    $selected = (isset($_POST['id_etudiant']) && $_POST['id_etudiant'] == $etudiant['id_etudiant']) ? 'selected' : '';

                    echo "<option value='{$etudiant['id_etudiant']}'>{$etudiant['nom']} {$etudiant['prenom']}</option>";
                }
                ?>
            </select>

            <label>Note :</label>
            <input type="number" name="note" step="0.1" min="0" max="20" required>

            <label>Type :</label>
            <select name="type">
                <option value="Contrôle">Contrôle</option>
                <option value="Examen">Examen</option>
            </select>
            <br>
            <input type="hidden" name="action" value="ajouter">
            <input type="submit" value="Valider" class="btn">
        </form>

        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr><th>Nom</th><th>Prénom</th><th>Note</th><th>Type</th><th>Validation</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php
                $stmt = $pdo->prepare("
                    SELECT e.id_etudiant, e.nom, e.prenom, ev.note, ev.type,
                    CASE 
                        WHEN ev.note >= 12 THEN 'Validé'
                        WHEN ev.note >= 6 THEN 'Rattrapage'
                        ELSE 'Ajourné'
                    END AS validation
                    FROM etudiants e
                    LEFT JOIN evaluations ev 
                    ON e.id_etudiant = ev.id_etudiant AND ev.id_matiere = ?
                    WHERE e.id_filiere = ?
                ");
                $stmt->execute([$selected_matiere, $selected_filiere]);
                foreach ($stmt->fetchAll() as $row):
                ?>
                    <tr>
                        <td><?= $row['nom'] ?></td>
                        <td><?= $row['prenom'] ?></td>
                        <td><?= $row['note'] ?></td>
                        <td><?= $row['type'] ?></td>
                        <td><?= $row['validation'] ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id_etudiant" value="<?= $row['id_etudiant'] ?>">
                                <input type="hidden" name="id_matiere" value="<?= $selected_matiere ?>">
                                <input type="hidden" name="action" value="modifier">
                                <button type="button" onclick="remplirFormulaire(<?= $row['id_etudiant'] ?>, <?= $selected_matiere ?>, <?= $row['note'] ?>, '<?= $row['type'] ?>')">✏️</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id_etudiant" value="<?= $row['id_etudiant'] ?>">
                                <input type="hidden" name="id_matiere" value="<?= $selected_matiere ?>">
                                <input type="hidden" name="action" value="supprimer">
                                <button type="submit">🗑️</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
</div>


    <script>
        function toggleDarkMode() {
            document.body.classList.toggle("dark");
        }

        document.querySelectorAll("nav .links a").forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault();
                document.querySelectorAll(".content-section").forEach(s => s.classList.remove("active-section"));
                document.querySelector(this.getAttribute("href")).classList.add("active-section");
            });
        });

        // Remplir le formulaire avec les données de l'étudiant sélectionné
function remplirFormulaire(idEtudiant, idMatiere, note, type) {
    document.querySelector('select[name="id_etudiant"]').value = idEtudiant;
    //document.querySelector('select[name="id_matiere"]').value = idMatiere;
    document.querySelector('input[name="note"]').value = note;
    document.querySelector('select[name="type"]').value = type;

    // Ajouter ou modifier l'action
    document.querySelector('input[name="action"]').value = 'modifier';
    
}


    </script>
</body>
</html>
