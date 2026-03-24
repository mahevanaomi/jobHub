<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - JobHub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-briefcase"></i>
                    <a href="index.html"><span>Job<strong>Hub</strong></span></a>
                </div>
                <ul class="nav-menu">
                    <li><a href="index.html#home" class="nav-link">Accueil</a></li>
                    <li><a href="index.html#jobs" class="nav-link">Emplois</a></li>
                    <li><a href="index.html#categories" class="nav-link">Catégories</a></li>
                    <li><a href="about.html" class="nav-link">À Propos</a></li>
                    <li><a href="contact.html" class="nav-link active">Contact</a></li>
                </ul>
                <div class="nav-buttons">
                    <a href="login.html" class="btn btn-outline">Connexion</a>
                    <a href="inscription.html" class="btn btn-primary">Inscription</a>
                </div>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- PAGE HEADER -->
    <section class="page-header">
        <div class="container">
            <h1>Contactez-nous</h1>
            <p>Nous sommes là pour répondre à toutes vos questions</p>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="inscription-section">
        <div class="container">
            <div style="max-width: 1000px; margin: 0 auto;">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-bottom: 50px;">
                    <!-- CONTACT INFO -->
                    <div>
                        <h2 style="font-size: 28px; margin-bottom: 30px; color: #1f2937;">Nos Coordonnées</h2>

                        <div style="margin-bottom: 25px; display: flex; align-items: start; gap: 20px;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-map-marker-alt" style="color: white; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 8px;">Adresse</h3>
                                <p style="color: #6b7280;">123 Avenue des Champs-Élysées<br>75008 Paris, France</p>
                            </div>
                        </div>

                        <div style="margin-bottom: 25px; display: flex; align-items: start; gap: 20px;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-phone" style="color: white; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 8px;">Téléphone</h3>
                                <p style="color: #6b7280;">+33 1 23 45 67 89<br>Lun - Ven : 9h - 18h</p>
                            </div>
                        </div>

                        <div style="margin-bottom: 25px; display: flex; align-items: start; gap: 20px;">
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fas fa-envelope" style="color: white; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 8px;">Email</h3>
                                <p style="color: #6b7280;">contact@jobhub.fr<br>support@jobhub.fr</p>
                            </div>
                        </div>

                        <div style="background: #f9fafb; padding: 25px; border-radius: 12px; margin-top: 40px;">
                            <h3 style="font-size: 18px; margin-bottom: 15px;">Suivez-nous</h3>
                            <div style="display: flex; gap: 15px;">
                                <a href="#" style="width: 45px; height: 45px; background: #3b5998; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                <a href="#" style="width: 45px; height: 45px; background: #1da1f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" style="width: 45px; height: 45px; background: #0077b5; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="#" style="width: 45px; height: 45px; background: #E4405F; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- CONTACT FORM -->
                    <div>
                        <form class="inscription-form" id="contactForm" style="margin: 0;">
                            <h2 style="font-size: 28px; margin-bottom: 25px; color: #1f2937;">Envoyez-nous un message</h2>

                            <div class="form-group">
                                <label for="nom">Nom complet *</label>
                                <input type="text" id="nom" name="nom" required placeholder="maheva naomi">
                            </div>

                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required placeholder="jean.dupont@email.com">
                            </div>

                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone" placeholder="+33 6 12 34 56 78">
                            </div>

                            <div class="form-group">
                                <label for="sujet">Sujet *</label>
                                <select id="sujet" name="sujet" required>
                                    <option value="">Sélectionnez un sujet...</option>
                                    <option value="candidat">Question - Candidat</option>
                                    <option value="entreprise">Question - Entreprise</option>
                                    <option value="technique">Problème technique</option>
                                    <option value="partenariat">Partenariat</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" required placeholder="Votre message..." rows="6"></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Envoyer le message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- FAQ -->
                <div style="margin-top: 80px;">
                    <h2 style="font-size: 32px; margin-bottom: 30px; color: #1f2937; text-align: center;">Questions Fréquentes</h2>

                    <div style="max-width: 800px; margin: 0 auto;">
                        <div style="background: white; border: 2px solid #e5e7eb; border-radius: 12px; padding: 25px; margin-bottom: 20px;">
                            <h3 style="font-size: 18px; margin-bottom: 10px; color: #1f2937;">
                                <i class="fas fa-question-circle" style="color: #3b82f6;"></i> Comment créer un compte ?
                            </h3>
                            <p style="color: #6b7280; line-height: 1.8;">
                                Cliquez sur "Inscription" en haut de la page, choisissez votre type de compte (candidat ou entreprise),
                                et remplissez le formulaire. C'est gratuit et ne prend que quelques minutes !
                            </p>
                        </div>

                        <div style="background: white; border: 2px solid #e5e7eb; border-radius: 12px; padding: 25px; margin-bottom: 20px;">
                            <h3 style="font-size: 18px; margin-bottom: 10px; color: #1f2937;">
                                <i class="fas fa-question-circle" style="color: #3b82f6;"></i> Comment postuler à une offre ?
                            </h3>
                            <p style="color: #6b7280; line-height: 1.8;">
                                Connectez-vous à votre compte candidat, recherchez l'offre qui vous intéresse,
                                et cliquez sur "Postuler". Assurez-vous que votre profil et votre CV sont à jour.
                            </p>
                        </div>

                        <div style="background: white; border: 2px solid #e5e7eb; border-radius: 12px; padding: 25px; margin-bottom: 20px;">
                            <h3 style="font-size: 18px; margin-bottom: 10px; color: #1f2937;">
                                <i class="fas fa-question-circle" style="color: #3b82f6;"></i> Comment publier une offre d'emploi ?
                            </h3>
                            <p style="color: #6b7280; line-height: 1.8;">
                                Créez un compte entreprise, accédez à votre dashboard et cliquez sur "Publier une offre".
                                Remplissez les détails de l'offre et publiez. Vous recevrez les candidatures directement dans votre espace.
                            </p>
                        </div>

                        <div style="background: white; border: 2px solid #e5e7eb; border-radius: 12px; padding: 25px;">
                            <h3 style="font-size: 18px; margin-bottom: 10px; color: #1f2937;">
                                <i class="fas fa-question-circle" style="color: #3b82f6;"></i> JobHub est-il gratuit ?
                            </h3>
                            <p style="color: #6b7280; line-height: 1.8;">
                                Oui ! L'inscription et la recherche d'emploi sont totalement gratuites pour les candidats.
                                Pour les entreprises, nous proposons différents forfaits selon vos besoins de recrutement.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <div class="logo">
                        <i class="fas fa-briefcase"></i>
                        <span>Job<strong>Hub</strong></span>
                    </div>
                    <p>Votre plateforme de référence pour trouver l'emploi idéal ou recruter les meilleurs talents.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h3>Pour les Candidats</h3>
                    <ul>
                        <li><a href="index.html#jobs">Rechercher un Emploi</a></li>
                        <li><a href="inscription.html">Créer un Profil</a></li>
                        <li><a href="login.html">Mon Compte</a></li>
                        <li><a href="dashboard-candidat.html">Mon Dashboard</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Pour les Entreprises</h3>
                    <ul>
                        <li><a href="poster-offre.html">Publier une Offre</a></li>
                        <li><a href="inscription.html">Créer un Compte</a></li>
                        <li><a href="login.html">Espace Entreprise</a></li>
                        <li><a href="contact.html">Nous Contacter</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>À Propos</h3>
                    <ul>
                        <li><a href="about.html">Qui sommes-nous ?</a></li>
                        <li><a href="contact.html">Nous Contacter</a></li>
                        <li><a href="#">Conditions d'utilisation</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 JobHub. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="js/alerts.js"></script>
    <script src="js/update-alerts.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
