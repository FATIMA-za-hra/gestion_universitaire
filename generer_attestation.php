<?php
// Variables dynamiques
$nom_prenom = "LOUAKKOU MARIAM";
$date_naissance = "10 Juin 2003";
$lieu_naissance = "OUARZAZATE (MAROC)";
$cne = "P392656";
$code_apogee = "D4455913";
$cin = "JA123456"; // exemple
$filiere = "Génie Informatique (GI)";
$annee_universitaire = "2024-2025";
$date_attestation = date("d F Y");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation de Scolarité</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px;
            line-height: 1.6;
        }
        .entete {
            text-align: center;
        }
        .titre {
            font-weight: bold;
            font-size: 20px;
            margin: 30px 0;
            text-align: center;
            text-decoration: underline;
        }
        .contenu {
            text-align: justify;
            margin: 20px 0;
        }
        .signature {
            text-align: right;
            margin-top: 60px;
        }
    </style>
</head>
<body>

<div class="entete">
    <p><strong>ROYAUME DU MAROC<br>
    Université Mohammed V de Rabat<br>
    École Supérieure de Technologie - Salé<br>
    Service des Affaires Estudiantines</strong></p>
</div>

<div class="titre">ATTESTATION DE SCOLARITÉ</div>

<div class="contenu">
    <p>Le Directeur de l'École Supérieure de Technologie de Salé atteste que l'étudiante :</p>
    <p><strong><?php echo $nom_prenom; ?></strong></p>
    <p>Numéro de la carte d’identité nationale : <strong><?php echo $cin; ?></strong><br>
    Code national de l’étudiante (Apogée) : <strong><?php echo $code_apogee; ?></strong><br>
    CNE : <strong><?php echo $cne; ?></strong></p>
    
    <p>née le <?php echo $date_naissance; ?> à <?php echo $lieu_naissance; ?>, est inscrite en <strong><?php echo $filiere; ?></strong> à l’École Supérieure de Technologie de Salé, pour l’année universitaire <strong><?php echo $annee_universitaire; ?></strong>.</p>

    <p>La présente attestation est délivrée pour servir et valoir ce que de droit.</p>
</div>

<div class="signature">
    <p>Fait à Salé, le <?php echo $date_attestation; ?><br>
    Le Directeur</p>
</div>

</body>
</html>