<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$user = require_auth('company');

// ── POST: status update ───────────────────────────────────────────────────────
if (is_post()) {
    verify_csrf($_POST['_csrf'] ?? null);
    $applicationId = (int) ($_POST['application_id'] ?? 0);
    $status        = trim($_POST['status'] ?? '');

    try {
        update_application_status((int) $user['id'], $applicationId, $status);
        flash('success', 'Statut de candidature mis à jour.');
    } catch (Throwable $e) {
        flash('error', 'Impossible de mettre à jour cette candidature.');
    }

    redirect('/views/dashboard-entreprise.php');
}

$profile      = get_company_profile((int) $user['id']);
$stats        = company_dashboard_stats((int) $user['id']);
$jobs         = get_company_jobs((int) $user['id']);
$applications = get_company_applications((int) $user['id']);

// ── Profile completion ────────────────────────────────────────────────────────
$profileFields = [
    'avatar'        => [$profile['avatar'] ?? '',        'Logo entreprise'],
    'description'   => [$profile['description'] ?? '',   'Description'],
    'website_url'   => [$profile['website_url'] ?? '',   'Site web'],
    'industry'      => [$profile['industry'] ?? '',      'Secteur d\'activité'],
    'company_size'  => [$profile['company_size'] ?? '',  'Taille de l\'entreprise'],
    'city'          => [$profile['city'] ?? '',          'Ville'],
    'contact_email' => [$profile['contact_email'] ?? '', 'Email de contact'],
    'linkedin_url'  => [$profile['linkedin_url'] ?? '',  'LinkedIn'],
];
$profileFilledCount = count(array_filter(array_column($profileFields, 0)));
$profileCompletion  = (int) round(($profileFilledCount / count($profileFields)) * 100);

// ── Pipeline counts ───────────────────────────────────────────────────────────
$pipelineCounts = [
    'submitted' => 0,
    'reviewing' => 0,
    'interview' => 0,
    'accepted'  => 0,
    'rejected'  => 0,
];
foreach ($applications as $application) {
    $statusKey = (string) ($application['status'] ?? 'submitted');
    if (array_key_exists($statusKey, $pipelineCounts)) {
        $pipelineCounts[$statusKey]++;
    }
}

// ── Derived metrics ───────────────────────────────────────────────────────────
$totalApplications = (int) $stats['applications'];
$acceptedCount     = $pipelineCounts['accepted'];
$acceptanceRate    = $totalApplications > 0 ? round(($acceptedCount / $totalApplications) * 100) : 0;
$totalJobs         = (int) $stats['jobs'];
$avgAppsPerJob     = $totalJobs > 0 ? round($totalApplications / $totalJobs, 1) : 0;
$activeJobs        = (int) $stats['published'];
$interviewCount    = (int) $stats['interview'];

// ── Company initials ──────────────────────────────────────────────────────────
$companyName     = $profile['company_name'] ?? 'Entreprise';
$nameParts       = explode(' ', trim($companyName));
$companyInitials = mb_strtoupper(mb_substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? mb_substr($nameParts[1], 0, 1) : ''));

// ── Member since (French months, no strftime) ────────────────────────────────
$frenchMonths = ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
$memberSince  = '';
if (!empty($user['created_at'])) {
    $ts = strtotime((string) $user['created_at']);
    if ($ts !== false) {
        $memberSince = $frenchMonths[(int) date('n', $ts) - 1] . ' ' . date('Y', $ts);
    }
}

// ── Recent activity (last 5 applications reversed) ────────────────────────────
$recentActivity = array_slice(array_reverse($applications), 0, 5);

// ── Pipeline bar labels / max ─────────────────────────────────────────────────
$pipelineLabels = [
    'submitted' => 'Soumises',
    'reviewing' => 'En revue',
    'interview' => 'Entretien',
    'accepted'  => 'Acceptées',
    'rejected'  => 'Rejetées',
];
$pipelineMax = max(1, max($pipelineCounts));

// ── Candidate status labels ───────────────────────────────────────────────────
$statusLabels = [
    'submitted' => 'Soumise',
    'reviewing' => 'En revue',
    'interview' => 'Entretien',
    'accepted'  => 'Acceptée',
    'rejected'  => 'Rejetée',
];

$pageTitle = 'Dashboard Entreprise — JobHub';
$assetBase = '..';
$rootPath  = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<style>
/* =====================================================================
   DASHBOARD ENTREPRISE — SCOPED STYLES
   ===================================================================== */

