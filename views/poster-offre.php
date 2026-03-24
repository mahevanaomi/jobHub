<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier une Offre - JobHub</title>
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
                    <li><a href="poster-offre.html" class="nav-link active">Publier une Offre</a></li>
                    <li><a href="contact.html" class="nav-link">Contact</a></li>
                </ul>
                <div class="nav-buttons">
                    <a href="profil-entreprise.html" class="btn btn-outline"><i class="fas fa-building"></i> Mon Profil</a>
                    <a href="dashboard-entreprise.html" class="btn btn-primary"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
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
            <h1><i class="fas fa-plus-circle"></i> Publier une Offre d'Emploi</h1>
            <p>Créez votre annonce et trouvez le candidat idéal</p>
        </div>
    </section>

    <!-- FORM SECTION -->
    <section class="inscription-section">
        <div class="container">
            <div class="inscription-container" style="max-width: 900px;">
                <form class="inscription-form" id="posterOffreForm">
                    <h3 style="margin-bottom: 25px; font-size: 24px;"><i class="fas fa-info-circle"></i> Informations générales</h3>

                    <div class="form-group">
                        <label for="titre">Titre du poste *</label>
                        <input type="text" id="titre" name="titre" required placeholder="Ex: Développeur Full Stack Senior">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="categorie">Catégorie *</label>
                            <select id="categorie" name="categorie" required>
                                <option value="">Sélectionnez...</option>
                                <option value="developpement">Développement</option>
                                <option value="design">Design</option>
                                <option value="marketing">Marketing</option>
                                <option value="finance">Finance</option>
                                <option value="rh">Ressources Humaines</option>
                                <option value="ingenierie">Ingénierie</option>
                                <option value="support">Support Client</option>
                                <option value="it">IT & Réseau</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="typeContrat">Type de contrat *</label>
                            <select id="typeContrat" name="typeContrat" required>
                                <option value="">Sélectionnez...</option>
                                <option value="temps-plein">Temps Plein (CDI)</option>
                                <option value="temps-partiel">Temps Partiel</option>
                                <option value="freelance">Freelance / Mission</option>
                                <option value="stage">Stage</option>
                                <option value="alternance">Alternance</option>
                                <option value="cdd">CDD</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ville">Ville *</label>
                            <input type="text" id="ville" name="ville" required placeholder="Ex: Paris">
                        </div>
                        <div class="form-group">
                            <label for="pays">Pays *</label>
                            <select id="pays" name="pays" required>
                                <option value="france" selected>France</option>
                                <option value="belgique">Belgique</option>
                                <option value="suisse">Suisse</option>
                                <option value="canada">Canada</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="teletravail" name="teletravail" style="width: auto; margin-right: 8px;">
                            Télétravail possible
                        </label>
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-wallet"></i> Rémunération</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="salaireMin">Salaire minimum (€) *</label>
                            <input type="number" id="salaireMin" name="salaireMin" required placeholder="40000">
                        </div>
                        <div class="form-group">
                            <label for="salaireMax">Salaire maximum (€) *</label>
                            <input type="number" id="salaireMax" name="salaireMax" required placeholder="60000">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="periodeSalaire">Période</label>
                        <select id="periodeSalaire" name="periodeSalaire">
                            <option value="annuel" selected>Par an</option>
                            <option value="mensuel">Par mois</option>
                            <option value="jour">Par jour</option>
                            <option value="heure">Par heure</option>
                        </select>
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-file-alt"></i> Description du poste</h3>

                    <div class="form-group">
                        <label for="description">Description complète *</label>
                        <textarea id="description" name="description" required placeholder="Décrivez le poste, les missions, l'environnement de travail..." rows="6"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="responsabilites">Responsabilités principales *</label>
                        <textarea id="responsabilites" name="responsabilites" required placeholder="Listez les principales responsabilités du poste..." rows="5"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="competences">Compétences requises *</label>
                        <textarea id="competences" name="competences" required placeholder="Listez les compétences et qualifications requises..." rows="5"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="competencesPlus">Compétences appréciées (optionnel)</label>
                        <textarea id="competencesPlus" name="competencesPlus" placeholder="Compétences qui seraient un plus..." rows="4"></textarea>
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-gift"></i> Avantages</h3>

                    <div class="form-group">
                        <label for="avantages">Avantages offerts</label>
                        <textarea id="avantages" name="avantages" placeholder="Ex: Mutuelle, tickets restaurant, télétravail, formations..." rows="4"></textarea>
                    </div>

                    <h3 style="margin: 40px 0 25px; font-size: 24px;"><i class="fas fa-cogs"></i> Paramètres</h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="experience">Expérience requise</label>
                            <select id="experience" name="experience">
                                <option value="">Sélectionnez...</option>
                                <option value="debutant">Débutant accepté</option>
                                <option value="0-1">Moins d'un an</option>
                                <option value="1-3">1 à 3 ans</option>
                                <option value="3-5">3 à 5 ans</option>
                                <option value="5-10">5 à 10 ans</option>
                                <option value="10+">Plus de 10 ans</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="niveauEtude">Niveau d'étude</label>
                            <select id="niveauEtude" name="niveauEtude">
                                <option value="">Sélectionnez...</option>
                                <option value="bac">Bac</option>
                                <option value="bac+2">Bac+2</option>
                                <option value="bac+3">Bac+3 (Licence)</option>
                                <option value="bac+5">Bac+5 (Master)</option>
                                <option value="doctorat">Doctorat</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tags">Mots-clés / Tags (séparés par des virgules)</label>
                        <input type="text" id="tags" name="tags" placeholder="Ex: JavaScript, React, Node.js, MongoDB">
                    </div>

                    <div class="form-group">
                        <label for="dateExpiration">Date d'expiration de l'offre</label>
                        <input type="date" id="dateExpiration" name="dateExpiration">
                    </div>

                    <div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin: 30px 0;">
                        <h4 style="margin-bottom: 15px; color: #1f2937;"><i class="fas fa-info-circle"></i> Informations importantes</h4>
                        <ul style="list-style: disc; margin-left: 20px; color: #6b7280;">
                            <li style="margin-bottom: 8px;">Votre offre sera visible par des milliers de candidats qualifiés</li>
                            <li style="margin-bottom: 8px;">Vous recevrez les candidatures directement dans votre dashboard</li>
                            <li style="margin-bottom: 8px;">L'offre reste active pendant 30 jours (modifiable)</li>
                            <li style="margin-bottom: 8px;">Vous pouvez modifier ou désactiver l'offre à tout moment</li>
                        </ul>
                    </div>

                    <div class="form-actions" style="display: flex; gap: 15px;">
                        <button type="submit" class="btn btn-primary" style="flex: 1;">
                            <i class="fas fa-paper-plane"></i> Publier l'Offre
                        </button>
                        <button type="button" class="btn btn-outline" onclick="if(confirm('Voulez-vous sauvegarder comme brouillon ?')) alert('Offre sauvegardée en brouillon')">
                            <i class="fas fa-save"></i> Sauvegarder en brouillon
                        </button>
                        <button type="button" class="btn btn-outline" onclick="if(confirm('Êtes-vous sûr de vouloir annuler ?')) window.location.href='dashboard-entreprise.html'">
                            <i class="fas fa-times"></i> Annuler
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
