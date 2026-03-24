<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobHub - Votre Avenir Commence Ici</title>
    <link rel="stylesheet" href="views/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-briefcase"></i>
                    <span>Job<strong>Hub</strong></span>
                </div>
                <ul class="nav-menu">
                    <li><a href="#home" class="nav-link active">Accueil</a></li>
                    <li><a href="#jobs" class="nav-link">Emplois</a></li>
                    <li><a href="#categories" class="nav-link">Catégories</a></li>
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

    <!-- HERO SECTION -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Votre Avenir Commence Ici</h1>
                <p class="hero-subtitle">Trouvez l'emploi de vos rêves parmi des milliers d'offres disponibles</p>

                <div class="search-box">
                    <div class="search-input-group">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Titre du poste, mots-clés..." id="searchInput">
                    </div>
                    <div class="search-input-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" placeholder="Ville, région..." id="locationInput">
                    </div>
                    <button class="btn btn-search">Rechercher</button>
                </div>

                <div class="hero-stats">
                    <div class="stat">
                        <h3>12,458</h3>
                        <p>Offres d'emploi</p>
                    </div>
                    <div class="stat">
                        <h3>5,230</h3>
                        <p>Entreprises</p>
                    </div>
                    <div class="stat">
                        <h3>8,947</h3>
                        <p>Candidats</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CATEGORIES SECTION -->
    <section class="categories" id="categories">
        <div class="container">
            <h2 class="section-title">Catégories Populaires</h2>
            <p class="section-subtitle">Explorez les opportunités par domaine</p>

            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>Développement</h3>
                    <p>2,458 emplois</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h3>Design</h3>
                    <p>1,234 emplois</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Marketing</h3>
                    <p>1,867 emplois</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>Finance</h3>
                    <p>987 emplois</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Ressources Humaines</h3>
                    <p>654 emplois</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3>Ingénierie</h3>
                    <p>1,543 emplois</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Support Client</h3>
                    <p>876 emplois</p>
                </div>
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3>IT & Réseau</h3>
                    <p>1,098 emplois</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED JOBS SECTION -->
    <section class="jobs-section" id="jobs">
        <div class="container">
            <h2 class="section-title">Offres d'Emploi Récentes</h2>
            <p class="section-subtitle">Découvrez les dernières opportunités de carrière</p>

            <div class="filters">
                <button class="filter-btn active" data-filter="all">Tous</button>
                <button class="filter-btn" data-filter="temps-plein">Temps Plein</button>
                <button class="filter-btn" data-filter="temps-partiel">Temps Partiel</button>
                <button class="filter-btn" data-filter="freelance">Freelance</button>
                <button class="filter-btn" data-filter="stage">Stage</button>
            </div>

            <div class="jobs-grid">
                <!-- Job Card 1 -->
                <div class="job-card" data-category="temps-plein">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/google.com" alt="Google" class="company-logo">
                        <span class="job-badge">Temps Plein</span>
                    </div>
                    <h3 class="job-title">Développeur Full Stack Senior</h3>
                    <p class="company-name">Google Inc.</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Paris, France</span>
                        <span><i class="fas fa-wallet"></i> 60k - 80k €/an</span>
                    </div>
                    <div class="job-tags">
                        <span>React</span>
                        <span>Node.js</span>
                        <span>MongoDB</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 2 heures</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 2 -->
                <div class="job-card" data-category="temps-plein">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/microsoft.com" alt="Microsoft" class="company-logo">
                        <span class="job-badge">Temps Plein</span>
                    </div>
                    <h3 class="job-title">UI/UX Designer</h3>
                    <p class="company-name">Microsoft Corporation</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Lyon, France</span>
                        <span><i class="fas fa-wallet"></i> 45k - 65k €/an</span>
                    </div>
                    <div class="job-tags">
                        <span>Figma</span>
                        <span>Adobe XD</span>
                        <span>UI Design</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 5 heures</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 3 -->
                <div class="job-card" data-category="freelance">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/amazon.com" alt="Amazon" class="company-logo">
                        <span class="job-badge badge-freelance">Freelance</span>
                    </div>
                    <h3 class="job-title">Data Scientist</h3>
                    <p class="company-name">Amazon Web Services</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Télétravail</span>
                        <span><i class="fas fa-wallet"></i> 500 - 700 €/jour</span>
                    </div>
                    <div class="job-tags">
                        <span>Python</span>
                        <span>Machine Learning</span>
                        <span>TensorFlow</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 1 jour</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 4 -->
                <div class="job-card" data-category="temps-plein">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/apple.com" alt="Apple" class="company-logo">
                        <span class="job-badge">Temps Plein</span>
                    </div>
                    <h3 class="job-title">Développeur iOS</h3>
                    <p class="company-name">Apple Inc.</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Marseille, France</span>
                        <span><i class="fas fa-wallet"></i> 55k - 75k €/an</span>
                    </div>
                    <div class="job-tags">
                        <span>Swift</span>
                        <span>iOS</span>
                        <span>Xcode</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 1 jour</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 5 -->
                <div class="job-card" data-category="stage">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/facebook.com" alt="Meta" class="company-logo">
                        <span class="job-badge badge-stage">Stage</span>
                    </div>
                    <h3 class="job-title">Stagiaire Marketing Digital</h3>
                    <p class="company-name">Meta (Facebook)</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Paris, France</span>
                        <span><i class="fas fa-wallet"></i> 1,200 €/mois</span>
                    </div>
                    <div class="job-tags">
                        <span>SEO</span>
                        <span>Social Media</span>
                        <span>Analytics</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 2 jours</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 6 -->
                <div class="job-card" data-category="temps-plein">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/netflix.com" alt="Netflix" class="company-logo">
                        <span class="job-badge">Temps Plein</span>
                    </div>
                    <h3 class="job-title">Ingénieur DevOps</h3>
                    <p class="company-name">Netflix</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Toulouse, France</span>
                        <span><i class="fas fa-wallet"></i> 65k - 85k €/an</span>
                    </div>
                    <div class="job-tags">
                        <span>Docker</span>
                        <span>Kubernetes</span>
                        <span>AWS</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 3 jours</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 7 -->
                <div class="job-card" data-category="temps-partiel">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/spotify.com" alt="Spotify" class="company-logo">
                        <span class="job-badge badge-partiel">Temps Partiel</span>
                    </div>
                    <h3 class="job-title">Community Manager</h3>
                    <p class="company-name">Spotify</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Bordeaux, France</span>
                        <span><i class="fas fa-wallet"></i> 25k - 35k €/an</span>
                    </div>
                    <div class="job-tags">
                        <span>Community</span>
                        <span>Content</span>
                        <span>Social Media</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 4 jours</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 8 -->
                <div class="job-card" data-category="temps-plein">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/uber.com" alt="Uber" class="company-logo">
                        <span class="job-badge">Temps Plein</span>
                    </div>
                    <h3 class="job-title">Product Manager</h3>
                    <p class="company-name">Uber Technologies</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Paris, France</span>
                        <span><i class="fas fa-wallet"></i> 70k - 90k €/an</span>
                    </div>
                    <div class="job-tags">
                        <span>Product</span>
                        <span>Agile</span>
                        <span>Strategy</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 5 jours</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 9 -->
                <div class="job-card" data-category="freelance">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/airbnb.com" alt="Airbnb" class="company-logo">
                        <span class="job-badge badge-freelance">Freelance</span>
                    </div>
                    <h3 class="job-title">Rédacteur Web</h3>
                    <p class="company-name">Airbnb</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Télétravail</span>
                        <span><i class="fas fa-wallet"></i> 300 - 450 €/jour</span>
                    </div>
                    <div class="job-tags">
                        <span>Rédaction</span>
                        <span>SEO</span>
                        <span>Content</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 1 semaine</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 10 -->
                <div class="job-card" data-category="temps-plein">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/salesforce.com" alt="Salesforce" class="company-logo">
                        <span class="job-badge">Temps Plein</span>
                    </div>
                    <h3 class="job-title">Consultant CRM</h3>
                    <p class="company-name">Salesforce</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Nantes, France</span>
                        <span><i class="fas fa-wallet"></i> 50k - 70k €/an</span>
                    </div>
                    <div class="job-tags">
                        <span>Salesforce</span>
                        <span>CRM</span>
                        <span>Cloud</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 1 semaine</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 11 -->
                <div class="job-card" data-category="temps-plein">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/adobe.com" alt="Adobe" class="company-logo">
                        <span class="job-badge">Temps Plein</span>
                    </div>
                    <h3 class="job-title">Motion Designer</h3>
                    <p class="company-name">Adobe Systems</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Lille, France</span>
                        <span><i class="fas fa-wallet"></i> 42k - 58k €/an</span>
                    </div>
                    <div class="job-tags">
                        <span>After Effects</span>
                        <span>Animation</span>
                        <span>Video</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 1 semaine</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>

                <!-- Job Card 12 -->
                <div class="job-card" data-category="temps-plein">
                    <div class="job-header">
                        <img src="https://logo.clearbit.com/tesla.com" alt="Tesla" class="company-logo">
                        <span class="job-badge">Temps Plein</span>
                    </div>
                    <h3 class="job-title">Ingénieur Mécanique</h3>
                    <p class="company-name">Tesla Motors</p>
                    <div class="job-details">
                        <span><i class="fas fa-map-marker-alt"></i> Strasbourg, France</span>
                        <span><i class="fas fa-wallet"></i> 55k - 75k €/an</span>
                    </div>
                    <div class="job-tags">
                        <span>CAO</span>
                        <span>Mécanique</span>
                        <span>Innovation</span>
                    </div>
                    <div class="job-footer">
                        <span class="job-time"><i class="far fa-clock"></i> Il y a 2 semaines</span>
                        <a href="job-details.html" class="btn btn-apply">Postuler</a>
                    </div>
                </div>
            </div>

            <div class="load-more">
                <button class="btn btn-primary">Voir Plus d'Offres</button>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Vous êtes une entreprise ?</h2>
                <p>Publiez vos offres d'emploi et trouvez les meilleurs talents</p>
                <a href="poster-offre.html" class="btn btn-light">Publier une Offre</a>
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

    <script src="views/js/alerts.js"></script>
    <script src="views/js/update-alerts.js"></script>
    <script src="views/js/main.js"></script>
</body>
</html>
