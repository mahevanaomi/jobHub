<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - JobHub</title>
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
                    <li><a href="contact.html" class="nav-link">Contact</a></li>
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
            <h1>Connexion</h1>
            <p>Accédez à votre espace personnel</p>
        </div>
    </section>

    <!-- LOGIN SECTION -->
    <section class="inscription-section">
        <div class="container">
            <div class="inscription-container" style="max-width: 500px;">
                <form class="inscription-form" id="loginForm">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fas fa-sign-in-alt" style="color: white; font-size: 32px;"></i>
                        </div>
                        <h2 style="font-size: 28px; margin-bottom: 10px;">Bon retour !</h2>
                        <p style="color: #6b7280;">Connectez-vous pour accéder à votre compte</p>
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" required placeholder="votre@email.com">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;">
                            <input type="checkbox" style="width: auto;">
                            <span>Se souvenir de moi</span>
                        </label>
                        <a href="#" style="color: #3b82f6; font-size: 14px; font-weight: 600;">Mot de passe oublié ?</a>
                    </div>

                    <div class="form-group">
                        <label for="accountType" style="margin-bottom: 10px;">Type de compte</label>
                        <select id="accountType" name="accountType" required>
                            <option value="candidat">Je suis un candidat</option>
                            <option value="entreprise">Je suis une entreprise</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </button>
                    </div>

                    <div style="text-align: center; margin-top: 25px; padding-top: 25px; border-top: 1px solid #e5e7eb;">
                        <p style="color: #6b7280; margin-bottom: 15px;">Ou connectez-vous avec</p>
                        <div style="display: flex; gap: 15px; justify-content: center;">
                            <button type="button" class="btn btn-outline" style="flex: 1; max-width: 150px;">
                                <i class="fab fa-google"></i> Google
                            </button>
                            <button type="button" class="btn btn-outline" style="flex: 1; max-width: 150px;">
                                <i class="fab fa-linkedin"></i> LinkedIn
                            </button>
                        </div>
                    </div>

                    <p style="text-align: center; margin-top: 25px; color: #6b7280;">
                        Vous n'avez pas de compte ?
                        <a href="inscription.html" style="color: #3b82f6; font-weight: 600;">Inscrivez-vous</a>
                    </p>
                </form>
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
                        <li><a href="index.html#jobs">Alertes Emploi</a></li>
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
                <p>&copy; 2025 JobHub. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="js/alerts.js"></script>
    <script src="js/update-alerts.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
