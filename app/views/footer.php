    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <div class="footer-brand">
                        <div class="logo-mark">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="logo">
                            <span>Job<strong>Hub</strong></span>
                            <small>PHP hiring platform</small>
                        </div>
                    </div>
                    <p>Plateforme de recrutement moderne conçue pour les candidats ambitieux et les entreprises sérieuses.</p>
                </div>
                <div class="footer-col">
                    <h3>Candidats</h3>
                    <ul>
                        <li><a href="<?= e($rootPath ?? '/index.php') ?>#jobs">Rechercher un emploi</a></li>
                        <li><a href="<?= e($viewsPath ?? '/views') ?>/dashboard-candidat.php">Mon espace</a></li>
                        <li><a href="<?= e($viewsPath ?? '/views') ?>/profil-candidat.php">Mon profil</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Entreprises</h3>
                    <ul>
                        <li><a href="<?= e($viewsPath ?? '/views') ?>/poster-offre.php">Publier une offre</a></li>
                        <li><a href="<?= e($viewsPath ?? '/views') ?>/dashboard-entreprise.php">Gérer mes offres</a></li>
                        <li><a href="<?= e($viewsPath ?? '/views') ?>/profil-entreprise.php">Profil entreprise</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Découvrir</h3>
                    <ul>
                        <li><a href="<?= e($viewsPath ?? '/views') ?>/about.php">À propos</a></li>
                        <li><a href="<?= e($viewsPath ?? '/views') ?>/contact.php">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 JobHub. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="<?= e($assetBase ?? '') ?>/views/js/main.js"></script>
</body>
</html>
