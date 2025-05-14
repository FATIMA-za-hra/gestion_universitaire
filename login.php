<?php
session_start();
include('db.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch();

    if ($utilisateur && $mot_de_passe === $utilisateur['mot_de_passe']) {
        $_SESSION['utilisateur'] = $utilisateur;

        if ($utilisateur['role'] == 'admin') {
            header('Location: index.php');
        } elseif ($utilisateur['role'] == 'enseignant') {
            header('Location: enseignant_dashboard.php');
        } elseif ($utilisateur['role'] == 'etudiant') {
            header('Location: etudiant_role.php');
        }
        exit;
    } else {
        $message = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('ests_background2.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }

        .login-box {
            background-color: rgba(255, 255, 255, 0.15);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            width: 100%;
            backdrop-filter: blur(10px);
            color: white;
            max-width: 400px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #007BFF;
            font-weight: 700;
        }

        .form-control {
            height: 45px;
            font-size: 16px;
            border-radius: 8px;
            color: white;
        }

        .form-control::placeholder {
            color: rgba(19, 18, 18, 0.7);
        }

        .btn-primary {
            height: 45px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            background-color: #007BFF;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            text-align: center;
        }

        .links {
        position: absolute; /* Position relative to the nearest positioned ancestor */
        top: 20px; /* Distance from the top */
        right: 20px; /* Distance from the right */
        display: flex; /* Align links in a row */
        justify-content: space-between; /* Space between links */
        width: 200px; /* Adjust width as needed */
    }

    .link-item {
        flex: 1; /* Equal width for each link */
        text-align: center; /* Center the text */
    }

    .links a {
        text-decoration: none;
        color: #007BFF; /* Blue color for the links */
        transition: color 0.3s;
    }

    .links a:hover {
        color: #0056b3; /* Darker blue on hover */
    }

    
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Connexion</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="mot_de_passe" class="form-control mb-4" placeholder="Mot de passe" required>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>

    <!-- Links with Icons -->
    <div class="links">
        <div class="link-item">
            <a href="about.php" class="text-blue"><i class="fas fa-info-circle"></i> À propos</a>
        </div>
        <div class="link-item">
            <a href="contact.php" class="text-blue"><i class="fas fa-envelope"></i> Contact</a>
        </div>
    </div>
    <hr class="separator">
</body>
</html>
