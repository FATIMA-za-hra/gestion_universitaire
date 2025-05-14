<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - EST Sale - Gestion Universitaire</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Reset et styles de base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    line-height: 1.6;
    color: #333;
    background-color: #f5f5f5;
}

.container {
    width: 85%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* Header */
.header {
    background-color: #2c3e50;
    color: white;
    padding: 1rem 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.header h1 {
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
}

.header nav ul {
    display: flex;
    list-style: none;
}

.header nav ul li a {
    color: white;
    text-decoration: none;
    padding: 0.5rem 1rem;
    display: block;
    transition: background-color 0.3s;
}

.header nav ul li a:hover, .header nav ul li a.active {
    background-color: #34495e;
    border-radius: 4px;
}

/* Sections principales */
main {
    padding: 2rem 0;
}

h2 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    border-bottom: 2px solid #3498db;
    padding-bottom: 0.5rem;
}

h3 {
    color: #2980b9;
    margin: 1rem 0;
    font-size: 1.5rem;
}

/* Page À propos */
.about-content {
    display: flex;
    gap: 2rem;
    margin-top: 1.5rem;
}

.about-image {
    flex: 1;
}

.about-image img {
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.about-text {
    flex: 2;
}

.about-text ul {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

/* Page Contact */
.contact-content {
    display: flex;
    gap: 2rem;
    margin-top: 1.5rem;
}

.contact-info {
    flex: 1;
    background-color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.contact-form {
    flex: 2;
    background-color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.form-group textarea {
    resize: vertical;
    min-height: 150px;
}

.btn {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 0.7rem 1.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #2980b9;
}

.map-section {
    margin-top: 2rem;
}

.map-container {
    margin-top: 1rem;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Footer */
.footer {
    background-color: #2c3e50;
    color: white;
    text-align: center;
    padding: 1rem 0;
    margin-top: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .about-content, .contact-content {
        flex-direction: column;
    }
    
    .header h1 {
        font-size: 1.5rem;
    }
}

/* Ajoutez ceci à votre fichier styles.css */

.logout-btn {
    background-color: #e74c3c;
    color: white !important;
    border-radius: 4px;
    margin-left: 1rem;
    transition: background-color 0.3s;
}

.logout-btn:hover {
    background-color: #c0392b;
}

/* Pour le bouton Retour si vous préférez */
.back-btn-container {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .back-btn {
            display: inline-block;
            background-color: #95a5a6;
            color: white !important;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .back-btn:hover {
            background-color: #7f8c8d;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <div class="back-btn-container">
        <a href="login.php" class="back-btn">Retour</a>
    </div>

    <main class="container">
        <section class="about-section">
            <h2>École Supérieure de Technologie de Salé</h2>
            
            <div class="about-content">
                <div class="about-image">
                    <img src="ests_background.jpg" alt="Bâtiment de l'EST Sale">
                </div>
                
                <div class="about-text">
                    <h3>Présentation</h3>
                    <p>L'École Supérieure de Technologie de Salé (EST Sale) est un établissement public d'enseignement supérieur relevant de l'Université Mohammed V de Rabat. Fondée en [année de fondation], l'EST a pour mission de former des techniciens supérieurs et des licenciés professionnels dans divers domaines technologiques.</p>
                    
                    <h3>Nos formations</h3>
                    <ul>
                        <li>Génie Informatique</li>
                        <li>Génie des Réseaux et Télécommunications</li>
                        <li>Génie Électrique</li>
                        <li>Génie Mécanique</li>
                        <li>Génie Industriel</li>
                    </ul>
                    
                    <h3>Valeurs</h3>
                    <p>L'EST Sale s'engage à fournir une éducation de qualité, combinant théorie et pratique, pour préparer les étudiants aux défis du marché du travail. Nous valorisons l'innovation, l'excellence et l'esprit d'équipe.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> EST Sale - Tous droits réservés</p>
        </div>
    </footer>
</body>
</html>