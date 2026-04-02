<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

if (is_post()) {
    verify_csrf($_POST['_csrf'] ?? null);

    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'subject' => trim($_POST['subject'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
    ];

    if (!$data['name'] || !$data['email'] || !$data['subject'] || !$data['message']) {
        flash('error', 'Tous les champs obligatoires doivent être renseignés.');
        redirect('/views/contact.php');
    }

    store_contact_message($data);
    flash('success', 'Message envoyé et enregistré avec succès.');
    redirect('/views/contact.php');
}

$pageTitle = 'Contact - JobHub';
$assetBase = '..';
$rootPath = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<section class="page-header page-header-rich">
    <div class="container">
        <div class="page-header-inner">
            <span class="page-eyebrow">Contact centralisé</span>
            <h1>Contact</h1>
            <p>Le formulaire de contact fonctionne maintenant côté serveur et écrit en base.</p>
            <div class="page-header-pills">
                <span>Messages persistés</span>
                <span>Formulaire CSRF protégé</span>
                <span>Prêt pour back-office</span>
            </div>
        </div>
    </div>
</section>

<section class="inscription-section">
    <div class="container">
        <div class="split-layout">
            <div class="content-card prose-block">
                <h2>Un canal propre pour les demandes</h2>
                <p>Tu peux centraliser ici les demandes candidats, les partenariats, les sujets techniques et les prises de contact entreprise.</p>
                <p>Chaque message est stocké dans la table `contact_messages`, ce qui permet ensuite de brancher un back-office ou un export.</p>
                <div class="contact-points">
                    <div class="contact-point">
                        <strong>Support produit</strong>
                        <p>Pour les sujets de parcours candidat, publication d'offres ou problèmes de dashboard.</p>
                    </div>
                    <div class="contact-point">
                        <strong>Partenariats</strong>
                        <p>Pour les demandes B2B, sponsoring, intégration RH ou collaboration entreprise.</p>
                    </div>
                    <div class="contact-point">
                        <strong>Suivi technique</strong>
                        <p>Chaque message est récupérable en base pour mettre ensuite un vrai traitement d'équipe.</p>
                    </div>
                </div>
            </div>
            <div class="content-card">
                <form method="post" class="inscription-form form-card-flat">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="phone">
                    </div>
                    <div class="form-group">
                        <label>Sujet</label>
                        <select name="subject" required>
                            <option value="">Sélectionner</option>
                            <option value="Question candidat">Question candidat</option>
                            <option value="Question entreprise">Question entreprise</option>
                            <option value="Support technique">Support technique</option>
                            <option value="Partenariat">Partenariat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="6" required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
