<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$user = require_auth('company');
$categories = get_categories();
$jobId = (int) ($_GET['id'] ?? 0);
$job = $jobId ? get_job_for_company($jobId, (int) $user['id']) : null;

if ($jobId && !$job) {
    flash('error', 'Offre introuvable.');
    redirect('/views/dashboard-entreprise.php');
}

if (is_post()) {
    verify_csrf($_POST['_csrf'] ?? null);

    $data = [
        'category_id' => (int) ($_POST['category_id'] ?? 0),
        'title' => trim($_POST['title'] ?? ''),
        'contract_type' => trim($_POST['contract_type'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'country' => trim($_POST['country'] ?? ''),
        'remote_allowed' => $_POST['remote_allowed'] ?? '',
        'salary_min' => trim($_POST['salary_min'] ?? ''),
        'salary_max' => trim($_POST['salary_max'] ?? ''),
        'salary_period' => trim($_POST['salary_period'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'responsibilities' => trim($_POST['responsibilities'] ?? ''),
        'requirements' => trim($_POST['requirements'] ?? ''),
        'bonus_skills' => trim($_POST['bonus_skills'] ?? ''),
        'benefits' => trim($_POST['benefits'] ?? ''),
        'experience_level' => trim($_POST['experience_level'] ?? ''),
        'education_level' => trim($_POST['education_level'] ?? ''),
        'tags' => trim($_POST['tags'] ?? ''),
        'expires_at' => trim($_POST['expires_at'] ?? ''),
    ];

    if (!$data['category_id'] || !$data['title'] || !$data['contract_type'] || !$data['city'] || !$data['country'] || !$data['description']) {
        flash('error', 'Merci de remplir tous les champs clés de l\'offre.');
        redirect($jobId ? '/views/poster-offre.php?id=' . $jobId : '/views/poster-offre.php');
    }

    if ($jobId) {
        update_job($jobId, (int) $user['id'], $data);
        flash('success', 'Offre mise à jour.');
    } else {
        $jobId = create_job((int) $user['id'], $data);
        flash('success', 'Offre publiée avec succès.');
    }

    redirect('/views/poster-offre.php?id=' . $jobId);
}

$job = $jobId ? get_job_for_company($jobId, (int) $user['id']) : null;
$pageTitle = $job ? 'Modifier une offre - JobHub' : 'Publier une offre - JobHub';
$assetBase = '..';
$rootPath = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<section class="page-header page-header-rich">
    <div class="container">
        <div class="page-header-inner">
            <span class="page-eyebrow">Studio d'offre</span>
            <h1><?= $job ? 'Modifier une offre' : 'Publier une offre' ?></h1>
            <p>Un formulaire métier plus guidé, plus clair et relié à la base pour créer ou mettre à jour une annonce sans friction.</p>
            <div class="page-header-pills">
                <span><?= $job ? 'Mode édition' : 'Mode création' ?></span>
                <span>Backend PHP natif</span>
                <span>Persistance immédiate</span>
            </div>
        </div>
    </div>
</section>

<section class="inscription-section">
    <div class="container">
        <div class="editor-shell">
            <aside class="editor-sidebar">
                <article class="editor-panel editor-panel-dark">
                    <span class="panel-label">Vue produit</span>
                    <h2><?= e($job['title'] ?? 'Nouvelle annonce') ?></h2>
                    <p>Renseigne les éléments qui permettent aux candidats de comprendre très vite le poste, le contexte et la valeur de ton offre.</p>
                    <div class="dashboard-pill-row">
                        <span><?= e($job['contract_type'] ?? 'Type à définir') ?></span>
                        <span><?= e($job['city'] ?? 'Ville à définir') ?></span>
                    </div>
                </article>

                <article class="editor-panel">
                    <span class="panel-label">Checklist de qualité</span>
                    <ul class="check-list">
                        <li>Un titre explicite et compréhensible</li>
                        <li>Une description orientée impact et contexte</li>
                        <li>Des responsabilités lisibles sous forme de points</li>
                        <li>Des compétences et avantages qui aident à décider</li>
                    </ul>
                </article>

                <article class="editor-panel">
                    <span class="panel-label">Raccourcis</span>
                    <div class="quick-link-stack">
                        <a href="/views/dashboard-entreprise.php" class="quick-link-card">
                            <strong>Retour au dashboard</strong>
                            <span>Suivre les candidatures et les offres</span>
                        </a>
                        <a href="/views/profil-entreprise.php" class="quick-link-card">
                            <strong>Renforcer le profil entreprise</strong>
                            <span>Logo, description, site et contact</span>
                        </a>
                    </div>
                </article>
            </aside>

            <div class="editor-main">
                <form method="post" class="inscription-form form-workspace">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Positionnement du poste</h2>
                            <p>Les informations que les candidats lisent en premier.</p>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Catégorie</label>
                                <select name="category_id" required>
                                    <option value="">Sélectionner</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= (int) $category['id'] ?>" <?= (int) ($job['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Type de contrat</label>
                                <select name="contract_type" required>
                                    <option value="">Sélectionner</option>
                                    <?php foreach (['CDI', 'CDD', 'Stage', 'Freelance', 'Alternance'] as $contract): ?>
                                        <option value="<?= e($contract) ?>" <?= ($job['contract_type'] ?? '') === $contract ? 'selected' : '' ?>><?= e($contract) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Titre du poste</label>
                            <input type="text" name="title" required value="<?= e($job['title'] ?? '') ?>">
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Localisation et mode de travail</h2>
                            <p>Permets au candidat de se projeter sans ambiguïté.</p>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Ville</label>
                                <input type="text" name="city" required value="<?= e($job['city'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>Pays</label>
                                <input type="text" name="country" required value="<?= e($job['country'] ?? 'Cameroun') ?>">
                            </div>
                        </div>
                        <div class="check-stack">
                            <label><input type="checkbox" name="remote_allowed" value="1" <?= (int) ($job['remote_allowed'] ?? 0) === 1 ? 'checked' : '' ?>> Télétravail possible</label>
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Rémunération et temporalité</h2>
                            <p>Donne un cadre concret et professionnel à l’annonce.</p>
                        </div>
                        <div class="form-section-grid form-section-grid-3">
                            <div class="form-group">
                                <label>Salaire minimum</label>
                                <input type="number" name="salary_min" value="<?= e((string) ($job['salary_min'] ?? '')) ?>">
                            </div>
                            <div class="form-group">
                                <label>Salaire maximum</label>
                                <input type="number" name="salary_max" value="<?= e((string) ($job['salary_max'] ?? '')) ?>">
                            </div>
                            <div class="form-group">
                                <label>Période salariale</label>
                                <input type="text" name="salary_period" value="<?= e($job['salary_period'] ?? 'mois') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Date d'expiration</label>
                            <input type="date" name="expires_at" value="<?= e($job['expires_at'] ?? '') ?>">
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Contenu de l’offre</h2>
                            <p>La zone la plus importante pour la compréhension et la conversion.</p>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="6" required><?= e($job['description'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Responsabilités</label>
                            <textarea name="responsibilities" rows="5"><?= e($job['responsibilities'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Compétences requises</label>
                            <textarea name="requirements" rows="5"><?= e($job['requirements'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Compétences bonus</label>
                            <textarea name="bonus_skills" rows="4"><?= e($job['bonus_skills'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Avantages</label>
                            <textarea name="benefits" rows="4"><?= e($job['benefits'] ?? '') ?></textarea>
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Qualificatifs complémentaires</h2>
                            <p>Affinage du poste pour le bon niveau de candidats.</p>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Expérience attendue</label>
                                <input type="text" name="experience_level" value="<?= e($job['experience_level'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>Niveau d'étude</label>
                                <input type="text" name="education_level" value="<?= e($job['education_level'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tags</label>
                            <input type="text" name="tags" value="<?= e($job['tags'] ?? '') ?>">
                        </div>
                    </section>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><?= $job ? 'Mettre à jour l\'offre' : 'Publier l\'offre' ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
