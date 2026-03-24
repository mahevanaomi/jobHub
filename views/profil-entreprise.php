<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Entreprise - JobHub</title>
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
                    <li><a href="dashboard-entreprise.html" class="nav-link">Dashboard</a></li>
                    <li><a href="poster-offre.html" class="nav-link">Publier une Offre</a></li>
                    <li><a href="contact.html" class="nav-link">Contact</a></li>
                </ul>
                <div class="nav-buttons">
                    <a href="profil-entreprise.html" class="btn btn-outline"><i class="fas fa-building"></i> Mon Profil</a>
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
            <h1><i class="fas fa-building"></i> Profil Entreprise</h1>
            <p>Gérez les informations de votre entreprise</p>
        </div>
    </section>

    <!-- PROFILE CONTENT -->
    <section class="inscription-section">
        <div class="container">
            <div class="inscription-container" style="max-width: 900px;">
                <form class="inscription-form" id="profilForm">
                    <!-- LOGO ENTREPRISE -->
                    <div style="text-align: center; margin-bottom: 40px;">
                        <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 36px; font-weight: 700;">
                            <i class="fas fa-building"></i>
                        </div>
                        <button type="button" class="btn btn-outline" onclick="alert('Fonctionnalité de changement de logo bientôt disponible !')">
                            <i class="fas fa-image"></i> Changer le logo
                        </button>
                    </div>

                    <h3 style="margin-bottom: 25px; font-size: 24px;"><i class="fas fa-building"></i> Informations de l'entreprise</h3>

                    <div class="form-group">
                        <label for="nomEntreprise">Nom de l'entreprise *</label>
                        <input type="text" id="nomEntreprise" name="nomEntreprise" required value="TechCorp Solutions">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="secteur">Secteur d'activité *</label>
                            <select id="secteur" name="secteur" required>
                                <option value="technologie" selected>Technologie</option>
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
                            <label for="taille">Taille de l'entreprise *</label>
                            <select id="taille" name="taille" required>
                                <option value="1-10">1 à 10 employés</option>
                                <option value="11-50">11 à 50 employés</option>
                                <option value="51-200" selected>51 à 200 employés</option>
                                <option value="201-500">201 à 500 employés</option>
                                <option value="500+">Plus de 500 employés</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description de l'entreprise</label>
                        <textarea id="description" name="description" rows="5">TechCorp Solutions est une entreprise innovante spécialisée dans le développement de solutions logicielles sur mesure. Nous aidons nos clients à transformer leurs idées en produits digitaux performants.</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="siteWeb">Site web</label>
                            <input type="url" id="siteWeb" name="siteWeb" value="https://techcorp-solutions.com">
                        </div>
                        <div class="form-group">
                            <label for="anneeCreation">Année de création</label>
                            <input type="number" id="anneeCreation" name="anneeCreation" value="2015" min="1900" max="2024">
                        </div>
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-map-marker-alt"></i> Localisation</h3>

                    <div class="form-group">
                        <label for="adresse">Adresse complète</label>
                        <input type="text" id="adresse" name="adresse" value="42 Avenue des Champs-Élysées">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ville">Ville *</label>
                            <input type="text" id="ville" name="ville" required value="Paris">
                        </div>
                        <div class="form-group">
                            <label for="codePostal">Code postal</label>
                            <input type="text" id="codePostal" name="codePostal" value="75008">
                        </div>
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

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-user-tie"></i> Contact principal</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nomContact">Nom du contact *</label>
                            <input type="text" id="nomContact" name="nomContact" required value="Marie Dubois">
                        </div>
                        <div class="form-group">
                            <label for="posteContact">Poste</label>
                            <input type="text" id="posteContact" name="posteContact" value="Responsable RH">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="emailContact">Email *</label>
                            <input type="email" id="emailContact" name="emailContact" required value="marie.dubois@techcorp.com">
                        </div>
                        <div class="form-group">
                            <label for="telephoneContact">Téléphone *</label>
                            <input type="tel" id="telephoneContact" name="telephoneContact" required value="+33 1 23 45 67 89">
                        </div>
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-share-alt"></i> Réseaux sociaux</h3>

                    <div class="form-group">
                        <label for="linkedin">LinkedIn (page entreprise)</label>
                        <input type="url" id="linkedin" name="linkedin" placeholder="https://linkedin.com/company/votre-entreprise" value="https://linkedin.com/company/techcorp-solutions">
                    </div>

                    <div class="form-group">
                        <label for="twitter">Twitter</label>
                        <input type="url" id="twitter" name="twitter" placeholder="https://twitter.com/votre-entreprise" value="https://twitter.com/techcorp">
                    </div>

                    <div class="form-group">
                        <label for="facebook">Facebook</label>
                        <input type="url" id="facebook" name="facebook" placeholder="https://facebook.com/votre-entreprise">
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-cog"></i> Paramètres</h3>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="profilPublic" name="profilPublic" checked style="width: auto; margin-right: 8px;">
                            Rendre le profil de l'entreprise visible publiquement
                        </label>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="notifCandidatures" name="notifCandidatures" checked style="width: auto; margin-right: 8px;">
                            Recevoir des notifications pour les nouvelles candidatures
                        </label>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="newsletter" name="newsletter" checked style="width: auto; margin-right: 8px;">
                            Recevoir la newsletter entreprise JobHub
                        </label>
                    </div>

                    <div class="form-actions" style="display: flex; gap: 15px; margin-top: 40px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                        <button type="button" class="btn btn-outline" onclick="window.location.href='dashboard-entreprise.html'">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                    </div>

                    <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #e5e7eb;">
                        <button type="button" class="btn btn-outline" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer le compte de l\\'entreprise ? Cette action est irréversible.')) alert('Compte supprimé')" style="color: #dc2626; border-color: #dc2626;">
                            <i class="fas fa-trash"></i> Supprimer le compte entreprise
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
