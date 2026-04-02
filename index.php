<?php

declare(strict_types=1);

require_once __DIR__ . '/app/bootstrap.php';

$filters = [
    'search'        => trim($_GET['search'] ?? ''),
    'city'          => trim($_GET['city'] ?? ''),
    'category'      => trim($_GET['category'] ?? ''),
    'contract_type' => trim($_GET['contract_type'] ?? ''),
];

$categories = get_categories();
$jobs       = get_featured_jobs($filters);

$pageTitle  = 'JobHub — Plateforme de recrutement au Cameroun';
$assetBase  = '';
$rootPath   = '/index.php';
$viewsPath  = '/views';

require __DIR__ . '/app/views/header.php';
?>

<style>
/* ── Homepage-specific styles ───────────────────────────────────────── */

/* Hero panel glassmorphism refinement */
.hero-panel .panel-card {
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
}

/* Trust strip overlapping hero */
.trust-strip {
    margin-top: -2rem;
    position: relative;
    z-index: 10;
}

/* ── How It Works ───────────────────────────────────────────────────── */
.how-it-works {
    padding: 6rem 0;
    background: #f8faff;
}

.how-it-works .section-title,
.how-it-works .section-subtitle {
    text-align: center;
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
    position: relative;
}

/* Connector line between steps on wide screens */
@media (min-width: 768px) {
    .steps-grid::before {
        content: '';
        position: absolute;
        top: 2.5rem;
        left: calc(50% / 3 + 2.5rem);
        right: calc(50% / 3 + 2.5rem);
        height: 2px;
        background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #6366f1 100%);
        opacity: .25;
        z-index: 0;
    }
}

.step-card {
    background: #fff;
    border: 1px solid #e8eaf0;
    border-radius: 1.25rem;
    padding: 2.25rem 1.75rem;
    text-align: center;
    position: relative;
    z-index: 1;
    transition: transform .2s ease, box-shadow .2s ease;
}

.step-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(99, 102, 241, .12);
}

.step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 3.25rem;
    height: 3.25rem;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
    box-shadow: 0 4px 16px rgba(99, 102, 241, .35);
}

.step-icon {
    font-size: 1.1rem;
}

.step-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: .5rem;
}

.step-card p {
    font-size: .92rem;
    color: #6b7280;
    line-height: 1.6;
    margin: 0;
}

/* ── Category card count badge ──────────────────────────────────────── */
.category-count {
    display: inline-block;
    margin-top: .5rem;
    font-size: .78rem;
    font-weight: 600;
    color: #6366f1;
    background: rgba(99, 102, 241, .08);
    padding: .2rem .65rem;
    border-radius: 99px;
}

/* ── Job card salary highlight ──────────────────────────────────────── */
.job-salary {
    font-weight: 600;
    color: #059669;
}

/* ── Section title accent underline ────────────────────────────────── */
.section-title-accent {
    display: inline-block;
    position: relative;
}

.section-title-accent::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 0;
    width: 48px;
    height: 3px;
    border-radius: 99px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6);
}

/* ── Job card hidden (voir plus) ────────────────────────────────────── */
.job-card-hidden {
    display: none !important;
}

/* ── Empty state icon ───────────────────────────────────────────────── */
.empty-state {
    text-align: center;
    padding: 4rem 1rem;
}

.empty-state-icon {
    font-size: 3rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

/* ── Filter bar active pill ─────────────────────────────────────────── */
.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin-bottom: 1.25rem;
}

.active-filter-pill {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: rgba(99, 102, 241, .08);
    border: 1px solid rgba(99, 102, 241, .25);
    color: #4f46e5;
    font-size: .8rem;
    font-weight: 600;
    padding: .3rem .75rem;
    border-radius: 99px;
}

/* ── CTA gradient overlay ────────────────────────────────────────────── */
.cta-section {
    overflow: hidden;
    position: relative;
}

.cta-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 80% 80% at 50% 120%, rgba(139, 92, 246, .35) 0%, transparent 70%);
    pointer-events: none;
}

/* ── Responsive tweaks ──────────────────────────────────────────────── */
@media (max-width: 640px) {
    .steps-grid {
        grid-template-columns: 1fr;
    }
    .active-filters {
        margin-top: .75rem;
    }
}
</style>

