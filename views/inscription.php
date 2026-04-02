<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

guest_only();

if (is_post()) {
    verify_csrf($_POST['_csrf'] ?? null);

    $data = [
        'account_type' => $_POST['account_type'] ?? 'candidate',
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'password_confirmation' => $_POST['password_confirmation'] ?? '',
        'phone' => trim($_POST['phone'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'country' => trim($_POST['country'] ?? ''),
        'headline' => trim($_POST['headline'] ?? ''),
        'experience_level' => trim($_POST['experience_level'] ?? ''),
        'skills' => trim($_POST['skills'] ?? ''),
        'bio' => trim($_POST['bio'] ?? ''),
        'linkedin_url' => trim($_POST['linkedin_url'] ?? ''),
        'github_url' => trim($_POST['github_url'] ?? ''),
        'portfolio_url' => trim($_POST['portfolio_url'] ?? ''),
        'company_name' => trim($_POST['company_name'] ?? ''),
        'industry' => trim($_POST['industry'] ?? ''),
        'company_size' => trim($_POST['company_size'] ?? ''),
        'company_description' => trim($_POST['company_description'] ?? ''),
        'website_url' => trim($_POST['website_url'] ?? ''),
        'alerts_enabled' => $_POST['alerts_enabled'] ?? '1',
        'newsletter_enabled' => $_POST['newsletter_enabled'] ?? '1',
    ];

    remember_old_input($data);

    if (!$data['first_name'] || !$data['last_name'] || !$data['email'] || !$data['password'] || !$data['phone'] || !$data['city'] || !$data['country']) {
        flash('error', 'Merci de remplir tous les champs obligatoires.');
        redirect('/views/inscription.php');
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Adresse email invalide.');
        redirect('/views/inscription.php');
    }

    if ($data['password'] !== $data['password_confirmation']) {
        flash('error', 'Les mots de passe ne correspondent pas.');
        redirect('/views/inscription.php');
    }

    if (find_user_by_email($data['email'])) {
        flash('error', 'Cette adresse email est déjà utilisée.');
        redirect('/views/inscription.php');
    }

    if ($data['account_type'] === 'company') {
        if (!$data['company_name']) {
            flash('error', 'Le nom de l\'entreprise est obligatoire.');
            redirect('/views/inscription.php');
        }
        $userId = create_company_account($data);
    } else {
        $userId = create_candidate_account($data);
    }

    login_user(find_user_by_id($userId));
    clear_old_input();
    flash('success', 'Compte créé avec succès.');
    redirect($data['account_type'] === 'company' ? '/views/dashboard-entreprise.php' : '/views/dashboard-candidat.php');
}

$pageTitle = 'Inscription - JobHub';
$assetBase = '..';
$rootPath = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<section class="page-header page-header-rich">
    <div class="container">
        <div class="page-header-inner">
            <span class="page-eyebrow">Onboarding PHP natif</span>
            <h1>Créer un compte</h1>
            <p>Choisis ton espace et démarre avec une vraie persistance en base.</p>
            <div class="page-header-pills">
                <span>Espace candidat</span>
                <span>Espace entreprise</span>
                <span>Connexion directe après création</span>
            </div>
        </div>
    </div>
</section>

<section class="inscription-section">
    <div class="container">
        <div class="auth-shell">
            <aside class="auth-intro">
                <span class="page-eyebrow">Choix guidé</span>
                <h2>Une inscription pensée pour lancer un vrai usage, pas juste une démo.</h2>
                <p>Le compte candidat ouvre le suivi des candidatures et des recommandations. Le compte entreprise active la publication d'offres, la gestion du profil et le traitement des candidatures.</p>
                <div class="auth-metrics">
                    <div class="metric-chip"><strong>74+</strong><span>comptes de test</span></div>
                    <div class="metric-chip"><strong>123</strong><span>offres en base</span></div>
                    <div class="metric-chip"><strong>221</strong><span>candidatures suivies</span></div>
                </div>
                <ul class="auth-list">
                    <li>Connexion automatique après création du compte</li>
                    <li>Structure adaptée aux deux rôles métier</li>
                    <li>Profils déjà reliés aux dashboards et aux offres</li>
                </ul>
            </aside>

            <div class="inscription-container">
            <form method="post" class="inscription-form">
                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

                <div class="form-group">
                    <label for="account_type">Type de compte</label>
                    <select id="account_type" name="account_type" required>
                        <option value="candidate" <?= old('account_type', 'candidate') === 'candidate' ? 'selected' : '' ?>>Candidat</option>
                        <option value="company" <?= old('account_type') === 'company' ? 'selected' : '' ?>>Entreprise</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">Prénom</label>
                        <input type="text" id="first_name" name="first_name" required value="<?= e(old('first_name')) ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Nom</label>
                        <input type="text" id="last_name" name="last_name" required value="<?= e(old('last_name')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?= e(old('email')) ?>">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmation</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="text" id="phone" name="phone" required value="<?= e(old('phone')) ?>">
                    </div>
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <input type="text" id="city" name="city" required value="<?= e(old('city')) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="country">Pays</label>
                    <input type="text" id="country" name="country" required value="<?= e(old('country', 'Cameroun')) ?>">
                </div>

                <div class="form-grid-two">
                    <div class="form-panel">
                        <h3>Candidat</h3>
                        <div class="form-group">
                            <label for="headline">Titre professionnel</label>
                            <input type="text" id="headline" name="headline" value="<?= e(old('headline')) ?>">
                        </div>
                        <div class="form-group">
                            <label for="experience_level">Expérience</label>
                            <select id="experience_level" name="experience_level">
                                <option value="">Sélectionner</option>
                                <?php foreach (['0-1', '1-3', '3-5', '5-10', '10+'] as $level): ?>
                                    <option value="<?= e($level) ?>" <?= old('experience_level') === $level ? 'selected' : '' ?>><?= e($level) ?> ans</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="skills">Compétences</label>
                            <input type="text" id="skills" name="skills" value="<?= e(old('skills')) ?>">
                        </div>
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio"><?= e(old('bio')) ?></textarea>
                        </div>
                    </div>

                    <div class="form-panel">
                        <h3>Entreprise</h3>
                        <div class="form-group">
                            <label for="company_name">Nom de l'entreprise</label>
                            <input type="text" id="company_name" name="company_name" value="<?= e(old('company_name')) ?>">
                        </div>
                        <div class="form-group">
                            <label for="industry">Secteur</label>
                            <input type="text" id="industry" name="industry" value="<?= e(old('industry')) ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_size">Taille</label>
                            <select id="company_size" name="company_size">
                                <option value="">Sélectionner</option>
                                <?php foreach (['1-10', '11-50', '51-200', '201-500', '500+'] as $size): ?>
                                    <option value="<?= e($size) ?>" <?= old('company_size') === $size ? 'selected' : '' ?>><?= e($size) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="website_url">Site web</label>
                            <input type="url" id="website_url" name="website_url" value="<?= e(old('website_url')) ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_description">Description entreprise</label>
                            <textarea id="company_description" name="company_description"><?= e(old('company_description')) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Créer mon compte</button>
                </div>
                <p class="auth-switch">Déjà inscrit ? <a href="/views/login.php">Se connecter</a></p>
            </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
