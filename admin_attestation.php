<?php
require 'db.php';

if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id = (int) $_GET['id'];

    if ($action === 'accepter') {
        $stmt = $pdo->prepare("UPDATE demande_attestations SET statut = 'Acceptée' WHERE id_demande = ?");
        $stmt->execute([$id]);
    } elseif ($action === 'refuser') {
        $stmt = $pdo->prepare("UPDATE demande_attestations SET statut = 'Refusée' WHERE id_demande = ?");
        $stmt->execute([$id]);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$stmt = $pdo->query("
    SELECT d.id_demande, e.nom, e.prenom, d.date_demande, d.statut
    FROM demande_attestations d
    JOIN etudiants e ON d.id_etudiant = e.id_etudiant
    WHERE d.statut = 'En attente'
    ORDER BY d.date_demande DESC
");

$demandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des demandes d'attestation</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        a.button {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }
        .accept { background-color: green; }
        .refuse { background-color: red; }
    </style>
</head>
<body>

<h2>Demandes d'attestation</h2>

<table>
    <tr>
        <th>Nom</th>
        <th>Date de demande</th>
        <th>Statut</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($demandes as $demande): ?>
        <tr>
            <td><?= htmlspecialchars($demande['nom'] . ' ' . $demande['prenom']) ?></td>
            <td><?= htmlspecialchars($demande['date_demande']) ?></td>
            <td><?= htmlspecialchars($demande['statut']) ?></td>
            <td>
                <?php if ($demande['statut'] === 'En attente'): ?>
                    <a class="button accept" href="?action=accepter&id=<?= $demande['id_demande'] ?>">✅ Accepter</a>
                    <a class="button refuse" href="?action=refuser&id=<?= $demande['id_demande'] ?>">❌ Refuser</a>
                <?php else: ?>
                    Aucune action
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
