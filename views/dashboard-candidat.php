<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Dashboard - JobHub</title>
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
                    <li><a href="dashboard-candidat.html" class="nav-link active">Mon Dashboard</a></li>
                    <li><a href="contact.html" class="nav-link">Contact</a></li>
                </ul>
                <div class="nav-buttons">
                    <a href="profil-candidat.html" class="btn btn-outline"><i class="fas fa-user"></i> Mon Profil</a>
                    <a href="#" onclick="logout()" class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- DASHBOARD HEADER -->
    <section class="page-header">
        <div class="container">
            <h1><i class="fas fa-tachometer-alt"></i> Bienvenue, <span id="userName">Candidat</span> !</h1>
            <p>Gérez vos candidatures et trouvez votre emploi idéal</p>
        </div>
    </section>

    <!-- DASHBOARD CONTENT -->
    <section class="inscription-section">
        <div class="container">
            <!-- STATS CARDS -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 50px;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="opacity: 0.9; margin-bottom: 10px;">Candidatures envoyées</p>
                            <h2 style="font-size: 36px; font-weight: 700;">12</h2>
                        </div>
                        <i class="fas fa-paper-plane" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 30px; border-radius: 12px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="opacity: 0.9; margin-bottom: 10px;">En attente</p>
                            <h2 style="font-size: 36px; font-weight: 700;">8</h2>
                        </div>
                        <i class="fas fa-clock" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 30px; border-radius: 12px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="opacity: 0.9; margin-bottom: 10px;">Entretiens</p>
                            <h2 style="font-size: 36px; font-weight: 700;">3</h2>
                        </div>
                        <i class="fas fa-handshake" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 30px; border-radius: 12px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="opacity: 0.9; margin-bottom: 10px;">Offres sauvegardées</p>
                            <h2 style="font-size: 36px; font-weight: 700;">15</h2>
                        </div>
                        <i class="fas fa-heart" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div style="margin-bottom: 50px;">
                <h2 class="section-title" style="text-align: left; font-size: 28px; margin-bottom: 30px;">Actions rapides</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <a href="index.html#jobs" class="category-card" style="text-decoration: none;">
                        <div class="category-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 style="font-size: 18px;">Chercher un emploi</h3>
                    </a>
                    <a href="profil-candidat.html" class="category-card" style="text-decoration: none;">
                        <div class="category-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h3 style="font-size: 18px;">Modifier mon profil</h3>
                    </a>
                    <a href="#" onclick="alert('Fonctionnalité bientôt disponible !')" class="category-card" style="text-decoration: none;">
                        <div class="category-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <h3 style="font-size: 18px;">Mettre à jour CV</h3>
                    </a>
                    <a href="#" onclick="alert('Alertes emploi configurées avec succès !')" class="category-card" style="text-decoration: none;">
                        <div class="category-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 style="font-size: 18px;">Alertes emploi</h3>
                    </a>
                </div>
            </div>

            <!-- MES CANDIDATURES -->
            <div style="margin-bottom: 50px;">
                <h2 class="section-title" style="text-align: left; font-size: 28px; margin-bottom: 30px;">Mes candidatures récentes</h2>

                <div style="background: white; border: 2px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
                    <!-- Candidature 1 -->
                    <div style="padding: 25px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 20px; flex: 1;">
                            <img src="https://logo.clearbit.com/google.com" alt="Google" style="width: 60px; height: 60px; border-radius: 8px; border: 1px solid #e5e7eb;">
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 5px;">Développeur Full Stack Senior</h3>
                                <p style="color: #6b7280; margin-bottom: 8px;">Google Inc.</p>
                                <div style="display: flex; gap: 15px; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-map-marker-alt" style="color: #3b82f6;"></i> Paris</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Il y a 2 jours</span>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="background: #fef3c7; color: #d97706; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                <i class="fas fa-hourglass-half"></i> En attente
                            </span>
                            <button class="btn btn-outline" style="padding: 10px 20px;" onclick="alert('Candidature annulée')">Annuler</button>
                        </div>
                    </div>

                    <!-- Candidature 2 -->
                    <div style="padding: 25px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 20px; flex: 1;">
                            <img src="https://logo.clearbit.com/microsoft.com" alt="Microsoft" style="width: 60px; height: 60px; border-radius: 8px; border: 1px solid #e5e7eb;">
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 5px;">UI/UX Designer</h3>
                                <p style="color: #6b7280; margin-bottom: 8px;">Microsoft Corporation</p>
                                <div style="display: flex; gap: 15px; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-map-marker-alt" style="color: #3b82f6;"></i> Lyon</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Il y a 5 jours</span>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="background: #dbeafe; color: #3b82f6; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                <i class="fas fa-eye"></i> Vue par l'entreprise
                            </span>
                            <button class="btn btn-outline" style="padding: 10px 20px;" onclick="alert('Candidature annulée')">Annuler</button>
                        </div>
                    </div>

                    <!-- Candidature 3 -->
                    <div style="padding: 25px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 20px; flex: 1;">
                            <img src="https://logo.clearbit.com/netflix.com" alt="Netflix" style="width: 60px; height: 60px; border-radius: 8px; border: 1px solid #e5e7eb;">
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 5px;">Ingénieur DevOps</h3>
                                <p style="color: #6b7280; margin-bottom: 8px;">Netflix</p>
                                <div style="display: flex; gap: 15px; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-map-marker-alt" style="color: #3b82f6;"></i> Toulouse</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Il y a 1 semaine</span>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="background: #dcfce7; color: #10b981; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                <i class="fas fa-check-circle"></i> Entretien programmé
                            </span>
                            <button class="btn btn-primary" style="padding: 10px 20px;" onclick="alert('Entretien le 15/01/2025 à 14h00 par visioconférence. Lien envoyé par email.')">Voir détails</button>
                        </div>
                    </div>

                    <!-- Candidature 4 -->
                    <div style="padding: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 20px; flex: 1;">
                            <img src="https://logo.clearbit.com/apple.com" alt="Apple" style="width: 60px; height: 60px; border-radius: 8px; border: 1px solid #e5e7eb;">
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 5px;">Développeur iOS</h3>
                                <p style="color: #6b7280; margin-bottom: 8px;">Apple Inc.</p>
                                <div style="display: flex; gap: 15px; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-map-marker-alt" style="color: #3b82f6;"></i> Marseille</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Il y a 2 semaines</span>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="background: #fee2e2; color: #dc2626; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                <i class="fas fa-times-circle"></i> Refusée
                            </span>
                            <button class="btn btn-outline" style="padding: 10px 20px;" onclick="alert('Feedback : Excellent profil mais nous recherchons quelqu\\'un avec plus d\\'expérience en Swift.')">Voir feedback</button>
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="#" class="btn btn-primary" onclick="alert('Affichage de toutes vos candidatures...')">Voir toutes mes candidatures</a>
                </div>
            </div>

            <!-- OFFRES RECOMMANDÉES -->
            <div>
                <h2 class="section-title" style="text-align: left; font-size: 28px; margin-bottom: 30px;">Offres recommandées pour vous</h2>

                <div class="jobs-grid">
                    <div class="job-card">
                        <div class="job-header">
                            <img src="https://logo.clearbit.com/salesforce.com" alt="Salesforce" class="company-logo">
                            <span class="job-badge">Temps Plein</span>
                        </div>
                        <h3 class="job-title">Développeur JavaScript</h3>
                        <p class="company-name">Salesforce</p>
                        <div class="job-details">
                            <span><i class="fas fa-map-marker-alt"></i> Paris, France</span>
                            <span><i class="fas fa-wallet"></i> 55k - 70k €/an</span>
                        </div>
                        <div class="job-tags">
                            <span>JavaScript</span>
                            <span>React</span>
                            <span>Node.js</span>
                        </div>
                        <div class="job-footer">
                            <span class="job-time"><i class="far fa-clock"></i> Il y a 1 jour</span>
                            <a href="job-details.html" class="btn btn-apply">Voir l'offre</a>
                        </div>
                    </div>

                    <div class="job-card">
                        <div class="job-header">
                            <img src="https://logo.clearbit.com/adobe.com" alt="Adobe" class="company-logo">
                            <span class="job-badge">Temps Plein</span>
                        </div>
                        <h3 class="job-title">Frontend Developer</h3>
                        <p class="company-name">Adobe Systems</p>
                        <div class="job-details">
                            <span><i class="fas fa-map-marker-alt"></i> Nice, France</span>
                            <span><i class="fas fa-wallet"></i> 48k - 62k €/an</span>
                        </div>
                        <div class="job-tags">
                            <span>Vue.js</span>
                            <span>TypeScript</span>
                            <span>CSS</span>
                        </div>
                        <div class="job-footer">
                            <span class="job-time"><i class="far fa-clock"></i> Il y a 2 jours</span>
                            <a href="job-details.html" class="btn btn-apply">Voir l'offre</a>
                        </div>
                    </div>

                    <div class="job-card">
                        <div class="job-header">
                            <img src="https://logo.clearbit.com/spotify.com" alt="Spotify" class="company-logo">
                            <span class="job-badge">Temps Plein</span>
                        </div>
                        <h3 class="job-title">Software Engineer</h3>
                        <p class="company-name">Spotify</p>
                        <div class="job-details">
                            <span><i class="fas fa-map-marker-alt"></i> Bordeaux, France</span>
                            <span><i class="fas fa-wallet"></i> 52k - 68k €/an</span>
                        </div>
                        <div class="job-tags">
                            <span>Python</span>
                            <span>Java</span>
                            <span>AWS</span>
                        </div>
                        <div class="job-footer">
                            <span class="job-time"><i class="far fa-clock"></i> Il y a 3 jours</span>
                            <a href="job-details.html" class="btn btn-apply">Voir l'offre</a>
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
