<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Entreprise - JobHub</title>
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
                    <li><a href="dashboard-entreprise.html" class="nav-link active">Dashboard</a></li>
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

    <!-- DASHBOARD HEADER -->
    <section class="page-header">
        <div class="container">
            <h1><i class="fas fa-building"></i> Espace Entreprise</h1>
            <p>Gérez vos offres d'emploi et recrutez les meilleurs talents</p>
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
                            <p style="opacity: 0.9; margin-bottom: 10px;">Offres actives</p>
                            <h2 style="font-size: 36px; font-weight: 700;">8</h2>
                        </div>
                        <i class="fas fa-briefcase" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 30px; border-radius: 12px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="opacity: 0.9; margin-bottom: 10px;">Candidatures reçues</p>
                            <h2 style="font-size: 36px; font-weight: 700;">156</h2>
                        </div>
                        <i class="fas fa-users" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 30px; border-radius: 12px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="opacity: 0.9; margin-bottom: 10px;">Vues profil</p>
                            <h2 style="font-size: 36px; font-weight: 700;">1,247</h2>
                        </div>
                        <i class="fas fa-eye" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); padding: 30px; border-radius: 12px; color: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="opacity: 0.9; margin-bottom: 10px;">Embauches ce mois</p>
                            <h2 style="font-size: 36px; font-weight: 700;">4</h2>
                        </div>
                        <i class="fas fa-user-check" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div style="margin-bottom: 50px;">
                <h2 class="section-title" style="text-align: left; font-size: 28px; margin-bottom: 30px;">Actions rapides</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <a href="poster-offre.html" class="category-card" style="text-decoration: none;">
                        <div class="category-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h3 style="font-size: 18px;">Publier une offre</h3>
                    </a>
                    <a href="#candidatures" class="category-card" style="text-decoration: none;">
                        <div class="category-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3 style="font-size: 18px;">Voir candidatures</h3>
                    </a>
                    <a href="profil-entreprise.html" class="category-card" style="text-decoration: none;">
                        <div class="category-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 style="font-size: 18px;">Modifier profil</h3>
                    </a>
                    <a href="#" onclick="alert('Statistiques détaillées : \n\n✓ Taux de conversion : 12%\n✓ Temps moyen de recrutement : 18 jours\n✓ Candidatures par offre : 19.5\n✓ Taux de satisfaction : 94%')" class="category-card" style="text-decoration: none;">
                        <div class="category-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 style="font-size: 18px;">Statistiques</h3>
                    </a>
                </div>
            </div>

            <!-- MES OFFRES PUBLIÉES -->
            <div style="margin-bottom: 50px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                    <h2 class="section-title" style="text-align: left; font-size: 28px; margin: 0;">Mes offres publiées</h2>
                    <a href="poster-offre.html" class="btn btn-primary"><i class="fas fa-plus"></i> Nouvelle offre</a>
                </div>

                <div style="background: white; border: 2px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
                    <!-- Offre 1 -->
                    <div style="padding: 25px; border-bottom: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                    <h3 style="font-size: 20px;">Développeur Full Stack Senior</h3>
                                    <span style="background: #dcfce7; color: #10b981; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                </div>
                                <div style="display: flex; gap: 20px; margin-bottom: 15px; flex-wrap: wrap; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-map-marker-alt" style="color: #3b82f6;"></i> Paris, France</span>
                                    <span><i class="fas fa-wallet" style="color: #3b82f6;"></i> 60k - 80k €/an</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Publié il y a 2 jours</span>
                                </div>
                                <div style="background: #f9fafb; padding: 15px; border-radius: 8px; display: flex; gap: 30px; flex-wrap: wrap;">
                                    <div>
                                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">Candidatures</p>
                                        <p style="font-size: 20px; font-weight: 700; color: #3b82f6;">45</p>
                                    </div>
                                    <div>
                                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">Vues</p>
                                        <p style="font-size: 20px; font-weight: 700; color: #3b82f6;">892</p>
                                    </div>
                                    <div>
                                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">Sauvegardes</p>
                                        <p style="font-size: 20px; font-weight: 700; color: #3b82f6;">67</p>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px; flex-direction: column;">
                                <button class="btn btn-primary" onclick="alert('45 candidatures reçues pour cette offre')" style="padding: 10px 20px; white-space: nowrap;">
                                    <i class="fas fa-users"></i> Voir candidatures (45)
                                </button>
                                <button class="btn btn-outline" onclick="alert('Édition de l\\'offre...')" style="padding: 10px 20px;">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <button class="btn btn-outline" onclick="if(confirm('Êtes-vous sûr de vouloir désactiver cette offre ?')) alert('Offre désactivée')" style="padding: 10px 20px; color: #dc2626; border-color: #dc2626;">
                                    <i class="fas fa-pause"></i> Désactiver
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Offre 2 -->
                    <div style="padding: 25px; border-bottom: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                    <h3 style="font-size: 20px;">Product Manager</h3>
                                    <span style="background: #dcfce7; color: #10b981; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                </div>
                                <div style="display: flex; gap: 20px; margin-bottom: 15px; flex-wrap: wrap; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-map-marker-alt" style="color: #3b82f6;"></i> Lyon, France</span>
                                    <span><i class="fas fa-wallet" style="color: #3b82f6;"></i> 55k - 75k €/an</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Publié il y a 5 jours</span>
                                </div>
                                <div style="background: #f9fafb; padding: 15px; border-radius: 8px; display: flex; gap: 30px; flex-wrap: wrap;">
                                    <div>
                                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">Candidatures</p>
                                        <p style="font-size: 20px; font-weight: 700; color: #3b82f6;">32</p>
                                    </div>
                                    <div>
                                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">Vues</p>
                                        <p style="font-size: 20px; font-weight: 700; color: #3b82f6;">654</p>
                                    </div>
                                    <div>
                                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">Sauvegardes</p>
                                        <p style="font-size: 20px; font-weight: 700; color: #3b82f6;">43</p>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px; flex-direction: column;">
                                <button class="btn btn-primary" onclick="alert('32 candidatures reçues pour cette offre')" style="padding: 10px 20px; white-space: nowrap;">
                                    <i class="fas fa-users"></i> Voir candidatures (32)
                                </button>
                                <button class="btn btn-outline" onclick="alert('Édition de l\\'offre...')" style="padding: 10px 20px;">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <button class="btn btn-outline" onclick="if(confirm('Êtes-vous sûr de vouloir désactiver cette offre ?')) alert('Offre désactivée')" style="padding: 10px 20px; color: #dc2626; border-color: #dc2626;">
                                    <i class="fas fa-pause"></i> Désactiver
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Offre 3 -->
                    <div style="padding: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                    <h3 style="font-size: 20px;">Designer UI/UX</h3>
                                    <span style="background: #fef3c7; color: #d97706; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                        <i class="fas fa-clock"></i> Expirée
                                    </span>
                                </div>
                                <div style="display: flex; gap: 20px; margin-bottom: 15px; flex-wrap: wrap; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-map-marker-alt" style="color: #3b82f6;"></i> Marseille, France</span>
                                    <span><i class="fas fa-wallet" style="color: #3b82f6;"></i> 42k - 58k €/an</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Publié il y a 35 jours</span>
                                </div>
                                <div style="background: #f9fafb; padding: 15px; border-radius: 8px; display: flex; gap: 30px; flex-wrap: wrap;">
                                    <div>
                                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">Candidatures</p>
                                        <p style="font-size: 20px; font-weight: 700; color: #6b7280;">28</p>
                                    </div>
                                    <div>
                                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">Vues</p>
                                        <p style="font-size: 20px; font-weight: 700; color: #6b7280;">423</p>
                                    </div>
                                    <div>
                                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 5px;">Sauvegardes</p>
                                        <p style="font-size: 20px; font-weight: 700; color: #6b7280;">31</p>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px; flex-direction: column;">
                                <button class="btn btn-outline" onclick="alert('28 candidatures reçues pour cette offre')" style="padding: 10px 20px; white-space: nowrap;">
                                    <i class="fas fa-users"></i> Voir candidatures (28)
                                </button>
                                <button class="btn btn-primary" onclick="alert('Offre republiée avec succès !')" style="padding: 10px 20px;">
                                    <i class="fas fa-redo"></i> Republier
                                </button>
                                <button class="btn btn-outline" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')) alert('Offre supprimée')" style="padding: 10px 20px; color: #dc2626; border-color: #dc2626;">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CANDIDATURES RÉCENTES -->
            <div id="candidatures">
                <h2 class="section-title" style="text-align: left; font-size: 28px; margin-bottom: 30px;">Candidatures récentes</h2>

                <div style="background: white; border: 2px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
                    <!-- Candidature 1 -->
                    <div style="padding: 25px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 20px; flex: 1;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 700;">
                                JD
                            </div>
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 5px;">Jean Dupont</h3>
                                <p style="color: #6b7280; margin-bottom: 8px;">Développeur Full Stack • 5 ans d'expérience</p>
                                <div style="display: flex; gap: 15px; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-briefcase" style="color: #3b82f6;"></i> Développeur Full Stack Senior</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Il y a 2 heures</span>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <button class="btn btn-outline" onclick="alert('CV téléchargé : jean_dupont_cv.pdf')" style="padding: 10px 20px;">
                                <i class="fas fa-file-pdf"></i> CV
                            </button>
                            <button class="btn btn-primary" onclick="alert('Email envoyé : Bonjour Jean, nous avons bien reçu votre candidature...')" style="padding: 10px 20px;">
                                <i class="fas fa-envelope"></i> Contacter
                            </button>
                            <button class="btn btn-outline" onclick="if(confirm('Refuser cette candidature ?')) alert('Candidature refusée')" style="padding: 10px 20px; color: #dc2626; border-color: #dc2626;">
                                <i class="fas fa-times"></i>
                            </button>
                            <button class="btn btn-primary" onclick="alert('Candidat ajouté aux favoris pour un entretien !')" style="padding: 10px 20px; background: #10b981;">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Candidature 2 -->
                    <div style="padding: 25px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 20px; flex: 1;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 700;">
                                SM
                            </div>
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 5px;">Sophie Martin</h3>
                                <p style="color: #6b7280; margin-bottom: 8px;">Product Manager • 7 ans d'expérience</p>
                                <div style="display: flex; gap: 15px; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-briefcase" style="color: #3b82f6;"></i> Product Manager</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Il y a 1 jour</span>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <button class="btn btn-outline" onclick="alert('CV téléchargé : sophie_martin_cv.pdf')" style="padding: 10px 20px;">
                                <i class="fas fa-file-pdf"></i> CV
                            </button>
                            <button class="btn btn-primary" onclick="alert('Email envoyé : Bonjour Sophie, nous avons bien reçu votre candidature...')" style="padding: 10px 20px;">
                                <i class="fas fa-envelope"></i> Contacter
                            </button>
                            <button class="btn btn-outline" onclick="if(confirm('Refuser cette candidature ?')) alert('Candidature refusée')" style="padding: 10px 20px; color: #dc2626; border-color: #dc2626;">
                                <i class="fas fa-times"></i>
                            </button>
                            <button class="btn btn-primary" onclick="alert('Candidat ajouté aux favoris pour un entretien !')" style="padding: 10px 20px; background: #10b981;">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Candidature 3 -->
                    <div style="padding: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 20px; flex: 1;">
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 700;">
                                PL
                            </div>
                            <div>
                                <h3 style="font-size: 18px; margin-bottom: 5px;">Pierre Lefebvre</h3>
                                <p style="color: #6b7280; margin-bottom: 8px;">Développeur Full Stack • 3 ans d'expérience</p>
                                <div style="display: flex; gap: 15px; font-size: 14px; color: #6b7280;">
                                    <span><i class="fas fa-briefcase" style="color: #3b82f6;"></i> Développeur Full Stack Senior</span>
                                    <span><i class="far fa-calendar" style="color: #3b82f6;"></i> Il y a 2 jours</span>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <button class="btn btn-outline" onclick="alert('CV téléchargé : pierre_lefebvre_cv.pdf')" style="padding: 10px 20px;">
                                <i class="fas fa-file-pdf"></i> CV
                            </button>
                            <button class="btn btn-primary" onclick="alert('Email envoyé : Bonjour Pierre, nous avons bien reçu votre candidature...')" style="padding: 10px 20px;">
                                <i class="fas fa-envelope"></i> Contacter
                            </button>
                            <button class="btn btn-outline" onclick="if(confirm('Refuser cette candidature ?')) alert('Candidature refusée')" style="padding: 10px 20px; color: #dc2626; border-color: #dc2626;">
                                <i class="fas fa-times"></i>
                            </button>
                            <button class="btn btn-primary" onclick="alert('Candidat ajouté aux favoris pour un entretien !')" style="padding: 10px 20px; background: #10b981;">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button class="btn btn-primary" onclick="alert('Affichage de toutes les candidatures reçues...')">Voir toutes les candidatures (156)</button>
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
