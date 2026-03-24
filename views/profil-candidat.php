<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - JobHub</title>
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
                    <li><a href="dashboard-candidat.html" class="nav-link">Mon Dashboard</a></li>
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

    <!-- PAGE HEADER -->
    <section class="page-header">
        <div class="container">
            <h1><i class="fas fa-user-circle"></i> Mon Profil</h1>
            <p>Gérez vos informations personnelles et professionnelles</p>
        </div>
    </section>

    <!-- PROFILE CONTENT -->
    <section class="inscription-section">
        <div class="container">
            <div class="inscription-container" style="max-width: 900px;">
                <form class="inscription-form" id="profilForm">
                    <!-- PHOTO DE PROFIL -->
                    <div style="text-align: center; margin-bottom: 40px;">
                        <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 48px; font-weight: 700;">
                            <span id="initiales">JD</span>
                        </div>
                        <button type="button" class="btn btn-outline" onclick="alert('Fonctionnalité de changement de photo bientôt disponible !')">
                            <i class="fas fa-camera"></i> Changer la photo
                        </button>
                    </div>

                    <h3 style="margin-bottom: 25px; font-size: 24px;"><i class="fas fa-user"></i> Informations personnelles</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" required value="Jean">
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" required value="Dupont">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required value="jean.dupont@email.com">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telephone">Téléphone *</label>
                            <input type="tel" id="telephone" name="telephone" required value="+33 6 12 34 56 78">
                        </div>
                        <div class="form-group">
                            <label for="dateNaissance">Date de naissance</label>
                            <input type="date" id="dateNaissance" name="dateNaissance" value="1990-05-15">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ville">Ville *</label>
                            <input type="text" id="ville" name="ville" required value="Paris">
                        </div>
                        <div class="form-group">
                            <label for="pays">Pays *</label>
                            <select id="pays" name="pays" required>
                                <option value="france" selected>France</option>
                                <option value="belgique">Belgique</option>
                                <option value="suisse">Suisse</option>
                                <option value="canada">Canada</option>
                            </select>
                        </div>
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-briefcase"></i> Informations professionnelles</h3>

                    <div class="form-group">
                        <label for="titre">Titre professionnel *</label>
                        <input type="text" id="titre" name="titre" required value="Développeur Full Stack Senior">
                    </div>

                    <div class="form-group">
                        <label for="experience">Années d'expérience</label>
                        <select id="experience" name="experience">
                            <option value="0-1">Moins d'un an</option>
                            <option value="1-3">1 à 3 ans</option>
                            <option value="3-5">3 à 5 ans</option>
                            <option value="5-10" selected>5 à 10 ans</option>
                            <option value="10+">Plus de 10 ans</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="competences">Compétences principales</label>
                        <input type="text" id="competences" name="competences" value="JavaScript, React, Node.js, MongoDB, TypeScript">
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio / Présentation</label>
                        <textarea id="bio" name="bio" rows="5">Développeur Full Stack passionné avec plus de 5 ans d'expérience dans la création d'applications web modernes. Expert en JavaScript, React et Node.js. Toujours à la recherche de nouveaux défis techniques.</textarea>
                    </div>

                    <div class="form-group">
                        <label for="cv">CV actuel</label>
                        <div style="display: flex; gap: 15px; align-items: center;">
                            <span style="color: #6b7280;"><i class="fas fa-file-pdf" style="color: #dc2626;"></i> jean_dupont_cv.pdf</span>
                            <button type="button" class="btn btn-outline" style="padding: 8px 16px;" onclick="alert('CV téléchargé')">
                                <i class="fas fa-download"></i> Télécharger
                            </button>
                            <button type="button" class="btn btn-primary" style="padding: 8px 16px;" onclick="alert('Sélectionnez un nouveau CV')">
                                <i class="fas fa-upload"></i> Mettre à jour
                            </button>
                        </div>
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-link"></i> Liens professionnels</h3>

                    <div class="form-group">
                        <label for="linkedin">LinkedIn</label>
                        <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/votre-profil" value="https://linkedin.com/in/jean-dupont">
                    </div>

                    <div class="form-group">
                        <label for="github">GitHub</label>
                        <input type="url" id="github" name="github" placeholder="https://github.com/votre-username" value="https://github.com/jeandupont">
                    </div>

                    <div class="form-group">
                        <label for="portfolio">Portfolio / Site Web</label>
                        <input type="url" id="portfolio" name="portfolio" placeholder="https://votre-site.com" value="https://jeandupont.dev">
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-cog"></i> Préférences</h3>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="alertesEmail" name="alertesEmail" checked style="width: auto; margin-right: 8px;">
                            Recevoir des alertes emploi par email
                        </label>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="profilPublic" name="profilPublic" checked style="width: auto; margin-right: 8px;">
                            Rendre mon profil visible aux recruteurs
                        </label>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="newsletter" name="newsletter" checked style="width: auto; margin-right: 8px;">
                            Recevoir la newsletter JobHub
                        </label>
                    </div>

                    <div class="form-actions" style="display: flex; gap: 15px; margin-top: 40px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                        <button type="button" class="btn btn-outline" onclick="window.location.href='dashboard-candidat.html'">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                    </div>

                    <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e5e7eb;">
                        <button type="button" class="btn btn-outline" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')) alert('Compte supprimé')" style="color: #dc2626; border-color: #dc2626;">
                            <i class="fas fa-trash"></i> Supprimer mon compte
                        </button>
                    </div>
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
