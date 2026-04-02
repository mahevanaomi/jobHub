<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$jobId = (int) ($_GET['id'] ?? 0);
$job = $jobId ? get_job_by_id($jobId) : null;

if (!$job) {
    flash('error', 'Offre introuvable.');
    redirect('/index.php');
}

$user = current_user();

if (is_post()) {
    require_auth('candidate');
    verify_csrf($_POST['_csrf'] ?? null);

    if (has_applied($jobId, (int) $user['id'])) {
        flash('error', 'Tu as déjà postulé à cette offre.');
        redirect('/views/job-details.php?id=' . $jobId);
    }

    create_application($jobId, (int) $user['id'], trim($_POST['cover_letter'] ?? ''));
    flash('success', 'Candidature envoyée avec succès.');
    redirect('/views/dashboard-candidat.php');
}

$pageTitle = $job['title'] . ' - JobHub';
$assetBase = '..';
$rootPath = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';

$tags = array_filter(array_map('trim', explode(',', (string) $job['tags'])));
$lines = static function (?string $text): array {
    return array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $text) ?: []));
};
?>

<section class="page-header page-header-rich">
    <div class="container">
        <div class="page-header-inner">
            <span class="page-eyebrow">Détail d'offre</span>
            <h1><?= e($job['title']) ?></h1>
            <p><?= e($job['company_name']) ?> · <?= e($job['city']) ?>, <?= e($job['country']) ?></p>
            <div class="page-header-pills">
                <span><?= e($job['contract_type']) ?></span>
                <span><?= (int) $job['remote_allowed'] === 1 ? 'Télétravail possible' : 'Présentiel' ?></span>
                <span>Expire le <?= e(format_datetime($job['expires_at'])) ?></span>
            </div>
        </div>
    </div>
</section>

<section class="job-details-section">
    <div class="container">
        <div class="job-detail-container">
            <div class="job-detail-card">
                <div class="job-section">
                    <h2>Vue d'ensemble</h2>
                    <div class="detail-grid">
                        <div><strong>Contrat</strong><span><?= e($job['contract_type']) ?></span></div>
                        <div><strong>Catégorie</strong><span><?= e($job['category_name'] ?: 'Général') ?></span></div>
                        <div><strong>Salaire min.</strong><span><?= e(format_money((float) $job['salary_min'], $job['salary_period'])) ?></span></div>
                        <div><strong>Salaire max.</strong><span><?= e(format_money((float) $job['salary_max'], $job['salary_period'])) ?></span></div>
                        <div><strong>Télétravail</strong><span><?= (int) $job['remote_allowed'] === 1 ? 'Oui' : 'Non' ?></span></div>
                        <div><strong>Expire le</strong><span><?= e(format_datetime($job['expires_at'])) ?></span></div>
                    </div>
                </div>

                <div class="job-section">
                    <h2>Description</h2>
                    <p><?= nl2br(e($job['description'])) ?></p>
                </div>

                <div class="job-section">
                    <h2>Responsabilités</h2>
                    <ul>
                        <?php foreach ($lines($job['responsibilities']) as $line): ?>
                            <li><?= e($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="job-section">
                    <h2>Compétences requises</h2>
                    <ul>
                        <?php foreach ($lines($job['requirements']) as $line): ?>
                            <li><?= e($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php if ($job['bonus_skills']): ?>
                    <div class="job-section">
                        <h2>Compétences bonus</h2>
                        <p><?= nl2br(e($job['bonus_skills'])) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($job['benefits']): ?>
                    <div class="job-section">
                        <h2>Avantages</h2>
                        <p><?= nl2br(e($job['benefits'])) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($tags): ?>
                    <div class="job-section">
                        <h2>Tags</h2>
                        <div class="job-tags">
                            <?php foreach ($tags as $tag): ?>
                                <span><?= e($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="apply-section apply-surface">
                    <h3>Postuler à cette offre</h3>
                    <?php if (!$user): ?>
                        <p>Connecte-toi comme candidat pour envoyer ta candidature.</p>
                        <a href="/views/login.php" class="btn btn-primary">Se connecter</a>
                    <?php elseif ($user['role'] !== 'candidate'): ?>
                        <p>Seuls les comptes candidats peuvent postuler.</p>
                    <?php elseif (has_applied($jobId, (int) $user['id'])): ?>
                        <p>Tu as déjà postulé à cette offre.</p>
                    <?php else: ?>
                        <form method="post" class="inscription-form form-card-flat apply-form">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <div class="form-group">
                                <label for="cover_letter">Message d'accompagnement</label>
                                <textarea id="cover_letter" name="cover_letter" rows="5" placeholder="Présente brièvement ta motivation."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Envoyer ma candidature</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <aside class="job-side-card">
                <h3>Entreprise</h3>
                <p><strong><?= e($job['company_name']) ?></strong></p>
                <p><?= e($job['industry'] ?: 'Secteur non précisé') ?></p>
                <p><?= e($job['company_size'] ?: 'Taille non précisée') ?></p>
                <?php if ($job['company_description']): ?>
                    <p><?= nl2br(e($job['company_description'])) ?></p>
                <?php endif; ?>
                <?php if ($job['website_url']): ?>
                    <a class="btn btn-outline" href="<?= e($job['website_url']) ?>" target="_blank" rel="noreferrer">Visiter le site</a>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
