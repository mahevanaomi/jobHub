<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

guest_only();

if (is_post()) {
    verify_csrf($_POST['_csrf'] ?? null);
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = authenticate($email, $password);

    if (!$user) {
        flash('error', 'Identifiants invalides.');
        remember_old_input(['email' => $email]);
        redirect('/views/login.php');
    }

    login_user($user);
    clear_old_input();
    flash('success', 'Connexion réussie.');
    if ($user['role'] === 'admin') {
        redirect('/views/dashboard-admin.php');
    }
    redirect($user['role'] === 'company' ? '/views/dashboard-entreprise.php' : '/views/dashboard-candidat.php');
}

$pageTitle = 'Connexion - JobHub';
$assetBase = '..';
$rootPath = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<section class="page-header page-header-rich">
    <div class="container">
        <div class="page-header-inner">
            <span class="page-eyebrow">Accès sécurisé</span>
            <h1>Connexion</h1>
            <p>Accède à ton espace recruteur ou candidat.</p>
            <div class="page-header-pills">
                <span>Sessions actives</span>
                <span>CSRF vérifié</span>
                <span>Redirection par rôle</span>
            </div>
        </div>
    </div>
</section>

<section class="inscription-section">
    <div class="container">
        <div class="auth-shell">
            <aside class="auth-intro">
                <span class="page-eyebrow">Accès rapide</span>
                <h2>Retrouve ton dashboard en quelques secondes.</h2>
                <p>La connexion redirige automatiquement chaque rôle vers son espace métier. Tu peux aussi tester le projet avec les comptes de démonstration déjà présents en base.</p>
                <div class="auth-metrics">
                    <div class="metric-chip"><strong>Candidat</strong><span>talent@jobhub.cm</span></div>
                    <div class="metric-chip"><strong>Entreprise</strong><span>demo@jobhub.cm</span></div>
                    <div class="metric-chip"><strong>Mot de passe</strong><span>password</span></div>
                </div>
                <ul class="auth-list">
                    <li>Dashboard candidat avec candidatures et recommandations</li>
                    <li>Dashboard entreprise avec offres et suivi des statuts</li>
                    <li>Connexion branchée à la base MySQL réelle</li>
                </ul>
            </aside>

            <div class="inscription-container inscription-container-narrow">
            <form method="post" class="inscription-form">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" required value="<?= e(old('email')) ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </div>
                <p class="form-note">Comptes démo après import SQL: `demo@jobhub.cm` et `talent@jobhub.cm`, mot de passe `password`.</p>
                <p class="auth-switch">Pas encore de compte ? <a href="/views/inscription.php">Créer un compte</a></p>
            </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
