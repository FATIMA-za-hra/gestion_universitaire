<?php
session_start();
include('db.php');

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user = $_SESSION['utilisateur'];

$utilisateur = $_SESSION['utilisateur'];
$id_etudiant = $utilisateur['id_etudiant']??null;
$message_attestation = "";


$id_filiere = null;
$niveau = null;
$filiere_nom = "";
$email = "";
$date_naissance = "";
$code_apogee = "";

if ($utilisateur['role'] === 'etudiant') {
    $stmt_info = $pdo->prepare("SELECT e.nom, e.prenom, e.id_filiere, e.niveau, f.nom_filiere, e.email, e.date_naissance, e.id_etudiant AS code_apogee 
                            FROM etudiants e 
                            JOIN filieres f ON e.id_filiere = f.id_filiere 
                            WHERE e.id_etudiant = ?");
    $stmt_info->execute([$id_etudiant]);
    $etudiant_info = $stmt_info->fetch();

    if ($etudiant_info) {
        $id_filiere = $etudiant_info['id_filiere'];
        $niveau = $etudiant_info['niveau'];
        $filiere_nom = $etudiant_info['nom_filiere'];
        $email = $etudiant_info['email'];
        $date_naissance = $etudiant_info['date_naissance'];
        $code_apogee = $etudiant_info['code_apogee'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['demande_attestation'])) {
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM etudiants WHERE id_etudiant = ?");
    $stmt_check->execute([$id_etudiant]);
    $etudiant_exists = $stmt_check->fetchColumn();

    if ($etudiant_exists > 0) {
        $stmt = $pdo->prepare("INSERT INTO demande_attestations (id_etudiant, date_demande, statut) VALUES (?, NOW(), 'En attente')");
        if ($stmt->execute([$id_etudiant])) {
            $message_attestation = "Votre demande d'attestation a été envoyée avec succès.";
        } else {
            $message_attestation = "Erreur lors de l'envoi de la demande : " . implode(", ", $stmt->errorInfo());
        }
    } else {
        $message_attestation = "L'étudiant avec cet ID n'existe pas.";
    }
}

$stmt = $pdo->prepare("SELECT m.nom_matiere, e.note, e.type 
                       FROM evaluations e 
                       JOIN matieres m ON e.id_matiere = m.id_matiere 
                       WHERE e.id_etudiant = ?");
$stmt->execute([$id_etudiant]);
$notes = $stmt->fetchAll();
$total_pondere = 0;
$total_coefficients = 0;

foreach ($notes as $note) {
    $valeur = floatval($note['note']);
    $type = strtolower($note['type']);

    if ($type === 'examen') {
        $coef = 0.6;
    } elseif ($type === 'contrôle') {
        $coef = 0.4;
    } else {
        continue; // type inconnu
    }

    $total_pondere += $valeur * $coef;
    $total_coefficients += $coef;
}

$moyenne_generale = $total_coefficients > 0 ? round($total_pondere / $total_coefficients, 2) : null;


$stmt_emploi = $pdo->prepare("
    SELECT e.jour, e.heure_debut, e.heure_fin, 
           m.nom_matiere, ens.nom AS nom_enseignant, e.salle
    FROM emploi_temps e
    JOIN matieres m ON e.id_matiere = m.id_matiere
    JOIN enseignants ens ON e.id_enseignant = ens.id_enseignant
    WHERE e.id_filiere = ? AND e.niveau = ?
");
$stmt_emploi->execute([$id_filiere, $niveau]);
$emploi_temps = $stmt_emploi->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Étudiant</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        * {
            margin: 0; padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        body {
            background-image: url('ests_biblio.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #222;
            backdrop-filter: blur(2px);
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
        }
        nav img {
            height: 40px;
        }
        nav .links a {
            margin-left: 25px;
            text-decoration: none;
            font-weight: bold;
            color: #0077cc;
            transition: color 0.3s;
        }
        nav .links a:hover {
            color: #005ea0;
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
        
        h3 {
            font-size: 16px;
            color: #3399ff;
            margin-bottom: 14px;
            border-bottom: 2px solid #3399ff;
            padding-bottom: 3px;
            display: inline-block;
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
        input[type="text"], input[type="email"], input[type="date"] {
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
        body.dark-mode {
            background-color: #121212;
            color: #f1f1f1;
        }

        body.dark-mode nav {
            background: rgba(30, 30, 30, 0.95);
        }

        body.dark-mode nav .links a {
            color: #80cfff;
        }

        body.dark-mode nav .links a:hover {
            color: #48aaff;
        }

        body.dark-mode .container {
            background: rgba(40, 40, 40, 0.94);
        }

        body.dark-mode table th {
            background-color: #1e1e1e;
            color: #eee;
        }

        body.dark-mode table td {
            border-color: #444;
        }

        body.dark-mode input,
        body.dark-mode select {
            background-color: #222;
            color: #eee;
            border: 1px solid #555;
        }

        body.dark-mode .btn {
            background-color: #3399ff;
        }

        body.dark-mode .btn:hover {
            background-color: #007acc;
        }

        body.dark-mode .alert {
            background: #2b3e50;
            color: #b3d4fc;
            border-left: 5px solid #3399ff;
        }

        ul {
        list-style: none; 
        margin-left: 20px;
        padding-left: 0;
        }

        ul li {
        position: relative;
        padding-left: 25px; 
        margin-bottom: 10px;
        }

        ul li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 7px;
        width: 7px;
        height: 7px;
        background-color: #3399ff; 
        border-radius: 1px; 
        }

        footer {
        text-align: center;
        padding: 10px 0;
        background-color: #0077cc;
        color: white;
        margin-top: 30px;
        }

    </style>
    
    <script>
        function showSection(id) {
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active-section'));
            document.getElementById(id).classList.add('active-section');
        }
        window.onload = function () {
            showSection('acceuil');
            
        };
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('dark-mode', 'on');
            } else {
                localStorage.setItem('dark-mode', 'off');
            }
        }



        async function genererAttestation(event) {
    event.preventDefault();
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Données dynamiques
    const nomComplet = "<?= htmlspecialchars($etudiant_info['nom'] . ' ' . $etudiant_info['prenom']) ?>";
    const code = "<?= $etudiant_info['code_apogee'] ?>";
    const naissance = "<?= $etudiant_info['date_naissance'] ?>";
    const filiere = "<?= $filiere_nom ?>";
    const annee = "<?= date('Y') ?>/<?= date('Y')+1 ?>";

    const logo = new Image();
    logo.src = "logo_est.png";

    try {
        await new Promise((resolve, reject) => {
            logo.onload = resolve;
            logo.onerror = reject;
        });

        // Dimensions utiles
        const pageWidth = doc.internal.pageSize.getWidth();
        const rightMargin = pageWidth - 20;
        
        // 1. Logo à gauche
        doc.addImage(logo, 'PNG', 15, 15, 30, 30);

        // 2. Numéro d'attestation en haut à droite
        doc.setFontSize(10);
        doc.text(`N° EST/${new Date().getFullYear()}/${Math.floor(Math.random()*1000)}`, rightMargin, 15, {align: 'right'});
        doc.setLineWidth(0.3);
        doc.line(rightMargin - 30, 18, rightMargin, 18);

        // 3. Texte institutionnel à droite
        doc.setFont("helvetica", "bold");
        doc.setFontSize(10);
        doc.text("ROYAUME DU MAROC", rightMargin, 30, {align: "right"});
        doc.text("Université Mohammed V de Rabat", rightMargin, 37, {align: "right"});
        doc.text("École Supérieure de Technologie - Salé", rightMargin, 44, {align: "right"});
        doc.setFont("helvetica", "normal");
        doc.text("Service des Affaires Estudiantines", rightMargin, 51, {align: "right"});

        // 4. Ligne de séparation
        doc.setLineWidth(0.5);
        doc.line(20, 60, pageWidth - 20, 60);

        // 5. Titre principal
        doc.setFontSize(16);
        doc.text("ATTESTATION DE SCOLARITÉ", pageWidth/2, 70, {align: "center"});
        doc.setLineWidth(0.3);
        doc.line(pageWidth/2 -40 , 73, pageWidth/2 + 40, 73);

        // 6. Corps du document
        doc.setFont("helvetica", "normal");
        doc.setFontSize(12);
        let yPosition = 90;
        
        doc.text(`Le Directeur de l'École Supérieure de Technologie de Salé atteste que l'étudiant(e) :`, 20, yPosition);
        yPosition += 10;
        
        doc.setFont("helvetica", "bold");
        doc.text(`${nomComplet}`, 20, yPosition);
        yPosition += 10;
        
        doc.setFont("helvetica", "normal");
        doc.text(`Code Apogée : ${code}`, 20, yPosition);
        yPosition += 8;
        
        doc.text(`Né(e) le : ${naissance}`, 20, yPosition);
        yPosition += 8;
        
        doc.text(`Est inscrit(e) au Diplôme Universitaire de Technologie à l'EST Salé`, 20, yPosition);
        yPosition += 8;
        
        doc.text(`pour l'année universitaire ${annee}`, 20, yPosition);
        yPosition += 8;
        
        doc.text(`Filière : ${filiere}`, 20, yPosition);
        yPosition += 15;

        // 7. Mentions légales
        doc.text("Fait pour servir et valoir ce que de droit.", 20, yPosition);
        yPosition += 20;

        // 8. Signature
        doc.text(`Fait à Salé, le ${new Date().toLocaleDateString('fr-FR')}`, 20, yPosition);
        doc.text("Le Directeur", rightMargin, yPosition, {align: "right"});

        // 9. Cadre d'adresse
        doc.setFontSize(10);
        doc.rect(20, 260, pageWidth - 40, 15);
        doc.text("Adresse : Avenue la Princesse Héritier, B.P. 227 Salé Médina 11060", 25, 266);
        doc.text("Tél : 0537 881 685 - Fax : 0537 881 547", 25, 272);

        // Sauvegarde
        doc.save(`attestation_scolarite_${nomComplet.replace(/ /g, '_')}.pdf`);

    } catch (error) {
        console.error("Erreur lors de la génération:", error);
        alert("Erreur lors de la génération du PDF");
    }
}
        
        function generatePDF() {
            const content = document.getElementById("pdf-content");

            // On le rend visible temporairement
            content.style.display = "block";

            // Génération PDF
            html2pdf().from(content).set({
                margin:       0.5,
                filename:     'bulletin_notes.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
            }).save().then(() => {
                // On le cache à nouveau
                content.style.display = "none";
            });

            html2pdf().set({
            onbeforePage: function(page) {
                page.canvas.setTextColor(200, 200, 200);
                page.canvas.setFontSize(60);
                page.canvas.text('EST Salé', 105, 150, null, 45);
            }
            });
        }
    </script>
</head>
<body>
    <nav>
        <img src="logo_est.png" alt="EST Salé">
        <div class="links">
            <a href="#" onclick="showSection('acceuil')">🏠 Acceuil</a>
            <a href="#" onclick="showSection('notes')">📘 Notes</a>
            <a href="#" onclick="showSection('emploi')">🗓 Emploi</a>
            <a href="#" onclick="showSection('attestation')">📄 Attestation</a>
            <a href="logout.php" style="color: red;">🚪 Déconnexion</a>
            <a href="#" onclick="toggleDarkMode()" title="Mode sombre" style="margin-right: 15px;">🌓</a>
        </div>
    </nav>

    <div class="container">

        <div class="content-section" id="acceuil">
            <h2 class="section-header">École Supérieure de Technologie de Salé</h2>
  
            <section>
            <h3 class=".section-h">Présentation</h3>
            <p>L'École Supérieure de Technologie de Salé (EST Salé) est un établissement public affilié à l’Université Mohammed V de Rabat. Elle forme des techniciens et des cadres intermédiaires spécialisés dans différents domaines technologiques et de gestion.</p>
            </section>
            <br>

            <section>
            <h3 class=".section-h">Types de diplômes</h3>
            <ul>
                <li>Diplôme Universitaire de Technologie (DUT)</li>
                <li>Licence Professionnelle</li>
            </ul>
            </section>

            <section>
                <h3 class=".section-h">Filières proposées</h3>
                <ul>
                <li>Génie Informatique</li>
                <li>Réseaux et Systèmes Informatiques</li>
                <li>Gestion des Entreprises</li>
                <li>Techniques de Commercialisation et de Vente</li>
                <li>Génie Électrique et Électronique Industrielle</li>
                <li>Logistique et Transport</li>
                </ul>
            </section>

            <section>
                <h3 class=".section-h">Clubs étudiants</h3>
                <ul>
                <li>Club Informatique</li>
                <li>Club Robotique</li>
                <li>Club Enactus</li>
                <li>Club Culturel et Artistique</li>
                <li>Club Sportif</li>
                <li>Club Environnement et Développement Durable</li>
                </ul>
            </section>

            <footer>
                <p>&copy; 2025 EST Salé. Tous droits réservés.</p>
            </footer>
        </div>

        <div class="content-section" id="notes">
            <h2 class="section-header">Mes Notes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Matière</th>
                        <th>Type</th>
                        <th>Note</th>
                        <th>Validation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $note): 
                        $validation = '';
                        $note_val = floatval($note['note']);
                        if ($note_val >= 12) {
                            $validation = '✅ Validé';
                        } elseif ($note_val >= 6) {
                            $validation = '🔄 Rattrapage';
                        } else {
                            $validation = '❌ Ajourné';
                        }
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($note['nom_matiere']) ?></td>
                            <td><?= htmlspecialchars($note['type']) ?></td>
                            <td><?= htmlspecialchars($note['note']) ?></td>
                            <td><?= $validation ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($moyenne_generale !== null): ?>
            <div class="alert" style="margin-top: 20px; background-color: #e6f7ff; color: #005b99;">
                🎓 Moyenne Générale Pondérée : <strong><?= $moyenne_generale ?></strong>
            </div>
            <?php else: ?>
            <div class="alert" style="margin-top: 20px;">
                ❌ Aucune note disponible pour le calcul.
            </div>
            <?php endif; ?>
        
            <!-- Bouton -->
        <button class="btn" onclick="generatePDF()">Exporter en PDF</button>

        <!-- Zone cachée pour le PDF -->

        <div id="pdf-content" style="display: none; padding: 20px; background: white; color: black; font-family: Arial, sans-serif;">
            <table style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td style="width: 20%;">
                        <img src="logo_est.png" alt="Logo EST Salé" style="width: 80px;">
                    </td>
                    <td style="text-align: center; width: 60%;">
                        <h2 style="margin: 0; color: #0077cc;">École Supérieure de Technologie de Salé</h2>
                        <p style="margin: 5px 0; font-size: 12px;">Université Mohammed V de Rabat</p>
                        <p style="margin: 5px 0; font-size: 12px;">Année Universitaire 2024/2025</p>
                    </td>
                    <td style="width: 20%; text-align: right;">
                        <p style="margin: 0; font-size: 10px;">Date: <?= date('d/m/Y') ?></p>
                    </td>
                </tr>
            </table>
            <hr style="border: 1px solid #0077cc; margin-bottom: 20px;">
            <br>
            
            <?php if (isset($etudiant_info) && $etudiant_info): ?>
            <p>Nom de l'étudiant : <?= htmlspecialchars($etudiant_info['nom']) ?></p>
            <p>Email : <?= htmlspecialchars($etudiant_info['email']) ?></p>
            <p>Filière : <?= htmlspecialchars($etudiant_info['nom_filiere']) ?></p>
            <?php else: ?>
            <p style="color:red;">Aucune information sur l'étudiant disponible.</p>
            <?php endif; ?>


            <h3>Notes :</h3>
            <table border="0" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background-color: #0077cc; color: white;">
                        <th style="padding: 8px; text-align: left; border-bottom: 2px solid #005fa3;">Matière</th>
                        <th style="padding: 8px; text-align: center; border-bottom: 2px solid #005fa3;">Type</th>
                        <th style="padding: 8px; text-align: center; border-bottom: 2px solid #005fa3;">Note</th>
                        <th style="padding: 8px; text-align: center; border-bottom: 2px solid #005fa3;">Validation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $note): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 8px;"><?= htmlspecialchars($note['nom_matiere']) ?></td>
                        <td style="padding: 8px; text-align: center;"><?= htmlspecialchars($note['type']) ?></td>
                        <td style="padding: 8px; text-align: center;"><?= htmlspecialchars($note['note']) ?></td>
                        <td style="padding: 8px; text-align: center; font-weight: bold; 
                            <?= floatval($note['note']) >= 12 ? 'color: #28a745;' : 
                            (floatval($note['note']) >= 6 ? 'color: #ffc107;' : 'color: #dc3545;') ?>">
                            <?= floatval($note['note']) >= 12 ? 'Validé' : 
                            (floatval($note['note']) >= 6 ? 'Rattrapage' : 'Ajourné') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Moyenne Générale : <?= number_format($moyenne_generale, 2) ?></h3>

            <div style="margin-top: 30px; border-top: 1px dashed #ccc; padding-top: 10px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 70%;">
                        <p style="font-size: 10px; color: #666;">
                            EST Salé - Avenue Princesse Lalla Hasnaa, Salé Médina<br>
                            Tél: 0537 881 685 | Email: contact@estsale.um5.ac.ma
                        </p>
                    </td>
                    <td style="width: 30%; text-align: center;">
                        <p style="margin-bottom: 40px;">Le Directeur Pédagogique</p>
                        <p style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px;">Signature</p>
                    </td>
                </tr>
            </table>
        </div>
        </div>
        </div>


        <div class="content-section" id="emploi">
            <h2 class="section-header">Emploi du Temps</h2>
            <?php if ($emploi_temps): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Heure Début</th>
                            <th>Heure Fin</th>
                            <th>Matière</th>
                            <th>Salle</th>
                            <th>Enseignant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emploi_temps as $emploi): ?>
                            <tr>
                                <td><?= htmlspecialchars($emploi['jour']) ?></td>
                                <td><?= htmlspecialchars($emploi['heure_debut']) ?></td>
                                <td><?= htmlspecialchars($emploi['heure_fin']) ?></td>
                                <td><?= htmlspecialchars($emploi['nom_matiere']) ?></td>
                                <td><?= htmlspecialchars($emploi['salle']) ?></td>
                                <td><?= htmlspecialchars($emploi['nom_enseignant']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="alert">Aucun emploi du temps disponible.</p>
            <?php endif; ?>
        </div>

        <div class="content-section" id="attestation">
            <h2 class="section-header">Demande d'Attestation</h2>
            <?php if ($message_attestation): ?>
                <div class="alert"><?= htmlspecialchars($message_attestation) ?></div>
            <?php endif; ?>

            <?php
            $stmt = $pdo->prepare("SELECT statut FROM demande_attestations WHERE id_etudiant = ? ORDER BY date_demande DESC LIMIT 1");
            $stmt->execute([$id_etudiant]);
            $demande = $stmt->fetch();
            ?>

            <?php if ($demande && $demande['statut'] === 'Acceptée'): ?>
                <button onclick="genererAttestation(event)" class="btn" style="width: 100%;">Générer l'Attestation PDF</button>
            <?php elseif ($demande): ?>
                <p style="margin-top: 20px;">Statut de votre demande : <strong><?= htmlspecialchars($demande['statut']) ?></strong></p>
            <?php endif; ?>



            <form method="post" style="margin-top: 30px;">
                <div class="form-group">
                    <label>Nom Complet</label>
                    <input type="text" name="nom_complet" value="<?= htmlspecialchars($utilisateur['nom'] . ' ' . $utilisateur['prenom']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Code Apogée</label>
                    <input type="text" name="code_apogee" value="<?= htmlspecialchars($code_apogee ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Date de Naissance</label>
                    <input type="date" name="date_naissance" value="<?= htmlspecialchars($date_naissance ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Filière</label>
                    <input type="text" name="filiere" value="<?= htmlspecialchars($filiere_nom) ?>" required>
                </div>
                <div class="form-group">
                    <label>Année Universitaire</label>
                    <input type="text" name="annee_universitaire" placeholder="ex: 2024-2025" required>
                </div>
                <!--<button type="submit" class="btn" style="width: 100%;">Générer l'Attestation PDF</button>-->
            </form>
            <form method="post">
                <input type="hidden" name="demande_attestation" value="1">
                <button type="submit" class="btn" style="width: 100%;">Demander une Attestation</button>
            </form>
        </div>
    </div>
</body>
</html>
