<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$user = require_auth('candidate');
$profile = get_candidate_profile((int) $user['id']);

if (is_post()) {
    verify_csrf($_POST['_csrf'] ?? null);

    $data = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'birth_date' => trim($_POST['birth_date'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'country' => trim($_POST['country'] ?? ''),
        'headline' => trim($_POST['headline'] ?? ''),
        'experience_level' => trim($_POST['experience_level'] ?? ''),
        'skills' => trim($_POST['skills'] ?? ''),
        'bio' => trim($_POST['bio'] ?? ''),
        'linkedin_url' => trim($_POST['linkedin_url'] ?? ''),
        'github_url' => trim($_POST['github_url'] ?? ''),
        'portfolio_url' => trim($_POST['portfolio_url'] ?? ''),
        'alerts_enabled' => $_POST['alerts_enabled'] ?? '',
        'visibility' => $_POST['visibility'] ?? '',
        'newsletter_enabled' => $_POST['newsletter_enabled'] ?? '',
    ];

    try {
        $data['cv_filename'] = upload_file($_FILES['cv_file'] ?? [], 'cv', ['pdf', 'doc', 'docx'], 10 * 1024 * 1024);
    } catch (RuntimeException $e) {
        flash('error', $e->getMessage());
        redirect('/views/profil-candidat.php');
    }

    update_candidate_profile((int) $user['id'], $data);
    flash('success', 'Profil candidat mis à jour.');
    redirect('/views/profil-candidat.php');
}

$profile = get_candidate_profile((int) $user['id']);
$skills = array_values(array_filter(array_map('trim', explode(',', (string) ($profile['skills'] ?? '')))));
$profileSignals = [
    $profile['headline'] ?? '',
    $profile['bio'] ?? '',
    $profile['skills'] ?? '',
    $profile['city'] ?? '',
    $profile['linkedin_url'] ?? '',
    $profile['github_url'] ?? '',
    $profile['portfolio_url'] ?? '',
    $profile['cv_filename'] ?? '',
];
$profileCompletion = (int) round((count(array_filter($profileSignals)) / count($profileSignals)) * 100);

$pageTitle = 'Profil Candidat - JobHub';
$assetBase = '..';
$rootPath = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<section class="page-header page-header-rich">
    <div class="container">
        <div class="page-header-inner">
            <span class="page-eyebrow">Profil candidat</span>
            <h1>Mon profil candidat</h1>
            <p>Un espace mieux guidé pour présenter clairement ton identité, ton positionnement, tes preuves et ta visibilité.</p>
            <div class="page-header-pills">
                <span><?= $profileCompletion ?>% complété</span>
                <span><?= !empty($profile['cv_filename']) ? 'CV disponible' : 'CV à ajouter' ?></span>
                <span><?= e($profile['city'] ?: 'Ville à compléter') ?></span>
            </div>
        </div>
    </div>
</section>

<section class="inscription-section">
    <div class="container">
        <div class="editor-shell">
            <aside class="editor-sidebar">
                <article class="editor-panel editor-panel-dark">
                    <span class="panel-label">Résumé candidat</span>
                    <h2><?= e(trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''))) ?></h2>
                    <p><?= e($profile['headline'] ?: 'Ajoute un titre professionnel clair pour inspirer confiance dès la première lecture.') ?></p>
                    <progress class="progress-meter" max="100" value="<?= $profileCompletion ?>"><?= $profileCompletion ?>%</progress>
                </article>

                <article class="editor-panel">
                    <span class="panel-label">Points clés</span>
                    <ul class="check-list">
                        <li><?= !empty($profile['cv_filename']) ? 'CV téléversé et partageable.' : 'Téléverser un CV professionnel.' ?></li>
                        <li><?= !empty($profile['bio']) ? 'Bio renseignée.' : 'Rédiger une bio plus convaincante.' ?></li>
                        <li><?= !empty($skills) ? 'Compétences listées.' : 'Ajouter des compétences concrètes.' ?></li>
                        <li><?= (int) ($profile['visibility'] ?? 0) === 1 ? 'Profil visible.' : 'Activer la visibilité du profil.' ?></li>
                    </ul>
                </article>

                <?php if ($skills): ?>
                    <article class="editor-panel">
                        <span class="panel-label">Compétences visibles</span>
                        <div class="skill-cloud">
                            <?php foreach ($skills as $skill): ?>
                                <span><?= e($skill) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </article>
                <?php endif; ?>
            </aside>

            <div class="editor-main">
                <form method="post" enctype="multipart/form-data" class="inscription-form form-workspace">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Identité</h2>
                            <p>Les éléments de base affichés dans l’espace et les candidatures.</p>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="first_name" required value="<?= e($profile['first_name']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="last_name" required value="<?= e($profile['last_name']) ?>">
                            </div>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" required value="<?= e($profile['email']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" name="phone" value="<?= e($profile['phone']) ?>">
                            </div>
                        </div>
                        <div class="form-section-grid form-section-grid-3">
                            <div class="form-group">
                                <label>Date de naissance</label>
                                <input type="date" name="birth_date" value="<?= e($profile['birth_date']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Ville</label>
                                <input type="text" name="city" value="<?= e($profile['city']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Pays</label>
                                <input type="text" name="country" value="<?= e($profile['country']) ?>">
                            </div>
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Positionnement professionnel</h2>
                            <p>Ce qui aide les recruteurs à te comprendre rapidement.</p>
                        </div>
                        <div class="form-group">
                            <label>Titre professionnel</label>
                            <input type="text" name="headline" value="<?= e($profile['headline']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Expérience</label>
                            <select name="experience_level">
                                <option value="">Sélectionner</option>
                                <?php foreach (['0-1', '1-3', '3-5', '5-10', '10+'] as $level): ?>
                                    <option value="<?= e($level) ?>" <?= $profile['experience_level'] === $level ? 'selected' : '' ?>><?= e($level) ?> ans</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Compétences</label>
                            <input type="text" name="skills" value="<?= e($profile['skills']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Bio</label>
                            <textarea name="bio" rows="6"><?= e($profile['bio']) ?></textarea>
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Preuves et liens</h2>
                            <p>Ajoute des éléments qui renforcent la confiance et la conversion.</p>
                        </div>
                        <div class="form-group">
                            <label>CV</label>
                            <input type="file" name="cv_file" accept=".pdf,.doc,.docx">
                            <?php if (!empty($profile['cv_filename'])): ?>
                                <p class="form-note">CV actuel : <a href="<?= e($profile['cv_filename']) ?>" target="_blank" rel="noreferrer">ouvrir le fichier</a></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>LinkedIn</label>
                                <input type="url" name="linkedin_url" value="<?= e($profile['linkedin_url']) ?>">
                            </div>
                            <div class="form-group">
                                <label>GitHub</label>
                                <input type="url" name="github_url" value="<?= e($profile['github_url']) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Portfolio</label>
                            <input type="url" name="portfolio_url" value="<?= e($profile['portfolio_url']) ?>">
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Préférences</h2>
                            <p>Règles de visibilité et d’alertes pour ton espace candidat.</p>
                        </div>
                        <div class="check-stack">
                            <label><input type="checkbox" name="alerts_enabled" value="1" <?= (int) $profile['alerts_enabled'] === 1 ? 'checked' : '' ?>> Recevoir les alertes emploi</label>
                            <label><input type="checkbox" name="visibility" value="1" <?= (int) $profile['visibility'] === 1 ? 'checked' : '' ?>> Rendre le profil visible</label>
                            <label><input type="checkbox" name="newsletter_enabled" value="1" <?= (int) $profile['newsletter_enabled'] === 1 ? 'checked' : '' ?>> Recevoir la newsletter</label>
                        </div>
                    </section>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
