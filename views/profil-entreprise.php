<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$user = require_auth('company');
$profile = get_company_profile((int) $user['id']);

if (is_post()) {
    verify_csrf($_POST['_csrf'] ?? null);

    $data = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'country' => trim($_POST['country'] ?? ''),
        'company_name' => trim($_POST['company_name'] ?? ''),
        'industry' => trim($_POST['industry'] ?? ''),
        'company_size' => trim($_POST['company_size'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'website_url' => trim($_POST['website_url'] ?? ''),
        'founded_year' => trim($_POST['founded_year'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'postal_code' => trim($_POST['postal_code'] ?? ''),
        'contact_name' => trim($_POST['contact_name'] ?? ''),
        'contact_role' => trim($_POST['contact_role'] ?? ''),
        'contact_email' => trim($_POST['contact_email'] ?? ''),
        'contact_phone' => trim($_POST['contact_phone'] ?? ''),
        'linkedin_url' => trim($_POST['linkedin_url'] ?? ''),
        'twitter_url' => trim($_POST['twitter_url'] ?? ''),
        'facebook_url' => trim($_POST['facebook_url'] ?? ''),
        'public_profile' => $_POST['public_profile'] ?? '',
        'application_notifications' => $_POST['application_notifications'] ?? '',
        'newsletter_enabled' => $_POST['newsletter_enabled'] ?? '',
    ];

    try {
        $data['avatar'] = upload_file($_FILES['logo_file'] ?? [], 'logos', ['png', 'jpg', 'jpeg', 'webp', 'svg'], 5 * 1024 * 1024);
    } catch (RuntimeException $e) {
        flash('error', $e->getMessage());
        redirect('/views/profil-entreprise.php');
    }

    update_company_profile((int) $user['id'], $data);
    flash('success', 'Profil entreprise mis à jour.');
    redirect('/views/profil-entreprise.php');
}

$profile = get_company_profile((int) $user['id']);
$profileSignals = [
    $profile['company_name'] ?? '',
    $profile['industry'] ?? '',
    $profile['description'] ?? '',
    $profile['website_url'] ?? '',
    $profile['city'] ?? '',
    $profile['contact_email'] ?? '',
    $profile['avatar'] ?? '',
];
$profileCompletion = (int) round((count(array_filter($profileSignals)) / count($profileSignals)) * 100);

$pageTitle = 'Profil Entreprise - JobHub';
$assetBase = '..';
$rootPath = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<section class="page-header page-header-rich">
    <div class="container">
        <div class="page-header-inner">
            <span class="page-eyebrow">Profil entreprise</span>
            <h1>Profil entreprise</h1>
            <p>Présente une marque employeur plus nette, plus rassurante et mieux structurée pour convertir de meilleurs candidats.</p>
            <div class="page-header-pills">
                <span><?= $profileCompletion ?>% complété</span>
                <span><?= !empty($profile['avatar']) ? 'Logo en place' : 'Logo à ajouter' ?></span>
                <span><?= e($profile['industry'] ?: 'Secteur à compléter') ?></span>
            </div>
        </div>
    </div>
</section>

<section class="inscription-section">
    <div class="container">
        <div class="editor-shell">
            <aside class="editor-sidebar">
                <article class="editor-panel editor-panel-dark">
                    <span class="panel-label">Marque employeur</span>
                    <h2><?= e($profile['company_name'] ?: 'Entreprise à nommer') ?></h2>
                    <p><?= e($profile['description'] ?: 'Ajoute une description synthétique pour expliquer qui vous êtes, ce que vous faites et pourquoi rejoindre l’équipe.') ?></p>
                    <progress class="progress-meter" max="100" value="<?= $profileCompletion ?>"><?= $profileCompletion ?>%</progress>
                </article>

                <?php if (!empty($profile['avatar'])): ?>
                    <article class="editor-panel">
                        <div class="logo-preview">
                            <img src="<?= e($profile['avatar']) ?>" alt="Logo entreprise">
                        </div>
                    </article>
                <?php endif; ?>

                <article class="editor-panel">
                    <span class="panel-label">Checklist</span>
                    <ul class="check-list">
                        <li><?= !empty($profile['avatar']) ? 'Logo visible et exploitable.' : 'Ajouter un logo propre et lisible.' ?></li>
                        <li><?= !empty($profile['website_url']) ? 'Site web renseigné.' : 'Ajouter le site pour renforcer la confiance.' ?></li>
                        <li><?= !empty($profile['description']) ? 'Description entreprise présente.' : 'Rédiger une description plus claire.' ?></li>
                        <li><?= !empty($profile['contact_email']) ? 'Email de contact renseigné.' : 'Ajouter une adresse de contact dédiée.' ?></li>
                    </ul>
                </article>
            </aside>

            <div class="editor-main">
                <form method="post" enctype="multipart/form-data" class="inscription-form form-workspace">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Compte et identité</h2>
                            <p>Informations de base du compte et du point de contact principal.</p>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Prénom du compte</label>
                                <input type="text" name="first_name" value="<?= e($profile['first_name']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Nom du compte</label>
                                <input type="text" name="last_name" value="<?= e($profile['last_name']) ?>">
                            </div>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="<?= e($profile['email']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" name="phone" value="<?= e($profile['phone']) ?>">
                            </div>
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Entreprise</h2>
                            <p>Les éléments que les candidats regardent pour juger votre sérieux.</p>
                        </div>
                        <div class="form-group">
                            <label>Nom de l'entreprise</label>
                            <input type="text" name="company_name" required value="<?= e($profile['company_name']) ?>">
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Secteur</label>
                                <input type="text" name="industry" value="<?= e($profile['industry']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Taille</label>
                                <input type="text" name="company_size" value="<?= e($profile['company_size']) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="6"><?= e($profile['description']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Logo</label>
                            <input type="file" name="logo_file" accept=".png,.jpg,.jpeg,.webp,.svg">
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Coordonnées et présence en ligne</h2>
                            <p>Canaux de confiance et moyens de vérification.</p>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Site web</label>
                                <input type="url" name="website_url" value="<?= e($profile['website_url']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Année de création</label>
                                <input type="number" name="founded_year" value="<?= e((string) $profile['founded_year']) ?>">
                            </div>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Ville</label>
                                <input type="text" name="city" value="<?= e($profile['city']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Pays</label>
                                <input type="text" name="country" value="<?= e($profile['country']) ?>">
                            </div>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Adresse</label>
                                <input type="text" name="address" value="<?= e($profile['address']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Code postal</label>
                                <input type="text" name="postal_code" value="<?= e($profile['postal_code']) ?>">
                            </div>
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Contact recrutement</h2>
                            <p>Le point d’entrée opérationnel pour les candidats et prospects.</p>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Nom du contact</label>
                                <input type="text" name="contact_name" value="<?= e($profile['contact_name']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Rôle du contact</label>
                                <input type="text" name="contact_role" value="<?= e($profile['contact_role']) ?>">
                            </div>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>Email du contact</label>
                                <input type="email" name="contact_email" value="<?= e($profile['contact_email']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Téléphone du contact</label>
                                <input type="text" name="contact_phone" value="<?= e($profile['contact_phone']) ?>">
                            </div>
                        </div>
                    </section>

                    <section class="form-section-card">
                        <div class="form-section-head">
                            <h2>Réseaux et préférences</h2>
                            <p>Visibilité publique et pilotage des notifications.</p>
                        </div>
                        <div class="form-section-grid form-section-grid-2">
                            <div class="form-group">
                                <label>LinkedIn</label>
                                <input type="url" name="linkedin_url" value="<?= e($profile['linkedin_url']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Twitter</label>
                                <input type="url" name="twitter_url" value="<?= e($profile['twitter_url']) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Facebook</label>
                            <input type="url" name="facebook_url" value="<?= e($profile['facebook_url']) ?>">
                        </div>
                        <div class="check-stack">
                            <label><input type="checkbox" name="public_profile" value="1" <?= (int) $profile['public_profile'] === 1 ? 'checked' : '' ?>> Profil public</label>
                            <label><input type="checkbox" name="application_notifications" value="1" <?= (int) $profile['application_notifications'] === 1 ? 'checked' : '' ?>> Notifications candidatures</label>
                            <label><input type="checkbox" name="newsletter_enabled" value="1" <?= (int) $profile['newsletter_enabled'] === 1 ? 'checked' : '' ?>> Newsletter</label>
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