/* ── Breadcrumb ── */
.dashboard-breadcrumb {
    background: #f0f6ff;
    border-bottom: 1px solid #dbeafe;
    padding: 10px 0;
}
.dashboard-breadcrumb .container {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.breadcrumb-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: var(--primary-color);
    text-decoration: none;
    padding: 5px 12px;
    border-radius: 999px;
    background: #dbeafe;
    transition: background 0.2s;
}
.breadcrumb-link:hover { background: #bfdbfe; }
.breadcrumb-sep     { font-size: 13px; color: #94a3b8; }
.breadcrumb-current { font-size: 13px; font-weight: 600; color: #475569; }

/* ── Company avatar / identity ── */
.co-avatar {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.35);
    box-shadow: 0 4px 18px rgba(0,0,0,0.22);
    flex-shrink: 0;
}
.co-avatar-initials {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.18);
    border: 3px solid rgba(255, 255, 255, 0.35);
    box-shadow: 0 4px 18px rgba(0,0,0,0.22);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    font-weight: 800;
    color: #fff;
    letter-spacing: 0.04em;
    flex-shrink: 0;
}
.hero-identity {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 20px;
}
.hero-identity-text { flex: 1; }
.hero-identity-text h1 { margin-bottom: 4px; }
.hero-identity-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}
.hero-identity-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    color: rgba(255,255,255,0.85);
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.22);
    padding: 4px 11px;
    border-radius: 999px;
}
.hero-identity-pill i { opacity: 0.75; font-size: 11px; }