<!-- ════════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════ -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-shell">

            <!-- Left: copy -->
            <div class="hero-copy">
                <span class="hero-kicker">
                    <i class="fas fa-star" style="color:#fbbf24;font-size:.8rem;"></i>
                    La plateforme&nbsp;#1 de recrutement au Cameroun
                </span>

                <h1 class="hero-title">
                    Trouvez le talent parfait<br>ou le poste idéal
                </h1>

                <p class="hero-subtitle">
                    Des milliers d'offres vérifiées, des recruteurs actifs et un parcours fluide — pour candidats ambitieux et entreprises sérieuses.
                </p>

                <div class="hero-actions">
                    <a href="#jobs" class="btn btn-light">
                        <i class="fas fa-search" style="margin-right:.45rem;"></i>Découvrir les offres
                    </a>
                    <a href="<?= e($viewsPath) ?>/poster-offre.php" class="btn btn-outline hero-outline">
                        <i class="fas fa-plus" style="margin-right:.45rem;"></i>Publier une offre
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="stat">
                        <h3><?= count($jobs) ?></h3>
                        <p>Offres actives</p>
                    </div>
                    <div class="stat">
                        <h3><?= count($categories) ?></h3>
                        <p>Secteurs couverts</p>
                    </div>
                    <div class="stat">
                        <h3>100&nbsp;%</h3>
                        <p>Offres vérifiées</p>
                    </div>
                </div>
            </div>

            <!-- Right: search card -->
            <div class="hero-panel">
                <div class="panel-card">
                    <div class="panel-topline">
                        <span><i class="fas fa-bolt" style="color:#fbbf24;margin-right:.35rem;"></i>Recherche rapide</span>
                        <strong>Données en direct</strong>
                    </div>

                    <form method="get" action="/index.php" class="search-box">
                        <div class="search-input-group">
                            <i class="fas fa-search"></i>
                            <input
                                type="text"
                                name="search"
                                placeholder="Titre, mot-clé, entreprise…"
                                value="<?= e($filters['search']) ?>"
                                autocomplete="off"
                            >
                        </div>
                        <div class="search-input-group">
                            <i class="fas fa-map-marker-alt"></i>
                            <input
                                type="text"
                                name="city"
                                placeholder="Ville (ex. Douala, Yaoundé)"
                                value="<?= e($filters['city']) ?>"
                                autocomplete="off"
                            >
                        </div>
                        <button class="btn btn-search" type="submit">
                            <i class="fas fa-arrow-right" style="margin-right:.4rem;"></i>Trouver des offres
                        </button>
                    </form>

                    <ul class="signal-list">
                        <li><span class="signal-dot"></span>Offres mises à jour quotidiennement</li>
                        <li><span class="signal-dot"></span>Candidature en un clic</li>
                        <li><span class="signal-dot"></span>Suivi recruteur en temps réel</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     TRUST BAR
════════════════════════════════════════════════════ -->
<section class="trust-strip">
    <div class="container trust-grid">
        <div class="trust-pill">
            <strong><?= count($jobs) ?>+</strong>
            <span>offres disponibles</span>
        </div>
        <div class="trust-pill">
            <strong><?= count($categories) ?></strong>
            <span>secteurs d'activité</span>
        </div>
        <div class="trust-pill">
            <strong>PHP&nbsp;8</strong>
            <span>stack moderne &amp; sécurisée</span>
        </div>
        <div class="trust-pill">
            <strong>24/7</strong>
            <span>plateforme disponible</span>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     CATEGORIES
════════════════════════════════════════════════════ -->
<section class="categories" id="categories">
    <div class="container">
        <h2 class="section-title">
            <span class="section-title-accent">Explorer par secteur</span>
        </h2>
        <p class="section-subtitle">
            Parcourez nos catégories et trouvez les opportunités qui correspondent à votre profil.
        </p>

        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
                <a class="category-card" href="/index.php?category=<?= urlencode($cat['slug']) ?>#jobs">
                    <div class="category-icon">
                        <i class="<?= e($cat['icon'] ?: 'fas fa-briefcase') ?>"></i>
                    </div>
                    <h3><?= e($cat['name']) ?></h3>
                    <p><?= e($cat['description'] ?: 'Opportunités professionnelles') ?></p>
                    <span class="category-count">Voir les offres <i class="fas fa-arrow-right" style="font-size:.7rem;margin-left:.2rem;"></i></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     JOBS
