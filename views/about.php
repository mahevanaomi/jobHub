<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$pageTitle = 'À propos - JobHub';
$assetBase = '..';
$rootPath = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<section class="page-header page-header-rich">
    <div class="container">
        <div class="page-header-inner">
            <span class="page-eyebrow">Produit PHP refondu</span>
            <h1>À propos de JobHub</h1>
            <p>Une base PHP native plus propre, plus sérieuse et pensée pour de vrais flux de recrutement.</p>
            <div class="page-header-pills">
                <span>Backend PHP connecté</span>
                <span>MySQL persistant</span>
                <span>Parcours candidat et entreprise</span>
            </div>
        </div>
    </div>
</section>

<section class="inscription-section">
    <div class="container">
        <div class="split-layout">
            <div class="content-card prose-block">
                <h2>Pourquoi cette refonte</h2>
                <p>Le projet initial était très visuel mais essentiellement statique. Il s'appuie désormais sur une architecture PHP commune, une base de données cohérente et des pages métier réellement branchées.</p>
                <h2>Ce que le backend gère maintenant</h2>
                <ul>
                    <li>création de comptes candidat et entreprise</li>
                    <li>authentification et sessions</li>
                    <li>gestion des profils</li>
                    <li>publication et édition d'offres</li>
                    <li>candidatures liées aux offres</li>
                    <li>dashboards candidat et recruteur</li>
                    <li>messages de contact persistés en base</li>
                </ul>
                <h2>Ce que cette base permet ensuite</h2>
                <p>Ajouter des pièces jointes, des notifications email, une messagerie recruteur-candidat, un panneau admin ou une API REST sans devoir tout casser.</p>
            </div>
            <div class="content-card">
                <div class="section-head">
                    <h2>Ce que JobHub délivre</h2>
                </div>
                <div class="mini-feature-grid">
                    <article class="mini-feature-card">
                        <strong>Recherche fiable</strong>
                        <p>Filtres PHP côté serveur sur le mot-clé, la ville, la catégorie et le type de contrat.</p>
                    </article>
                    <article class="mini-feature-card">
                        <strong>Workflow recruteur</strong>
                        <p>Publication d'offres, suivi des candidatures et mise à jour des statuts depuis le dashboard.</p>
                    </article>
                    <article class="mini-feature-card">
                        <strong>Base évolutive</strong>
                        <p>Le socle permet d'ajouter ensuite mails, admin, reporting ou API sans repartir de zéro.</p>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