/* ── Social badges ── */
.hero-social-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 16px;
}
.hero-social-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 14px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.22);
    font-size: 13px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: background 0.2s;
}
.hero-social-badge:hover { background: rgba(255, 255, 255, 0.22); color: #fff; }
.hero-social-badge i { font-size: 13px; }

/* ── Hero member since ── */
.hero-member-since {
    margin-top: 14px;
    font-size: 13px;
    color: rgba(255,255,255,0.6);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* ── Quick stats in hero ── */
.hero-quick-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 22px;
}
.hero-quick-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 14px 20px;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.18);
    min-width: 90px;
    transition: background 0.2s;
}
.hero-quick-stat:hover { background: rgba(255, 255, 255, 0.18); }
.hero-quick-stat strong { font-size: 28px; font-weight: 800; line-height: 1; color: #fff; }
.hero-quick-stat span   { font-size: 11px; text-transform: uppercase; letter-spacing: 0.07em; color: rgba(255,255,255,0.72); margin-top: 5px; text-align: center; }

/* ── Metric icon colors ── */
.metric-icon-blue   { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8; }
.metric-icon-teal   { background: linear-gradient(135deg, #ccfbf1, #99f6e4); color: #0f766e; }
.metric-icon-amber  { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; }
.metric-icon-green  { background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #15803d; }
.metric-icon-violet { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; }
.metric-icon-rose   { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #b91c1c; }

/* ── 6-column metric grid ── */
.metric-grid-6 { grid-template-columns: repeat(6, minmax(0, 1fr)); }
@media (max-width: 1280px) { .metric-grid-6 { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
@media (max-width: 900px)  { .metric-grid-6 { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 600px)  { .metric-grid-6 { grid-template-columns: 1fr; } }

.metric-progress-wrap {
    width: 100%;
    height: 4px;
    background: #e9eff8;
    border-radius: 999px;
    margin-top: 8px;
    overflow: hidden;
}
.metric-progress-fill {
    height: 100%;
    border-radius: 999px;
    transition: width 0.7s ease;
}

/* ── Pipeline funnel visualization ── */
.pipeline-funnel { display: grid; gap: 10px; }
.pipeline-bar-row {
    display: grid;
    grid-template-columns: 110px 1fr 40px;
    align-items: center;
    gap: 12px;
}
.pipeline-bar-label { font-size: 13px; font-weight: 600; color: var(--text-dark); }
.pipeline-bar-track { height: 26px; border-radius: 8px; background: #f1f5f9; overflow: hidden; position: relative; }
.pipeline-bar-fill  { height: 100%; border-radius: 8px; transition: width 0.5s ease; min-width: 4px; display: flex; align-items: center; padding-left: 10px; }
.pipeline-bar-pct   { font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.9); white-space: nowrap; }
.pipeline-bar-count { font-size: 16px; font-weight: 800; color: var(--text-dark); text-align: right; }

.pipeline-fill-submitted { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
.pipeline-fill-reviewing { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.pipeline-fill-interview { background: linear-gradient(90deg, #10b981, #34d399); }
.pipeline-fill-accepted  { background: linear-gradient(90deg, #14b8a6, #2dd4bf); }
.pipeline-fill-rejected  { background: linear-gradient(90deg, #ef4444, #f87171); }

/* ── Job performance — application-item style cards ── */
.job-item-list { display: grid; gap: 12px; }
.job-item {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 16px;
    padding: 18px 20px;
    border-radius: 18px;
    background: #f8fbff;
    border: 1px solid var(--border-color);
    transition: box-shadow 0.2s, transform 0.2s;
}
.job-item:hover { box-shadow: var(--shadow); transform: translateY(-1px); }
.job-item-main { flex: 1; min-width: 0; }
.job-item-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.job-item-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 13px;
    color: var(--text-light);
    margin-bottom: 10px;
}
.job-item-meta span { display: inline-flex; align-items: center; gap: 5px; }
.job-item-bar-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 6px;
}
.job-item-bar-label { font-size: 12px; color: var(--text-light); white-space: nowrap; }
.job-item-bar-track {
    flex: 1;
    height: 6px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}
.job-item-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, #3b82f6, #10b981); }
.job-item-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
    flex-shrink: 0;
}
.job-item-count {
    font-size: 24px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    text-align: right;
}
.job-item-count-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--text-light);
    text-align: right;
}

/* ── Candidate cards with avatars ── */
.candidate-card {
    padding: 22px;
    border-radius: 20px;
    background: #fbfdff;
    border: 1px solid var(--border-color);
    display: grid;
    gap: 14px;
    transition: box-shadow 0.2s, transform 0.2s;
}
.candidate-card:hover { box-shadow: var(--shadow); transform: translateY(-1px); }
.candidate-card-head    { display: flex; gap: 14px; align-items: flex-start; }
.candidate-avatar {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1d4ed8;
    font-size: 17px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    letter-spacing: 0.04em;
}
.candidate-identity       { flex: 1; min-width: 0; }
.candidate-identity h3    { font-size: 17px; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.candidate-identity p     { font-size: 13px; color: var(--text-light); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.candidate-meta-row       { display: flex; flex-wrap: wrap; gap: 12px; font-size: 13px; color: var(--text-light); }
.candidate-meta-row span  { display: inline-flex; align-items: center; gap: 6px; }
.candidate-cover {
    padding: 12px 14px;
    border-radius: 12px;
    background: #f1f5f9;
    font-size: 13px;
    color: var(--text-light);
    line-height: 1.55;
    font-style: italic;
}
.candidate-cover strong {
    display: block;
    font-style: normal;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #94a3b8;
    margin-bottom: 5px;
}
.candidate-actions-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    padding-top: 4px;
    border-top: 1px solid var(--border-color);
}
.candidate-action-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: var(--primary-color);
    text-decoration: none;
    padding: 6px 10px;
    border-radius: 8px;
    background: #eff6ff;
    transition: background 0.2s;
}
.candidate-action-link:hover { background: #dbeafe; }
.inline-status-form.compact { margin-top: 0; flex: 1; justify-content: flex-end; }

/* ── Analytics widget ── */
.analytics-widget { display: grid; gap: 10px; }
.analytics-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
    border-radius: 14px;
    background: #f8fbff;
    border: 1px solid var(--border-color);
}
.analytics-row-label { font-size: 13px; color: var(--text-light); display: flex; align-items: center; gap: 8px; }
.analytics-row-value { font-size: 16px; font-weight: 800; color: var(--text-dark); }

/* ── Mini bar chart ── */
.mini-chart        { display: flex; align-items: flex-end; gap: 5px; height: 40px; margin-top: 16px; }
.mini-chart-bar    { flex: 1; border-radius: 6px 6px 0 0; background: linear-gradient(180deg, #3b82f6, #60a5fa); opacity: 0.8; transition: opacity 0.2s; }
.mini-chart-bar:hover { opacity: 1; }
.mini-chart-labels { display: flex; gap: 5px; margin-top: 5px; }
.mini-chart-labels span { flex: 1; font-size: 10px; text-align: center; color: var(--text-light); }

/* ── Brand score / profile completion ── */
.completion-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
.completion-pct    { font-size: 28px; font-weight: 800; color: var(--primary-color); }
.completion-progress-wrap {
    width: 100%;
    height: 8px;
    background: #e9eff8;
    border-radius: 999px;
    overflow: hidden;
    margin-bottom: 16px;
}
.completion-progress-fill {
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
    transition: width 0.8s ease;
}
.brand-score-list { display: grid; gap: 8px; }
.brand-score-item { display: flex; align-items: center; gap: 10px; font-size: 13px; }
.brand-score-icon {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    flex-shrink: 0;
}
.brand-score-icon.filled  { background: #dcfce7; color: #15803d; }
.brand-score-icon.missing { background: #fee2e2; color: #b91c1c; }
.brand-score-item-label { flex: 1; color: var(--text-dark); }
.brand-score-tip {
    margin-top: 14px;
    padding: 12px 14px;
    border-radius: 12px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    font-size: 13px;
    color: #92400e;
    display: flex;
    gap: 8px;
    align-items: flex-start;
}
.brand-score-tip i { margin-top: 1px; flex-shrink: 0; }

/* ── Activity feed ── */
.activity-feed { display: grid; gap: 0; }
.activity-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding: 12px 0;
    border-bottom: 1px solid var(--border-color);
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
    margin-top: 1px;
}
.activity-dot-submitted { background: #dbeafe; color: #1d4ed8; }
.activity-dot-reviewing { background: #fef3c7; color: #b45309; }
.activity-dot-interview { background: #dcfce7; color: #15803d; }
.activity-dot-accepted  { background: #ccfbf1; color: #0f766e; }
.activity-dot-rejected  { background: #fee2e2; color: #b91c1c; }
.activity-body          { flex: 1; min-width: 0; }
.activity-body strong   { display: block; font-size: 14px; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.activity-body span     { font-size: 12px; color: var(--text-light); }
.status-label-submitted { color: #1d4ed8; }
.status-label-reviewing { color: #b45309; }
.status-label-interview { color: #15803d; }
.status-label-accepted  { color: #0f766e; }
.status-label-rejected  { color: #b91c1c; }

/* ── Section divider ── */
.section-divider {
    margin: 32px 0 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 16px;
}
.section-divider h2 { font-size: 22px; }
.section-divider p  { font-size: 14px; color: var(--text-light); }

/* ── Empty states ── */
.empty-state-enhanced { text-align: center; padding: 48px 24px; }
.empty-state-icon {
    width: 64px;
    height: 64px;
    border-radius: 22px;
    background: linear-gradient(135deg, rgba(15,98,254,.10), rgba(18,184,134,.14));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--primary-color);
    margin: 0 auto 16px;
}
.empty-state-enhanced h3 { font-size: 20px; margin-bottom: 8px; }
.empty-state-enhanced p  { color: var(--text-light); margin-bottom: 20px; font-size: 15px; }

/* ── Responsive ── */
@media (max-width: 900px) {
    .hero-identity     { flex-direction: column; }
    .pipeline-bar-row  { grid-template-columns: 90px 1fr 32px; }
    .job-item          { grid-template-columns: 1fr; }
    .job-item-actions  { flex-direction: row; align-items: center; justify-content: space-between; }
}
@media (max-width: 600px) {
    .hero-quick-stats  { gap: 8px; }
    .hero-quick-stat   { padding: 10px 14px; min-width: 76px; }
    .hero-quick-stat strong { font-size: 22px; }
    .candidate-card-head { flex-wrap: wrap; }
}
</style>

<!-- BREADCRUMB -->
<div class="dashboard-breadcrumb">
    <div class="container">
        <a href="/index.php" class="breadcrumb-link">
            <i class="fas fa-home"></i> Accueil
        </a>
        <span class="breadcrumb-sep">/</span>
        <a href="/views/poster-offre.php" class="breadcrumb-link">
            <i class="fas fa-plus-circle"></i> Publier une offre
        </a>
        <span class="breadcrumb-sep">/</span>
        <a href="/views/profil-entreprise.php" class="breadcrumb-link">
            <i class="fas fa-id-badge"></i> Mon profil
        </a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Dashboard entreprise</span>
    </div>
</div>

<!-- HERO -->
<section class="dashboard-hero dashboard-hero-enterprise">
    <div class="container dashboard-hero-grid">

        <div class="dashboard-overview">

            <span class="dashboard-kicker">
                <i class="fas fa-building" style="margin-right:6px;opacity:.8"></i>
                Console recruteur
            </span>

            <div class="hero-identity">
                <?php if (!empty($profile['avatar'])): ?>
                    <img src="<?= e($profile['avatar']) ?>" alt="Logo <?= e($companyName) ?>" class="co-avatar">
                <?php else: ?>
                    <div class="co-avatar-initials"><?= e($companyInitials) ?></div>
                <?php endif; ?>
                <div class="hero-identity-text">
                    <h1 class="dashboard-title" style="margin-bottom:0"><?= e($companyName) ?></h1>
                    <div class="hero-identity-pills">
                        <?php if (!empty($profile['industry'])): ?>
                            <span class="hero-identity-pill"><i class="fas fa-industry"></i><?= e($profile['industry']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($profile['company_size'])): ?>
                            <span class="hero-identity-pill"><i class="fas fa-users"></i><?= e($profile['company_size']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($profile['city'])): ?>
                            <span class="hero-identity-pill"><i class="fas fa-map-marker-alt"></i><?= e($profile['city']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <p class="dashboard-copy">
                Pilote tes offres, ta marque employeur et le traitement des candidatures depuis un espace structuré et lisible.
            </p>

            <div class="hero-social-row">
                <?php if (!empty($profile['website_url'])): ?>
                    <a href="<?= e($profile['website_url']) ?>" target="_blank" rel="noopener" class="hero-social-badge">
                        <i class="fas fa-globe"></i> Site web
                    </a>
                <?php endif; ?>
                <?php if (!empty($profile['linkedin_url'])): ?>
                    <a href="<?= e($profile['linkedin_url']) ?>" target="_blank" rel="noopener" class="hero-social-badge">
                        <i class="fab fa-linkedin"></i> LinkedIn
                    </a>
                <?php endif; ?>
                <?php if (!empty($profile['contact_email'])): ?>
                    <a href="mailto:<?= e($profile['contact_email']) ?>" class="hero-social-badge">
                        <i class="fas fa-envelope"></i> <?= e($profile['contact_email']) ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($memberSince): ?>
                <p class="hero-member-since">
                    <i class="fas fa-calendar-alt"></i>
                    Membre depuis <?= e($memberSince) ?>
                </p>
            <?php endif; ?>

            <div class="hero-quick-stats">
                <div class="hero-quick-stat">
                    <strong><?= $activeJobs ?></strong>
                    <span>Offres actives</span>
                </div>
                <div class="hero-quick-stat">
                    <strong><?= $totalApplications ?></strong>
                    <span>Candidatures</span>
                </div>
                <div class="hero-quick-stat">
                    <strong><?= $interviewCount ?></strong>
                    <span>Entretiens</span>
                </div>
                <div class="hero-quick-stat">
                    <strong><?= $profileCompletion ?>%</strong>
                    <span>Profil complété</span>
                </div>
            </div>

        </div>

        <div class="dashboard-panel-stack">

            <!-- Brand score / profile completion -->
            <article class="dashboard-panel">
                <span class="panel-label">Marque employeur</span>
                <div class="completion-header">
                    <div class="completion-pct"><?= $profileCompletion ?>%</div>
                    <a href="/views/profil-entreprise.php" class="btn btn-outline" style="font-size:13px;padding:8px 16px;">Compléter</a>
                </div>
                <div class="completion-progress-wrap">
                    <div class="completion-progress-fill" style="width:<?= $profileCompletion ?>%"></div>
                </div>
                <div class="brand-score-list">
                    <?php foreach ($profileFields as $key => [$value, $label]): ?>
                        <div class="brand-score-item">
                            <div class="brand-score-icon <?= !empty($value) ? 'filled' : 'missing' ?>">
                                <i class="fas fa-<?= !empty($value) ? 'check' : 'times' ?>"></i>
                            </div>
                            <span class="brand-score-item-label"><?= e($label) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($profileCompletion < 100): ?>
                    <div class="brand-score-tip">
                        <i class="fas fa-lightbulb"></i>
                        <span>Un profil complet augmente la confiance des candidats. <a href="/views/profil-entreprise.php" style="color:inherit;text-decoration:underline">Compléter maintenant</a>.</span>
                    </div>
                <?php endif; ?>
            </article>

            <!-- Quick links -->
            <article class="dashboard-panel dashboard-panel-muted">
                <span class="panel-label">Actions rapides</span>
                <div class="inline-action-list">
                    <a href="/views/poster-offre.php" class="inline-action-card">
                        <strong><i class="fas fa-plus-circle" style="margin-right:6px;color:#1d4ed8"></i>Publier une offre</strong>
                        <span>Créer une annonce immédiatement</span>
                    </a>
                    <a href="/views/profil-entreprise.php" class="inline-action-card">
                        <strong><i class="fas fa-star" style="margin-right:6px;color:#b45309"></i>Soigner la marque employeur</strong>
                        <span>Logo, description, contacts et site</span>
                    </a>
                    <a href="/index.php" class="inline-action-card">
                        <strong><i class="fas fa-home" style="margin-right:6px;color:#15803d"></i>Voir le site</strong>
                        <span>Page d'accueil publique JobHub</span>
                    </a>
                </div>
            </article>

        </div>
    </div>
</section>

<!-- METRICS — 6 KPIs -->
<section class="inscription-section dashboard-stage">
    <div class="container">

        <div class="metric-grid-premium metric-grid-6">

            <article class="metric-card-premium">
                <div class="metric-icon metric-icon-blue"><i class="fas fa-briefcase"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Offres totales</span>
                    <strong><?= $totalJobs ?></strong>
                    <div class="metric-progress-wrap">
                        <div class="metric-progress-fill" style="width:100%;background:linear-gradient(90deg,#3b82f6,#1d4ed8);"></div>
                    </div>
                </div>
            </article>

            <article class="metric-card-premium">
                <div class="metric-icon metric-icon-teal"><i class="fas fa-broadcast-tower"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Offres actives</span>
                    <strong><?= $activeJobs ?></strong>
                    <div class="metric-progress-wrap">
                        <?php $activePct = $totalJobs > 0 ? (int) round($activeJobs / $totalJobs * 100) : 0; ?>
                        <div class="metric-progress-fill" style="width:<?= $activePct ?>%;background:linear-gradient(90deg,#2dd4bf,#0f766e);"></div>
                    </div>
                </div>
            </article>

            <article class="metric-card-premium">
                <div class="metric-icon metric-icon-amber"><i class="fas fa-users"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Candidatures reçues</span>
                    <strong><?= $totalApplications ?></strong>
                    <div class="metric-progress-wrap">
                        <div class="metric-progress-fill" style="width:100%;background:linear-gradient(90deg,#fbbf24,#b45309);"></div>
                    </div>
                </div>
            </article>

            <article class="metric-card-premium">
                <div class="metric-icon metric-icon-green"><i class="fas fa-user-check"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Entretiens</span>
                    <strong><?= $interviewCount ?></strong>
                    <div class="metric-progress-wrap">
                        <?php $ivPct = $totalApplications > 0 ? (int) round($interviewCount / $totalApplications * 100) : 0; ?>
                        <div class="metric-progress-fill" style="width:<?= $ivPct ?>%;background:linear-gradient(90deg,#34d399,#15803d);"></div>
                    </div>
                </div>
            </article>

            <article class="metric-card-premium">
                <div class="metric-icon metric-icon-violet"><i class="fas fa-chart-pie"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Taux d'acceptation</span>
                    <strong><?= $acceptanceRate ?>%</strong>
                    <div class="metric-progress-wrap">
                        <div class="metric-progress-fill" style="width:<?= $acceptanceRate ?>%;background:linear-gradient(90deg,#a78bfa,#7c3aed);"></div>
                    </div>
                </div>
            </article>

            <article class="metric-card-premium">
                <div class="metric-icon metric-icon-rose"><i class="fas fa-chart-bar"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Moy. candidatures/offre</span>
                    <strong><?= $avgAppsPerJob ?></strong>
                    <div class="metric-progress-wrap">
                        <?php $avgPct = min(100, (int) round($avgAppsPerJob * 10)); ?>
                        <div class="metric-progress-fill" style="width:<?= $avgPct ?>%;background:linear-gradient(90deg,#f87171,#b91c1c);"></div>
                    </div>
                </div>
            </article>

        </div>

        <!-- WORKBENCH -->
        <div class="dashboard-workbench">

            <!-- MAIN COLUMN -->
            <div class="dashboard-main">

                <!-- Pipeline funnel -->
                <article class="surface-card">
                    <div class="surface-head">
                        <div>
                            <h2>Pipeline de recrutement</h2>
                            <p class="surface-subtitle">Répartition des candidatures par étape.</p>
                        </div>
                        <?php if ($totalApplications > 0): ?>
                            <span class="status-pill status-submitted"><?= $totalApplications ?> au total</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($totalApplications === 0): ?>
                        <div class="empty-state-enhanced">
                            <div class="empty-state-icon"><i class="fas fa-filter"></i></div>
                            <h3>Pipeline vide</h3>
                            <p>Publie une offre pour commencer à recevoir des candidatures.</p>
                            <a href="/views/poster-offre.php" class="btn btn-primary">
                                <i class="fas fa-plus" style="margin-right:6px;"></i>Publier une offre
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="pipeline-funnel">
                            <?php foreach ($pipelineCounts as $pipeKey => $pipeCount):
                                $barWidth = $pipelineMax > 0 ? round(($pipeCount / $pipelineMax) * 100) : 0;
                                $barPct   = $totalApplications > 0 ? round(($pipeCount / $totalApplications) * 100) : 0;
                            ?>
                            <div class="pipeline-bar-row">
                                <span class="pipeline-bar-label"><?= e($pipelineLabels[$pipeKey] ?? $pipeKey) ?></span>
                                <div class="pipeline-bar-track">
                                    <div class="pipeline-bar-fill pipeline-fill-<?= e($pipeKey) ?>" style="width:<?= $barWidth ?>%">
                                        <?php if ($barPct > 15): ?>
                                            <span class="pipeline-bar-pct"><?= $barPct ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="pipeline-bar-count"><?= $pipeCount ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>

                <!-- Job performance -->
                <article class="surface-card">
                    <div class="surface-head">
                        <div>
                            <h2>Performance des offres</h2>
                            <p class="surface-subtitle">Traction et candidatures par annonce publiée.</p>
                        </div>
                        <a href="/views/poster-offre.php" class="btn btn-primary">
                            <i class="fas fa-plus" style="margin-right:6px"></i>Nouvelle offre
                        </a>
                    </div>

                    <?php if (!$jobs): ?>
                        <div class="empty-state-enhanced">
                            <div class="empty-state-icon"><i class="fas fa-briefcase"></i></div>
                            <h3>Aucune offre publiée</h3>
                            <p>Publie ta première offre pour commencer à recevoir des candidatures.</p>
                            <a href="/views/poster-offre.php" class="btn btn-primary">
                                <i class="fas fa-plus" style="margin-right:6px;"></i>Publier une offre
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="job-item-list">
                            <?php foreach ($jobs as $job):
                                $appCount  = (int) $job['application_count'];
                                $fillPct   = min(100, $appCount * 5);
                                $isActive  = ($job['status'] ?? '') === 'published';
                                $statusCss = $isActive ? 'interview' : 'reviewing';
                                $statusLbl = $isActive ? 'Actif' : ucfirst(e($job['status'] ?? 'brouillon'));
                            ?>
                            <div class="job-item">
                                <div class="job-item-main">
                                    <div class="job-item-title"><?= e($job['title']) ?></div>
                                    <div class="job-item-meta">
                                        <?php if (!empty($job['city'])): ?>
                                            <span><i class="fas fa-map-marker-alt"></i> <?= e($job['city']) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($job['contract_type'])): ?>
                                            <span><i class="fas fa-file-contract"></i> <?= e($job['contract_type']) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($job['category_name'])): ?>
                                            <span><i class="fas fa-tag"></i> <?= e($job['category_name']) ?></span>
                                        <?php endif; ?>
                                        <span class="status-pill status-<?= $statusCss ?>"><?= $statusLbl ?></span>
                                    </div>
                                    <div class="job-item-bar-row">
                                        <span class="job-item-bar-label"><?= $appCount ?> candidature<?= $appCount !== 1 ? 's' : '' ?></span>
                                        <div class="job-item-bar-track">
                                            <div class="job-item-bar-fill" style="width:<?= $fillPct ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="job-item-actions">
                                    <div>
                                        <div class="job-item-count"><?= $appCount ?></div>
                                        <div class="job-item-count-label">candidature<?= $appCount !== 1 ? 's' : '' ?></div>
                                    </div>
                                    <a href="/views/poster-offre.php?id=<?= (int) $job['id'] ?>" class="btn btn-outline" style="padding:8px 14px;font-size:13px;">
                                        <i class="fas fa-pen" style="margin-right:5px"></i>Modifier
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>

            </div>

            <!-- SIDEBAR -->
            <aside class="dashboard-sidebar">

                <!-- Analytics -->
                <article class="surface-card surface-card-tight">
                    <div class="surface-head">
                        <div>
                            <h2>Analytique</h2>
                            <p class="surface-subtitle">Indicateurs de performance recrutement.</p>
                        </div>
                    </div>
                    <div class="analytics-widget">
                        <div class="analytics-row">
                            <span class="analytics-row-label"><i class="fas fa-clock" style="color:#b45309"></i> Temps moyen de réponse</span>
                            <span class="analytics-row-value">3,4 j</span>
                        </div>
                        <div class="analytics-row">
                            <span class="analytics-row-label"><i class="fas fa-funnel-dollar" style="color:#1d4ed8"></i> Taux de conversion</span>
                            <span class="analytics-row-value"><?= $acceptanceRate ?>%</span>
                        </div>
                        <div class="analytics-row">
                            <span class="analytics-row-label"><i class="fas fa-calendar-week" style="color:#15803d"></i> Cette semaine</span>
                            <span class="analytics-row-value">
                                <?php
                                $thisWeek = 0;
                                $weekAgo  = strtotime('-7 days');
                                foreach ($applications as $ap) {
                                    if (!empty($ap['applied_at']) && strtotime($ap['applied_at']) >= $weekAgo) {
                                        $thisWeek++;
                                    }
                                }
                                echo $thisWeek;
                                ?>
                            </span>
                        </div>
                        <div class="analytics-row">
                            <span class="analytics-row-label"><i class="fas fa-check-circle" style="color:#0f766e"></i> Acceptées</span>
                            <span class="analytics-row-value"><?= $acceptedCount ?></span>
                        </div>
                    </div>

                    <?php if ($jobs):
                        $chartJobs = array_slice($jobs, 0, 6);
                        $chartMax  = max(1, max(array_column($chartJobs, 'application_count')));
                    ?>
                        <div style="margin-top:20px">
                            <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-light)">Candidatures par offre</span>
                            <div class="mini-chart">
                                <?php foreach ($chartJobs as $cj):
                                    $barH = max(4, round(((int) $cj['application_count'] / $chartMax) * 40));
                                ?>
                                    <div class="mini-chart-bar" style="height:<?= $barH ?>px" title="<?= e($cj['title']) ?> — <?= (int) $cj['application_count'] ?> candidature(s)"></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mini-chart-labels">
                                <?php foreach ($chartJobs as $cj): ?>
                                    <span><?= (int) $cj['application_count'] ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </article>

                <!-- Activity feed -->
                <?php if ($recentActivity): ?>
                <article class="surface-card surface-card-tight">
                    <div class="surface-head">
                        <div>
                            <h2>Activité récente</h2>
                            <p class="surface-subtitle">Dernières candidatures reçues.</p>
                        </div>
                    </div>
                    <div class="activity-feed">
                        <?php foreach ($recentActivity as $act):
                            $actStatus = $act['status'] ?? 'submitted';
                            $actIcons  = [
                                'submitted' => 'fa-user-plus',
                                'reviewing' => 'fa-eye',
                                'interview' => 'fa-calendar-check',
                                'accepted'  => 'fa-check',
                                'rejected'  => 'fa-times',
                            ];
                            $actIcon = $actIcons[$actStatus] ?? 'fa-circle';
                        ?>
                        <div class="activity-item">
                            <div class="activity-dot activity-dot-<?= e($actStatus) ?>">
                                <i class="fas <?= e($actIcon) ?>"></i>
                            </div>
                            <div class="activity-body">
                                <strong><?= e($act['first_name'] . ' ' . $act['last_name']) ?></strong>
                                <span>
                                    <?= e($act['title'] ?? 'Offre non précisée') ?>
                                    · <span class="status-label-<?= e($actStatus) ?>" style="font-weight:600"><?= e($statusLabels[$actStatus] ?? $actStatus) ?></span>
                                    · <?= e(format_datetime($act['applied_at'], 'd/m/Y')) ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </article>
                <?php endif; ?>

                <!-- Quick links -->
                <article class="surface-card surface-card-tight">
                    <div class="surface-head">
                        <div>
                            <h2>Raccourcis</h2>
                            <p class="surface-subtitle">Actions fréquentes du recruteur.</p>
                        </div>
                    </div>
                    <div class="quick-link-stack">
                        <a href="/views/poster-offre.php" class="quick-link-card">
                            <strong><i class="fas fa-plus-circle" style="color:#1d4ed8;margin-right:7px"></i>Publier une offre</strong>
                            <span>Formulaire complet relié à la base</span>
                        </a>
                        <a href="/views/profil-entreprise.php" class="quick-link-card">
                            <strong><i class="fas fa-id-badge" style="color:#7c3aed;margin-right:7px"></i>Profil entreprise</strong>
                            <span>Logo, description, contacts et réseaux</span>
                        </a>
                        <a href="/index.php" class="quick-link-card">
                            <strong><i class="fas fa-home" style="color:#15803d;margin-right:7px"></i>Voir le site</strong>
                            <span>Page d'accueil JobHub</span>
                        </a>
                    </div>
                </article>

            </aside>
        </div>

        <!-- CANDIDATE CARDS -->
        <div class="section-divider">
            <div>
                <h2>Candidatures reçues</h2>
                <p>Pilote les réponses et fais avancer le pipeline sans quitter cette page.</p>
            </div>
            <?php if ($applications): ?>
                <span style="font-size:13px;color:var(--text-light);white-space:nowrap"><?= count($applications) ?> candidature<?= count($applications) !== 1 ? 's' : '' ?></span>
            <?php endif; ?>
        </div>

        <?php if (!$applications): ?>
            <div class="empty-state-enhanced">
                <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                <h3>Pas encore de candidatures</h3>
                <p>Dès qu'un candidat postule, il apparaîtra ici avec son profil et son statut.</p>
                <a href="/views/poster-offre.php" class="btn btn-primary">
                    <i class="fas fa-plus" style="margin-right:6px;"></i>Publier une offre
                </a>
            </div>
        <?php else: ?>
            <div class="application-list">
                <?php foreach ($applications as $application):
                    $candidateName     = trim($application['first_name'] . ' ' . $application['last_name']);
                    $candidateParts    = explode(' ', $candidateName);
                    $candidateInitials = mb_strtoupper(
                        mb_substr($candidateParts[0], 0, 1) .
                        (isset($candidateParts[1]) ? mb_substr($candidateParts[1], 0, 1) : '')
                    );
                    $appStatus         = $application['status'] ?? 'submitted';
                    $coverPreview      = '';
                    if (!empty($application['cover_letter'])) {
                        $coverPreview = mb_substr(strip_tags($application['cover_letter']), 0, 120);
                        if (mb_strlen($application['cover_letter']) > 120) $coverPreview .= '…';
                    }
                ?>
                <article class="candidate-card">

                    <div class="candidate-card-head">
                        <div class="candidate-avatar"><?= e($candidateInitials) ?></div>
                        <div class="candidate-identity">
                            <h3><?= e($candidateName) ?></h3>
                            <p><?= e($application['headline'] ?: 'Candidat') ?> · <?= e($application['title'] ?? 'Offre') ?></p>
                        </div>
                        <span class="status-pill status-<?= e($appStatus) ?>">
                            <?= e($statusLabels[$appStatus] ?? $appStatus) ?>
                        </span>
                    </div>

                    <div class="candidate-meta-row">
                        <?php if (!empty($application['email'])): ?>
                            <span><i class="fas fa-envelope"></i> <?= e($application['email']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($application['phone'])): ?>
                            <span><i class="fas fa-phone"></i> <?= e($application['phone']) ?></span>
                        <?php endif; ?>
                        <span><i class="far fa-clock"></i> <?= e(format_datetime($application['applied_at'], 'd/m/Y \à H:i')) ?></span>
                    </div>

                    <?php if ($coverPreview): ?>
                        <div class="candidate-cover">
                            <strong>Lettre de motivation</strong>
                            <?= e($coverPreview) ?>
                        </div>
                    <?php endif; ?>

                    <div class="candidate-actions-row">
                        <a href="/views/profil-candidat.php?id=<?= (int) $application['user_id'] ?>" class="candidate-action-link">
                            <i class="fas fa-user"></i> Voir profil
                        </a>
                        <a href="mailto:<?= e($application['email']) ?>" class="candidate-action-link">
                            <i class="fas fa-paper-plane"></i> Contacter
                        </a>

                        <form method="post" class="inline-status-form compact">
                            <input type="hidden" name="_csrf"          value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="application_id" value="<?= (int) $application['id'] ?>">
                            <select name="status">
                                <option value="submitted" <?= $appStatus === 'submitted' ? 'selected' : '' ?>>Soumise</option>
                                <option value="reviewing" <?= $appStatus === 'reviewing' ? 'selected' : '' ?>>En revue</option>
                                <option value="interview" <?= $appStatus === 'interview' ? 'selected' : '' ?>>Entretien</option>
                                <option value="accepted"  <?= $appStatus === 'accepted'  ? 'selected' : '' ?>>Acceptée</option>
                                <option value="rejected"  <?= $appStatus === 'rejected'  ? 'selected' : '' ?>>Refusée</option>
                            </select>
                            <button type="submit" class="btn btn-outline" style="padding:9px 14px;font-size:13px">
                                <i class="fas fa-save" style="margin-right:5px"></i>Mettre à jour
                            </button>
                        </form>
                    </div>

                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
