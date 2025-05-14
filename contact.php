<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - EST Sale - Gestion Universitaire</title>
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
            position: relative;
        }

        .container {
            width: 85%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Bouton Retour */
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

        /* Header */
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

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

        

    </style>
</head>
<body>
    <div class="back-btn-container">
        <a href="login.php" class="back-btn">Retour</a>
    </div>

    

    <main class="container">
    <section class="contact-section">
            <h2>Contactez l'EST Sale</h2>
            
            <div class="contact-content">
                <div class="contact-info">
                    <h3>Coordonnées</h3>
                    <address>
                        <p><strong>Adresse :</strong> Avenue Prince Héritier Sidi Mohammed, Salé, Maroc</p>
                        <p><strong>Téléphone :</strong> +212 5 37 84 90 00</p>
                        <p><strong>Email :</strong> contact@estsale.um5.ac.ma</p>
                        <p><strong>Site web :</strong> <a href="https://www.est.um5.ac.ma/a" target="_blank">www.estsale.um5.ac.ma</a></p>
                    </address>
                    
                    <h3>Heures d'ouverture</h3>
                    <p>Du lundi au vendredi : 8h30 - 18h00</p>
                </div>
                
                <div class="contact-form">
                    <h3>Formulaire de contact</h3>
                    <form action="process_contact.php" method="POST">
                        <div class="form-group">
                            <label for="name">Nom complet :</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email :</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Sujet :</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message :</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Envoyer</button>
                    </form>
                </div>
            </div>
        </section>
        
        <section class="map-section">
            <h3>Localisation</h3>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3309.041704828318!2d-6.810150684785492!3d33.99098098062171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda76b8711d7f0f5%3A0x7f3b1e3e3e3e3e3e!2sEST%20Sale!5e0!3m2!1sfr!2sma!4v1620000000000!5m2!1sfr!2sma" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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