<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - JobHub</title>
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
            <h1>Rejoignez JobHub</h1>
            <p>Créez votre compte et accédez à des milliers d'opportunités</p>
        </div>
    </section>

    <!-- INSCRIPTION SECTION -->
    <section class="inscription-section">
        <div class="container">
            <div class="inscription-container">
                <h2 class="section-title">Choisissez votre type de compte</h2>
                <p class="section-subtitle">Sélectionnez le type de compte qui correspond à vos besoins</p>

                <div class="account-type-selection">
                    <div class="account-type-card active" data-type="candidat">
                        <div class="account-type-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3>Je cherche un emploi</h3>
                        <p>Créez votre profil, postulez aux offres et trouvez votre emploi idéal</p>
                    </div>

                    <div class="account-type-card" data-type="entreprise">
                        <div class="account-type-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>Je recrute des talents</h3>
                        <p>Publiez vos offres d'emploi et trouvez les meilleurs candidats</p>
                    </div>
                </div>

                <form class="inscription-form" id="inscriptionForm">
                    <h3 style="margin-bottom: 25px; font-size: 24px;">Informations personnelles</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="prenom">Prénom *</label>
                            <input type="text" id="prenom" name="prenom" required>
                        </div>
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Mot de passe *</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirmer le mot de passe *</label>
                            <input type="password" id="confirmPassword" name="confirmPassword" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telephone">Téléphone *</label>
                        <input type="tel" id="telephone" name="telephone" required>
                    </div>

                    <!-- CHAMPS POUR CANDIDATS -->
                    <div class="candidate-field">
                        <h3 style="margin: 30px 0 25px; font-size: 24px;">Informations professionnelles</h3>

                        <div class="form-group">
                            <label for="titre">Titre professionnel *</label>
                            <input type="text" id="titre" name="titre" placeholder="Ex: Développeur Full Stack">
                        </div>

                        <div class="form-group">
                            <label for="experience">Années d'expérience</label>
                            <select id="experience" name="experience">
                                <option value="">Sélectionnez...</option>
                                <option value="0-1">Moins d'un an</option>
                                <option value="1-3">1 à 3 ans</option>
                                <option value="3-5">3 à 5 ans</option>
                                <option value="5-10">5 à 10 ans</option>
                                <option value="10+">Plus de 10 ans</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="competences">Compétences principales</label>
                            <input type="text" id="competences" name="competences" placeholder="Ex: JavaScript, React, Node.js...">
                        </div>

                        <div class="form-group">
                            <label for="cv">CV (optionnel)</label>
                            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx">
                        </div>

                        <div class="form-group">
                            <label for="bio">Bio / Présentation</label>
                            <textarea id="bio" name="bio" placeholder="Parlez-nous de vous, de votre parcours et de vos objectifs professionnels..."></textarea>
                        </div>
                    </div>

                    <!-- CHAMPS POUR ENTREPRISES -->
                    <div class="company-field" style="display: none;">
                        <h3 style="margin: 30px 0 25px; font-size: 24px;">Informations sur l'entreprise</h3>

                        <div class="form-group">
                            <label for="nomEntreprise">Nom de l'entreprise *</label>
                            <input type="text" id="nomEntreprise" name="nomEntreprise">
                        </div>

                        <div class="form-group">
                            <label for="secteur">Secteur d'activité</label>
                            <select id="secteur" name="secteur">
                                <option value="">Sélectionnez...</option>
                                <option value="technologie">Technologie</option>
                                <option value="finance">Finance</option>
                                <option value="sante">Santé</option>
                                <option value="education">Éducation</option>
                                <option value="commerce">Commerce</option>
                                <option value="industrie">Industrie</option>
                                <option value="services">Services</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="taille">Taille de l'entreprise</label>
                            <select id="taille" name="taille">
                                <option value="">Sélectionnez...</option>
                                <option value="1-10">1 à 10 employés</option>
                                <option value="11-50">11 à 50 employés</option>
                                <option value="51-200">51 à 200 employés</option>
                                <option value="201-500">201 à 500 employés</option>
                                <option value="500+">Plus de 500 employés</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="siteWeb">Site web de l'entreprise</label>
                            <input type="url" id="siteWeb" name="siteWeb" placeholder="https://www.exemple.com">
                        </div>

                        <div class="form-group">
                            <label for="descriptionEntreprise">Description de l'entreprise</label>
                            <textarea id="descriptionEntreprise" name="descriptionEntreprise" placeholder="Présentez votre entreprise, sa mission et ses valeurs..."></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ville">Ville *</label>
                        <input type="text" id="ville" name="ville" required>
                    </div>

                    <div class="form-group">
                        <label for="pays">Pays *</label>
                        <select id="pays" name="pays" required>
                            <option value="">Sélectionnez...</option>
                            <option value="france" selected>France</option>
                            <option value="belgique">Belgique</option>
                            <option value="suisse">Suisse</option>
                            <option value="canada">Canada</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" required style="width: auto;">
                            <span>J'accepte les conditions d'utilisation et la politique de confidentialité *</span>
                        </label>
                    </div>

                    <div class="form-group" style="margin-top: 10px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" style="width: auto;">
                            <span>Je souhaite recevoir des alertes emploi et des actualités par email</span>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Créer mon compte
                        </button>
                    </div>

                    <p style="text-align: center; margin-top: 20px; color: #6b7280;">
                        Vous avez déjà un compte ?
                        <a href="#" style="color: #3b82f6; font-weight: 600;">Connectez-vous</a>
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
                        <li><a href="#">Rechercher un Emploi</a></li>
                        <li><a href="#">Créer un Profil</a></li>
                        <li><a href="#">Télécharger CV</a></li>
                        <li><a href="#">Alertes Emploi</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Pour les Entreprises</h3>
                    <ul>
                        <li><a href="#">Publier une Offre</a></li>
                        <li><a href="#">Rechercher des Talents</a></li>
                        <li><a href="#">Plans & Tarifs</a></li>
                        <li><a href="#">Espace Entreprise</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>À Propos</h3>
                    <ul>
                        <li><a href="#">Qui sommes-nous ?</a></li>
                        <li><a href="#">Nous Contacter</a></li>
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