════════════════════════════════════════════════════ -->
<section class="jobs-section" id="jobs">
    <div class="container">

        <h2 class="section-title">
            <span class="section-title-accent">Offres disponibles</span>
        </h2>
        <p class="section-subtitle">
            <?php if (array_filter($filters)): ?>
                Résultats filtrés — <?= count($jobs) ?> offre<?= count($jobs) !== 1 ? 's' : '' ?> trouvée<?= count($jobs) !== 1 ? 's' : '' ?>
            <?php else: ?>
                Toutes les offres publiées, mises à jour en temps réel
            <?php endif; ?>
        </p>

        <!-- Active filter pills -->
        <?php $activeFilters = array_filter($filters); ?>
        <?php if ($activeFilters): ?>
            <div class="active-filters">
                <?php if ($filters['search']): ?>
                    <span class="active-filter-pill"><i class="fas fa-search"></i><?= e($filters['search']) ?></span>
                <?php endif; ?>
                <?php if ($filters['city']): ?>
                    <span class="active-filter-pill"><i class="fas fa-map-marker-alt"></i><?= e($filters['city']) ?></span>
                <?php endif; ?>
                <?php if ($filters['category']): ?>
                    <span class="active-filter-pill"><i class="fas fa-tag"></i><?= e($filters['category']) ?></span>
                <?php endif; ?>
                <?php if ($filters['contract_type']): ?>
                    <span class="active-filter-pill"><i class="fas fa-file-contract"></i><?= e($filters['contract_type']) ?></span>
                <?php endif; ?>
                <a href="/index.php#jobs" style="font-size:.8rem;color:#9ca3af;text-decoration:none;align-self:center;margin-left:.25rem;">
                    <i class="fas fa-times-circle"></i> Effacer
                </a>
            </div>
        <?php endif; ?>

        <!-- Filter bar -->
        <form method="get" action="/index.php" class="filter-bar">
            <div class="filter-group filter-group-wide">
                <label for="f-search">Mot-clé</label>
                <input
                    id="f-search"
                    type="text"
                    name="search"
                    value="<?= e($filters['search']) ?>"
                    placeholder="PHP, marketing, réseau…"
                    autocomplete="off"
                >
            </div>

            <div class="filter-group">
                <label for="f-city">Ville</label>
                <input
                    id="f-city"
                    type="text"
                    name="city"
                    value="<?= e($filters['city']) ?>"
                    placeholder="Douala, Yaoundé…"
                    autocomplete="off"
                >
            </div>

            <div class="filter-group">
                <label for="f-category">Secteur</label>
                <select id="f-category" name="category">
                    <option value="">Tous les secteurs</option>
                    <?php foreach ($categories as $cat): ?>
                        <option
                            value="<?= e($cat['slug']) ?>"
                            <?= $filters['category'] === $cat['slug'] ? 'selected' : '' ?>
                        >
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="f-contract">Contrat</label>
                <select id="f-contract" name="contract_type">
                    <option value="">Tous les contrats</option>
                    <?php foreach (['CDI', 'CDD', 'Stage', 'Freelance', 'Alternance'] as $ct): ?>
                        <option value="<?= e($ct) ?>" <?= $filters['contract_type'] === $ct ? 'selected' : '' ?>>
                            <?= e($ct) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter" style="margin-right:.35rem;"></i>Appliquer
                </button>
                <a href="/index.php#jobs" class="btn btn-outline">
                    <i class="fas fa-rotate-left" style="margin-right:.35rem;"></i>Réinitialiser
                </a>
            </div>
        </form>

        <!-- Results -->
        <?php if (!$jobs): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-search-minus"></i></div>
                <h3>Aucune offre ne correspond à cette recherche</h3>
                <p>Essayez un autre mot-clé, une autre ville ou réinitialisez les filtres.</p>
                <a href="/index.php#jobs" class="btn btn-primary" style="margin-top:1.25rem;">
                    Voir toutes les offres
                </a>
            </div>
        <?php else: ?>
            <?php $visibleLimit = 6; $hasMore = count($jobs) > $visibleLimit; ?>
            <div class="jobs-grid" id="jobs-grid">
                <?php foreach ($jobs as $idx => $job): ?>
                    <article class="job-card <?= $idx >= $visibleLimit ? 'job-card-hidden' : '' ?>" data-category="<?= e(strtolower($job['contract_type'])) ?>">

                        <div class="job-header">
                            <span class="job-badge"><?= e($job['contract_type']) ?></span>
                            <span class="job-mini-tag"><?= e($job['category_name'] ?: 'Général') ?></span>
                        </div>

                        <h3 class="job-title"><?= e($job['title']) ?></h3>
                        <p class="company-name">
                            <i class="fas fa-building" style="opacity:.5;font-size:.85rem;margin-right:.3rem;"></i>
                            <?= e($job['company_name']) ?>
                        </p>

                        <div class="job-details">
                            <span>
                                <i class="fas fa-map-marker-alt"></i>
                                <?= e($job['city']) ?>, <?= e($job['country']) ?>
                            </span>
                            <span class="job-salary">
                                <i class="fas fa-wallet"></i>
                                <?= e(format_money((float) $job['salary_min'], $job['salary_period'])) ?>
                            </span>
                        </div>

                        <?php
                            $rawTags = array_slice(
                                array_filter(array_map('trim', explode(',', (string) $job['tags']))),
                                0, 3
                            );
                        ?>
                        <?php if ($rawTags): ?>
                            <div class="job-tags">
                                <?php foreach ($rawTags as $tag): ?>
                                    <span><?= e($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="job-footer">
                            <span class="job-time">
                                <i class="far fa-clock"></i>
                                Jusqu'au <?= e(format_datetime($job['expires_at'])) ?>
                            </span>
                            <a href="/views/job-details.php?id=<?= (int) $job['id'] ?>" class="btn btn-apply">
                                Voir l'offre
                            </a>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($hasMore): ?>
                <div class="load-more" style="text-align:center;margin-top:2rem;">
                    <button id="btn-voir-plus" class="btn btn-outline" style="padding:14px 40px;font-size:15px;">
                        <i class="fas fa-chevron-down" style="margin-right:.5rem;"></i>
                        Voir plus d'offres
                        <span style="margin-left:.4rem;opacity:.6;font-size:13px;">(<?= count($jobs) - $visibleLimit ?> restantes)</span>
                    </button>
                </div>
                <script>
                document.getElementById('btn-voir-plus').addEventListener('click', function() {
                    document.querySelectorAll('.job-card-hidden').forEach(function(card) {
                        card.classList.remove('job-card-hidden');
                        card.style.animation = 'fadeInUp 0.4s ease forwards';
                    });
                    this.parentElement.style.display = 'none';
                });
                </script>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</section>

<!-- ════════════════════════════════════════════════════
     FEATURES
════════════════════════════════════════════════════ -->
<section class="feature-spotlight">
    <div class="container">
        <h2 class="section-title" style="text-align:center;margin-bottom:2.5rem;">
            <span class="section-title-accent">Pourquoi JobHub&nbsp;?</span>
        </h2>
        <div class="feature-grid">
            <article class="feature-card">
                <span class="feature-icon"><i class="fas fa-layer-group"></i></span>
                <h3>Recherche puissante</h3>
                <p>Filtres combinés par mot-clé, ville, secteur et type de contrat — résultats instantanés depuis la base de données.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon"><i class="fas fa-bolt"></i></span>
                <h3>Parcours sans friction</h3>
                <p>De la découverte d'une offre à la candidature : un parcours testé, fluide et accessible depuis n'importe quel appareil.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon"><i class="fas fa-shield-alt"></i></span>
                <h3>Plateforme de confiance</h3>
                <p>Sessions sécurisées, protection CSRF, uploads contrôlés — votre compte et vos données sont protégés à chaque étape.</p>
            </article>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════════════════ -->
<section class="how-it-works">
    <div class="container">
        <h2 class="section-title">
            <span class="section-title-accent">Comment ça marche&nbsp;?</span>
        </h2>
        <p class="section-subtitle">
            Trois étapes simples pour trouver un emploi ou recruter le bon candidat.
        </p>

        <div class="steps-grid">

            <div class="step-card">
                <div class="step-number">
                    <span class="step-icon">01</span>
                </div>
                <h3>Créez votre compte</h3>
                <p>Inscrivez-vous en tant que candidat pour postuler, ou en tant qu'entreprise pour publier et gérer vos offres.</p>
            </div>

            <div class="step-card">
                <div class="step-number">
                    <span class="step-icon">02</span>
                </div>
                <h3>Explorez ou publiez</h3>
                <p>Parcourez des centaines d'offres filtrées selon vos critères, ou publiez votre annonce en quelques minutes.</p>
            </div>

            <div class="step-card">
                <div class="step-number">
                    <span class="step-icon">03</span>
                </div>
                <h3>Connectez-vous</h3>
                <p>Postulez en un clic et suivez vos candidatures, ou gérez les profils reçus depuis votre tableau de bord entreprise.</p>
            </div>

        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     CTA
════════════════════════════════════════════════════ -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Prêt à franchir le pas&nbsp;?</h2>
            <p>
                Rejoignez des milliers de candidats et d'entreprises qui font confiance à JobHub pour leurs recrutements au Cameroun.
            </p>
            <div class="hero-inline-actions">
                <a href="<?= e($viewsPath) ?>/inscription.php" class="btn btn-light">
                    <i class="fas fa-user-plus" style="margin-right:.45rem;"></i>Créer un compte gratuit
                </a>
                <a href="<?= e($viewsPath) ?>/poster-offre.php" class="btn btn-outline cta-outline">
                    <i class="fas fa-pen-to-square" style="margin-right:.45rem;"></i>Publier une offre
                </a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/app/views/footer.php'; ?>
