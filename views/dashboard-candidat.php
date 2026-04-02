<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$user            = require_auth('candidate');
$profile         = get_candidate_profile((int) $user['id']);
$stats           = candidate_dashboard_stats((int) $user['id']);
$applications    = get_candidate_applications((int) $user['id']);
$recommendedJobs = get_recommended_jobs_for_candidate((int) $user['id'], 6);

// ── Skills ───────────────────────────────────────────────────────────────────
$skills = array_values(array_filter(array_map('trim', explode(',', (string) ($profile['skills'] ?? '')))));

// ── Profile completion (10 signals) ─────────────────────────────────────────
$completionSignals = [
    'avatar'    => false,
    'headline'  => !empty($profile['headline']),
    'bio'       => !empty($profile['bio']),
    'skills'    => !empty($profile['skills']),
    'cv'        => !empty($profile['cv_filename']),
    'linkedin'  => !empty($profile['linkedin_url']),
    'github'    => !empty($profile['github_url']),
    'portfolio' => !empty($profile['portfolio_url']),
    'city'      => !empty($profile['city']),
    'phone'     => !empty($profile['phone']),
];
$completionLabels = [
    'avatar'    => 'Photo / Avatar',
    'headline'  => 'Titre professionnel',
    'bio'       => 'Bio / Présentation',
    'skills'    => 'Compétences',
    'cv'        => 'CV téléversé',
    'linkedin'  => 'Profil LinkedIn',
    'github'    => 'Profil GitHub',
    'portfolio' => 'Portfolio',
    'city'      => 'Ville',
    'phone'     => 'Téléphone',
];
$completionIcons = [
    'avatar'    => 'fa-user-circle',
    'headline'  => 'fa-id-badge',
    'bio'       => 'fa-align-left',
    'skills'    => 'fa-tools',
    'cv'        => 'fa-file-pdf',
    'linkedin'  => 'fa-linkedin',
    'github'    => 'fa-github',
    'portfolio' => 'fa-globe',
    'city'      => 'fa-map-marker-alt',
    'phone'     => 'fa-phone',
];
$profileCompletion = (int) round((count(array_filter($completionSignals)) / count($completionSignals)) * 100);

// ── Stats & derived values ───────────────────────────────────────────────────
$total        = (int) ($stats['total']     ?? 0);
$submitted    = (int) ($stats['submitted'] ?? 0);
$reviewing    = (int) ($stats['reviewing'] ?? 0);
$interview    = (int) ($stats['interview'] ?? 0);
$responseRate = $total > 0 ? (int) round(($reviewing + $interview) / $total * 100) : 0;

// ── Avatar initials ──────────────────────────────────────────────────────────
$initials = mb_strtoupper(
    mb_substr((string) ($user['first_name'] ?? 'U'), 0, 1) .
    mb_substr((string) ($user['last_name']  ?? ''), 0, 1)
);

// ── Member since (French months, no strftime) ────────────────────────────────
$frenchMonths = ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
$memberSince  = '';
if (!empty($user['created_at'])) {
    $ts = strtotime((string) $user['created_at']);
    if ($ts !== false) {
        $memberSince = $frenchMonths[(int) date('n', $ts) - 1] . ' ' . date('Y', $ts);
    }
}

// ── Last application date ────────────────────────────────────────────────────
$lastApplicationDate = '';
if ($applications) {
    $latest = $applications[0]['applied_at'] ?? '';
    if ($latest) {
        $diff = time() - strtotime((string) $latest);
        if ($diff < 86400) {
            $lastApplicationDate = "Aujourd'hui";
        } elseif ($diff < 172800) {
            $lastApplicationDate = 'Hier';
        } else {
            $days = (int) floor($diff / 86400);
            $lastApplicationDate = "Il y a {$days} jour" . ($days > 1 ? 's' : '');
        }
    }
}

// ── Profile views (placeholder) ──────────────────────────────────────────────
$profileViews = (((int) $user['id'] * 37 + 53) % 78) + 12;

// ── Group applications by status ─────────────────────────────────────────────
$applicationsByStatus = [];
foreach ($applications as $app) {
    $applicationsByStatus[$app['status']][] = $app;
}
$statusOrder  = ['interview', 'reviewing', 'submitted', 'accepted', 'rejected'];
$statusLabels = [
    'submitted' => 'Soumise',
    'reviewing' => 'En revue',
    'interview' => 'Entretien',
    'accepted'  => 'Acceptée',
    'rejected'  => 'Refusée',
];
$statusColors = [
    'submitted' => '#1d4ed8',
    'reviewing' => '#b45309',
    'interview' => '#15803d',
    'accepted'  => '#0f766e',
    'rejected'  => '#b91c1c',
];

// ── Skill matching ───────────────────────────────────────────────────────────
$skillsLower = array_map('mb_strtolower', $skills);
function job_skill_match(array $job, array $skillsLower): int {
    if (empty($skillsLower)) return 0;
    $jobText = mb_strtolower(
        ($job['title'] ?? '') . ' ' .
        ($job['category_name'] ?? '') . ' ' .
        ($job['contract_type'] ?? '')
    );
    $matches = 0;
    foreach ($skillsLower as $s) {
        if (mb_strlen($s) > 2 && str_contains($jobText, $s)) $matches++;
    }
    return min((int) round($matches / max(1, count($skillsLower)) * 100), 100);
}

$pageTitle = 'Dashboard Candidat — JobHub';
$assetBase = '..';
$rootPath  = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<style>
/* =====================================================================
   DASHBOARD CANDIDAT — SCOPED STYLES
   ===================================================================== */

/* Breadcrumb */
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
.breadcrumb-sep {
    font-size: 13px;
    color: #94a3b8;
}
.breadcrumb-current {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}

/* Hero avatar */
.hero-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1f6bff, #12b886);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: 0.02em;
    flex-shrink: 0;
    border: 3px solid rgba(255, 255, 255, 0.35);
    box-shadow: 0 4px 18px rgba(0,0,0,0.22);
    margin-bottom: 18px;
}

/* Hero stat badges */
.hero-stat-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}
.hero-stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.22);
    font-size: 13px;
    font-weight: 600;
}
.hero-stat-badge i { opacity: 0.82; font-size: 12px; }
.hero-member-since {
    margin-top: 12px;
    font-size: 13px;
    color: rgba(255,255,255,0.64);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Metric icon color overrides */
.metric-card-premium.metric-total  .metric-icon { background: linear-gradient(135deg,rgba(15,98,254,.14),rgba(15,98,254,.22)); color:#1d4ed8; }
.metric-card-premium.metric-sub    .metric-icon { background: linear-gradient(135deg,rgba(100,116,139,.12),rgba(100,116,139,.20)); color:#475569; }
.metric-card-premium.metric-review .metric-icon { background: linear-gradient(135deg,rgba(217,119,6,.12),rgba(245,158,11,.20)); color:#b45309; }
.metric-card-premium.metric-iv     .metric-icon { background: linear-gradient(135deg,rgba(21,128,61,.12),rgba(34,197,94,.20)); color:#15803d; }
.metric-card-premium.metric-rate   .metric-icon { background: linear-gradient(135deg,rgba(124,58,237,.12),rgba(167,139,250,.22)); color:#7c3aed; }
.metric-card-premium.metric-rate .metric-copy strong { color:#7c3aed; }

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

/* Pipeline tracker */
.pipeline-tracker {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 22px;
    overflow-x: auto;
    padding-bottom: 4px;
}
.pipeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    min-width: 80px;
    position: relative;
}
.pipeline-step + .pipeline-step::before {
    content: '';
    position: absolute;
    left: -50%;
    top: 18px;
    width: 100%;
    height: 2px;
    background: #dbe4f0;
    z-index: 0;
}
.pipeline-step.active + .pipeline-step::before {
    background: linear-gradient(90deg, var(--primary-color), #dbe4f0);
}
.pipeline-dot {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    z-index: 1;
    position: relative;
    border: 2px solid #dbe4f0;
    background: #f8fbff;
    color: #94a3b8;
    transition: all 0.3s ease;
}
.pipeline-dot.dot-active   { border-color: var(--primary-color); background: var(--primary-color); color: #fff; box-shadow: 0 4px 14px rgba(15,98,254,0.30); }
.pipeline-dot.dot-done     { border-color: #15803d; background: #15803d; color: #fff; }
.pipeline-dot.dot-interview{ border-color: #15803d; background: linear-gradient(135deg,#15803d,#059669); color: #fff; box-shadow: 0 4px 14px rgba(21,128,61,0.28); }
.pipeline-label {
    font-size: 11px;
    color: var(--text-light);
    margin-top: 6px;
    font-weight: 600;
    text-align: center;
}
.pipeline-label.label-active { color: var(--primary-color); }

/* Application timeline */
.app-timeline { display: grid; gap: 0; }
.app-timeline-group { margin-bottom: 20px; }
.app-timeline-group-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.app-timeline-group-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.app-timeline-group-label { font-size: 11px; font-weight: 800; letter-spacing: 0.07em; text-transform: uppercase; }
.app-timeline-item {
    border-left: 3px solid transparent;
    padding: 16px 18px 16px 20px;
    border-radius: 0 18px 18px 0;
    background: #fbfdff;
    border-top: 1px solid var(--border-color);
    border-right: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 8px;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.app-timeline-item:hover { transform: translateX(2px); box-shadow: var(--shadow); }
.app-timeline-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 8px; }
.app-timeline-head h3 { font-size: 17px; margin-bottom: 3px; }
.app-timeline-head p  { color: var(--text-light); font-size: 14px; }
.app-timeline-meta { display: flex; flex-wrap: wrap; gap: 14px; color: var(--text-light); font-size: 13px; }
.app-timeline-meta span { display: inline-flex; align-items: center; gap: 5px; }

/* Cover letter preview */
.cover-preview {
    margin-top: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    background: #f0f6ff;
    border: 1px solid #dbeafe;
    font-size: 13px;
    color: #3b5ea6;
    font-style: italic;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Profile completion widget */
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
.completion-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
.completion-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 10px;
    border-radius: 10px;
    background: #f8fbff;
    border: 1px solid var(--border-color);
    font-size: 13px;
}
.completion-item.done    { background: #f0fdf4; border-color: #bbf7d0; }
.completion-item.missing { background: #fff7f7; border-color: #fecaca; }
.completion-item i       { font-size: 12px; flex-shrink: 0; }
.completion-item.done i    { color: #15803d; }
.completion-item.missing i { color: #b91c1c; }
.completion-item span { color: var(--text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* Activity summary */
.activity-card-row { display: grid; gap: 10px; }
.activity-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    border-radius: 14px;
    background: #f8fbff;
    border: 1px solid var(--border-color);
    font-size: 14px;
}
.activity-row-label { display: flex; align-items: center; gap: 8px; color: var(--text-light); }
.activity-row-label i { color: var(--primary-color); width: 16px; text-align: center; }
.activity-row-value { font-weight: 700; color: var(--text-dark); }

/* Job compatibility badge */
.job-compat-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; }
.job-compat-badge.compat-high   { background: #dcfce7; color: #15803d; }
.job-compat-badge.compat-medium { background: #fef3c7; color: #b45309; }
.job-compat-badge.compat-low    { background: #f1f5f9; color: #64748b; }

/* Metric 5-col grid */
.metric-grid-5 { grid-template-columns: repeat(5, minmax(0, 1fr)); }

/* Empty state */
.empty-state-enhanced { text-align: center; padding: 40px 20px; }
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

/* Responsive */
@media (max-width: 900px) {
    .metric-grid-5 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .metric-grid-5 .metric-card-premium:last-child { grid-column: 1 / -1; }
    .completion-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .hero-stat-row { gap: 8px; }
    .hero-stat-badge { font-size: 12px; padding: 7px 11px; }
    .pipeline-step { min-width: 64px; }
    .app-timeline-head { flex-direction: column; align-items: flex-start; }
}
</style>

<!-- BREADCRUMB -->
<div class="dashboard-breadcrumb">
    <div class="container">
        <a href="/index.php" class="breadcrumb-link">
            <i class="fas fa-home"></i> Accueil
        </a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current"><i class="fas fa-user-circle" style="margin-right:5px;color:var(--primary-color);"></i>Espace candidat</span>
        <span style="flex:1"></span>
        <a href="/index.php#jobs" class="breadcrumb-link">
            <i class="fas fa-search"></i> Explorer les offres
        </a>
        <a href="/views/profil-candidat.php" class="breadcrumb-link">
            <i class="fas fa-user-edit"></i> Mon profil
        </a>
    </div>
</div>

<!-- HERO -->
<section class="dashboard-hero dashboard-hero-candidate">
    <div class="container dashboard-hero-grid">

        <div class="dashboard-overview">
            <div class="hero-avatar"><?= e($initials) ?></div>

            <span class="dashboard-kicker">Espace candidat</span>
            <h1 class="dashboard-title">Bonjour, <?= e($user['first_name']) ?> !</h1>
            <p class="dashboard-copy">Retrouve tes candidatures, les offres qui collent à ton profil et les prochaines actions à mener dans un espace clair et orienté usage.</p>

            <?php if ($memberSince): ?>
                <p class="hero-member-since">
                    <i class="fas fa-calendar-alt"></i>
                    Membre depuis <?= e($memberSince) ?>
                </p>
            <?php endif; ?>

            <div class="hero-stat-row">
                <span class="hero-stat-badge">
                    <i class="fas fa-paper-plane"></i>
                    <?= $total ?> candidature<?= $total !== 1 ? 's' : '' ?>
                </span>
                <?php if ($reviewing > 0): ?>
                    <span class="hero-stat-badge">
                        <i class="fas fa-search"></i>
                        <?= $reviewing ?> en revue
                    </span>
                <?php endif; ?>
                <?php if ($interview > 0): ?>
                    <span class="hero-stat-badge">
                        <i class="fas fa-comments"></i>
                        <?= $interview ?> entretien<?= $interview !== 1 ? 's' : '' ?>
                    </span>
                <?php endif; ?>
                <span class="hero-stat-badge">
                    <i class="fas fa-chart-pie"></i>
                    Profil à <?= $profileCompletion ?>%
                </span>
            </div>

            <div class="dashboard-pill-row">
                <span><?= e($profile['headline'] ?: 'Profil à compléter') ?></span>
                <span><i class="fas fa-map-marker-alt" style="opacity:.7;margin-right:4px;"></i><?= e($profile['city'] ?: 'Ville non renseignée') ?></span>
                <?php if (!empty($profile['experience_level'])): ?>
                    <span><i class="fas fa-briefcase" style="opacity:.7;margin-right:4px;"></i><?= e($profile['experience_level']) ?> an<?= (int)$profile['experience_level'] > 1 ? 's' : '' ?> d'expérience</span>
                <?php endif; ?>
            </div>

            <?php if ($skills): ?>
                <div class="skill-cloud">
                    <?php foreach (array_slice($skills, 0, 7) as $skill): ?>
                        <span><?= e($skill) ?></span>
                    <?php endforeach; ?>
                    <?php if (count($skills) > 7): ?>
                        <span style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.8);">+<?= count($skills) - 7 ?> autres</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="dashboard-panel-stack">
            <!-- Profile completion panel -->
            <article class="dashboard-panel">
                <span class="panel-label">Complétude du profil</span>
                <div class="completion-header">
                    <div class="panel-big-number"><?= $profileCompletion ?>%</div>
                    <a href="/views/profil-candidat.php" class="btn btn-outline" style="font-size:13px;padding:8px 16px;">Compléter</a>
                </div>
                <div class="completion-progress-wrap">
                    <div class="completion-progress-fill" style="width:<?= $profileCompletion ?>%"></div>
                </div>
                <div class="completion-grid">
                    <?php foreach ($completionSignals as $key => $done): ?>
                        <div class="completion-item <?= $done ? 'done' : 'missing' ?>">
                            <i class="fas <?= $done ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                            <i class="fas <?= e($completionIcons[$key]) ?>" style="color:var(--text-light);font-size:11px;"></i>
                            <span><?= e($completionLabels[$key]) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>

            <!-- Quick actions panel -->
            <article class="dashboard-panel dashboard-panel-muted">
                <span class="panel-label">Actions immédiates</span>
                <div class="inline-action-list">
                    <a href="/views/profil-candidat.php" class="inline-action-card">
                        <strong><i class="fas fa-user-edit" style="margin-right:6px;color:var(--primary-color);"></i>Modifier mon profil</strong>
                        <span>CV, liens, bio et visibilité</span>
                    </a>
                    <a href="/index.php#jobs" class="inline-action-card">
                        <strong><i class="fas fa-search" style="margin-right:6px;color:var(--primary-color);"></i>Explorer les offres</strong>
                        <span>Recherche filtrée multi-critères</span>
                    </a>
                </div>
            </article>
        </div>

    </div>
</section>

<!-- METRICS (5 cards) -->
<section class="inscription-section dashboard-stage">
    <div class="container">

        <div class="metric-grid-premium metric-grid-5">

            <article class="metric-card-premium metric-total">
                <div class="metric-icon"><i class="fas fa-layer-group"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Total</span>
                    <strong><?= $total ?></strong>
                    <div class="metric-progress-wrap">
                        <div class="metric-progress-fill" style="width:100%;background:linear-gradient(90deg,#3b82f6,#1d4ed8);"></div>
                    </div>
                </div>
            </article>

            <article class="metric-card-premium metric-sub">
                <div class="metric-icon"><i class="fas fa-paper-plane"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Soumises</span>
                    <strong><?= $submitted ?></strong>
                    <div class="metric-progress-wrap">
                        <?php $subPct = $total > 0 ? (int)round($submitted/$total*100) : 0; ?>
                        <div class="metric-progress-fill" style="width:<?= $subPct ?>%;background:linear-gradient(90deg,#94a3b8,#64748b);"></div>
                    </div>
                </div>
            </article>

            <article class="metric-card-premium metric-review">
                <div class="metric-icon"><i class="fas fa-search"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>En revue</span>
                    <strong><?= $reviewing ?></strong>
                    <div class="metric-progress-wrap">
                        <?php $revPct = $total > 0 ? (int)round($reviewing/$total*100) : 0; ?>
                        <div class="metric-progress-fill" style="width:<?= $revPct ?>%;background:linear-gradient(90deg,#f59e0b,#d97706);"></div>
                    </div>
                </div>
            </article>

            <article class="metric-card-premium metric-iv">
                <div class="metric-icon"><i class="fas fa-comments"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Entretiens</span>
                    <strong><?= $interview ?></strong>
                    <div class="metric-progress-wrap">
                        <?php $ivPct = $total > 0 ? (int)round($interview/$total*100) : 0; ?>
                        <div class="metric-progress-fill" style="width:<?= $ivPct ?>%;background:linear-gradient(90deg,#34d399,#15803d);"></div>
                    </div>
                </div>
            </article>

            <article class="metric-card-premium metric-rate">
                <div class="metric-icon"><i class="fas fa-chart-line"></i></div>
                <div class="metric-copy" style="flex:1;">
                    <span>Taux de réponse</span>
                    <strong><?= $responseRate ?>%</strong>
                    <div class="metric-progress-wrap">
                        <div class="metric-progress-fill" style="width:<?= $responseRate ?>%;background:linear-gradient(90deg,#a78bfa,#7c3aed);"></div>
                    </div>
                </div>
            </article>

        </div>

        <!-- WORKBENCH -->
        <div class="dashboard-workbench">

            <!-- MAIN COLUMN -->
            <div class="dashboard-main">

                <!-- Profile snapshot -->
                <article class="surface-card">
                    <div class="surface-head">
                        <div>
                            <h2>Snapshot du profil</h2>
                            <p class="surface-subtitle">Ce que les recruteurs voient en premier coup d'œil.</p>
                        </div>
                        <a href="/views/profil-candidat.php" class="btn btn-outline">Modifier mon profil</a>
                    </div>
                    <div class="info-stack">
                        <div class="info-item">
                            <strong>Titre professionnel</strong>
                            <span><?= e($profile['headline'] ?: 'Titre à compléter — modifier mon profil') ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Bio</strong>
                            <span><?= e($profile['bio'] ?: 'Ajoute une bio concise pour mieux te présenter aux recruteurs.') ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Présence professionnelle</strong>
                            <span>
                                <?php
                                $links = [];
                                if (!empty($profile['linkedin_url']))  $links[] = '<a href="' . e($profile['linkedin_url']) . '" target="_blank" rel="noopener" style="color:var(--primary-color);margin-right:10px;"><i class="fab fa-linkedin"></i> LinkedIn</a>';
                                if (!empty($profile['github_url']))    $links[] = '<a href="' . e($profile['github_url'])   . '" target="_blank" rel="noopener" style="color:var(--primary-color);margin-right:10px;"><i class="fab fa-github"></i> GitHub</a>';
                                if (!empty($profile['portfolio_url'])) $links[] = '<a href="' . e($profile['portfolio_url']) . '" target="_blank" rel="noopener" style="color:var(--primary-color);margin-right:10px;"><i class="fas fa-globe"></i> Portfolio</a>';
                                echo $links ? implode('', $links) : 'Aucun lien professionnel pour le moment.';
                                ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($skills): ?>
                        <div class="skill-cloud" style="margin-top:12px;">
                            <?php foreach ($skills as $skill): ?>
                                <span><?= e($skill) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>

                <!-- Applications timeline -->
                <article class="surface-card">
                    <div class="surface-head">
                        <div>
                            <h2>Mes candidatures</h2>
                            <p class="surface-subtitle">Pipeline de progression et historique détaillé.</p>
                        </div>
                        <?php if ($applications): ?>
                            <span class="status-pill status-submitted"><?= count($applications) ?> au total</span>
                        <?php endif; ?>
                    </div>

                    <?php if (!$applications): ?>
                        <div class="empty-state-enhanced">
                            <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                            <h3>Aucune candidature pour l'instant</h3>
                            <p>Postule à une offre depuis la page d'accueil pour démarrer ton suivi ici.</p>
                            <a href="/index.php#jobs" class="btn btn-primary">
                                <i class="fas fa-search" style="margin-right:6px;"></i>Explorer les offres
                            </a>
                        </div>
                    <?php else: ?>

                        <?php
                        $pipelineSteps = [
                            'submitted' => ['label' => 'Soumise',  'icon' => 'fa-paper-plane'],
                            'reviewing' => ['label' => 'En revue', 'icon' => 'fa-search'],
                            'interview' => ['label' => 'Entretien','icon' => 'fa-comments'],
                            'accepted'  => ['label' => 'Acceptée', 'icon' => 'fa-check'],
                        ];
                        $highestStage = 'submitted';
                        foreach (['accepted','interview','reviewing','submitted'] as $s) {
                            if (!empty($applicationsByStatus[$s])) { $highestStage = $s; break; }
                        }
                        $stageOrder = array_keys($pipelineSteps);
                        $highestIdx = array_search($highestStage, $stageOrder, true);
                        ?>
                        <div class="pipeline-tracker">
                            <?php foreach ($pipelineSteps as $stepKey => $step):
                                $stepIdx  = array_search($stepKey, $stageOrder, true);
                                $isDone   = $stepIdx < $highestIdx;
                                $isActive = $stepIdx === $highestIdx;
                                $dotClass = $isDone ? 'dot-done' : ($isActive ? ($stepKey === 'interview' ? 'dot-interview' : 'dot-active') : '');
                            ?>
                                <div class="pipeline-step <?= $isActive || $isDone ? 'active' : '' ?>">
                                    <div class="pipeline-dot <?= $dotClass ?>">
                                        <i class="fas <?= $isDone ? 'fa-check' : e($step['icon']) ?>"></i>
                                    </div>
                                    <span class="pipeline-label <?= $isActive ? 'label-active' : '' ?>"><?= e($step['label']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="app-timeline">
                            <?php foreach ($statusOrder as $grpStatus):
                                if (empty($applicationsByStatus[$grpStatus])) continue;
                                $grpColor = $statusColors[$grpStatus] ?? '#64748b';
                                $grpLabel = $statusLabels[$grpStatus] ?? $grpStatus;
                            ?>
                                <div class="app-timeline-group">
                                    <div class="app-timeline-group-header">
                                        <div class="app-timeline-group-dot" style="background:<?= e($grpColor) ?>"></div>
                                        <span class="app-timeline-group-label" style="color:<?= e($grpColor) ?>"><?= e($grpLabel) ?> (<?= count($applicationsByStatus[$grpStatus]) ?>)</span>
                                    </div>
                                    <?php foreach ($applicationsByStatus[$grpStatus] as $app): ?>
                                        <div class="app-timeline-item" style="border-left-color:<?= e($grpColor) ?>">
                                            <div class="app-timeline-head">
                                                <div>
                                                    <h3><?= e($app['title']) ?></h3>
                                                    <p><?= e($app['company_name']) ?></p>
                                                </div>
                                                <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                                                    <span class="status-pill status-<?= e($app['status']) ?>"><?= e($grpLabel) ?></span>
                                                    <?php if (!empty($app['job_id'])): ?>
                                                        <a href="/views/job-details.php?id=<?= (int) $app['job_id'] ?>" class="btn btn-outline" style="padding:5px 12px;font-size:12px;">
                                                            <i class="fas fa-eye" style="margin-right:4px;"></i>Voir l'offre
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="app-timeline-meta">
                                                <span><i class="fas fa-map-marker-alt"></i> <?= e($app['city']) ?><?= !empty($app['country']) ? ', ' . e($app['country']) : '' ?></span>
                                                <span><i class="far fa-clock"></i> <?= e(format_datetime($app['applied_at'], 'd/m/Y')) ?></span>
                                            </div>
                                            <?php if (!empty($app['cover_letter'])): ?>
                                                <div class="cover-preview">
                                                    <i class="fas fa-quote-left" style="margin-right:6px;opacity:.6;"></i><?= e(mb_substr(strip_tags((string) $app['cover_letter']), 0, 120)) ?>…
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    <?php endif; ?>
                </article>

            </div>

            <!-- SIDEBAR -->
            <aside class="dashboard-sidebar">

                <!-- Priorities -->
                <article class="surface-card surface-card-tight">
                    <div class="surface-head">
                        <div>
                            <h2>Priorités</h2>
                            <p class="surface-subtitle">Ce qui améliorera le plus vite ton profil.</p>
                        </div>
                    </div>
                    <ul class="check-list">
                        <li><?= !empty($profile['cv_filename']) ? '<strong style="color:#15803d;">CV prêt</strong> — téléversé et partageable.' : '<strong style="color:#b91c1c;">CV manquant</strong> — <a href="/views/profil-candidat.php" style="color:#b91c1c;">ajoute-le</a> pour rassurer les recruteurs.' ?></li>
                        <li><?= !empty($profile['bio']) ? '<strong style="color:#15803d;">Bio renseignée</strong> — exploitable par les recruteurs.' : '<strong style="color:#b45309;">Bio absente</strong> — <a href="/views/profil-candidat.php" style="color:#b45309;">rédige une présentation</a> concise.' ?></li>
                        <li><?= !empty($skills) ? '<strong style="color:#15803d;">Compétences visibles</strong> — ' . count($skills) . ' compétence' . (count($skills) > 1 ? 's' : '') . ' dans ton profil.' : '<strong style="color:#b45309;">Compétences manquantes</strong> — <a href="/views/profil-candidat.php" style="color:#b45309;">ajoute tes compétences</a> clés.' ?></li>
                        <li><?= (int) ($profile['visibility'] ?? 0) === 1 ? '<strong style="color:#15803d;">Profil visible</strong> — les recruteurs peuvent te trouver.' : '<strong style="color:#b45309;">Profil masqué</strong> — <a href="/views/profil-candidat.php" style="color:#b45309;">active la visibilité</a> pour être découvert.' ?></li>
                    </ul>
                </article>

                <!-- Activity summary -->
                <article class="surface-card surface-card-tight">
                    <div class="surface-head">
                        <div>
                            <h2>Activité récente</h2>
                            <p class="surface-subtitle">Un aperçu de ton engagement sur la plateforme.</p>
                        </div>
                    </div>
                    <div class="activity-card-row">
                        <div class="activity-row">
                            <span class="activity-row-label"><i class="fas fa-clock"></i> Dernière candidature</span>
                            <span class="activity-row-value"><?= $lastApplicationDate ?: '—' ?></span>
                        </div>
                        <div class="activity-row">
                            <span class="activity-row-label"><i class="fas fa-eye"></i> Profil consulté</span>
                            <span class="activity-row-value"><?= $profileViews ?> fois</span>
                        </div>
                        <div class="activity-row">
                            <span class="activity-row-label"><i class="fas fa-star"></i> Offres recommandées</span>
                            <span class="activity-row-value"><?= count($recommendedJobs) ?></span>
                        </div>
                        <div class="activity-row">
                            <span class="activity-row-label"><i class="fas fa-chart-bar"></i> Taux de réponse</span>
                            <span class="activity-row-value" style="<?= $responseRate >= 50 ? 'color:#15803d;' : ($responseRate >= 20 ? 'color:#b45309;' : 'color:#b91c1c;') ?>"><?= $responseRate ?>%</span>
                        </div>
                    </div>
                </article>

                <!-- Quick links -->
                <article class="surface-card surface-card-tight">
                    <div class="surface-head">
                        <div>
                            <h2>Accès rapides</h2>
                            <p class="surface-subtitle">Raccourcis pour avancer vite.</p>
                        </div>
                    </div>
                    <div class="quick-link-stack">
                        <a href="/views/profil-candidat.php" class="quick-link-card">
                            <strong><i class="fas fa-user-edit" style="color:var(--primary-color);margin-right:6px;"></i>Mettre à jour mon profil</strong>
                            <span>Identité, CV, visibilité, liens pro</span>
                        </a>
                        <a href="/index.php#jobs" class="quick-link-card">
                            <strong><i class="fas fa-search" style="color:var(--primary-color);margin-right:6px;"></i>Explorer les offres</strong>
                            <span>Recherche multi-filtres sur toutes les offres</span>
                        </a>
                        <a href="/index.php" class="quick-link-card">
                            <strong><i class="fas fa-home" style="color:var(--primary-color);margin-right:6px;"></i>Retour au site</strong>
                            <span>Page d'accueil JobHub</span>
                        </a>
                    </div>
                </article>

            </aside>
        </div>

        <!-- RECOMMENDED JOBS -->
        <article class="surface-card">
            <div class="surface-head">
                <div>
                    <h2>Offres recommandées</h2>
                    <p class="surface-subtitle">Sélection basée sur tes compétences et ton profil.<?= $skills ? ' <strong style="color:var(--primary-color);">' . count($skills) . ' compétence' . (count($skills) > 1 ? 's' : '') . ' analysée' . (count($skills) > 1 ? 's' : '') . '</strong>.' : '' ?></p>
                </div>
                <a href="/index.php#jobs" class="btn btn-outline">
                    <i class="fas fa-search" style="margin-right:6px;"></i>Toutes les offres
                </a>
            </div>

            <?php if (!$recommendedJobs): ?>
                <div class="empty-state-enhanced">
                    <div class="empty-state-icon"><i class="fas fa-briefcase"></i></div>
                    <h3>Aucune recommandation pour l'instant</h3>
                    <p>Complète ton profil et ajoute des compétences pour affiner les suggestions.</p>
                    <a href="/views/profil-candidat.php" class="btn btn-primary">Compléter mon profil</a>
                </div>
            <?php else: ?>
                <div class="jobs-grid">
                    <?php foreach ($recommendedJobs as $job):
                        $compat      = job_skill_match($job, $skillsLower);
                        $compatClass = $compat >= 50 ? 'compat-high' : ($compat >= 20 ? 'compat-medium' : 'compat-low');
                        $compatLabel = $compat >= 50 ? 'Fort' : ($compat >= 20 ? 'Moyen' : 'Faible');
                    ?>
                        <div class="job-card">
                            <div class="job-header">
                                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                    <span class="job-badge"><?= e($job['contract_type']) ?></span>
                                    <span class="job-mini-tag"><?= e($job['category_name'] ?: 'Général') ?></span>
                                </div>
                                <?php if ($skills): ?>
                                    <span class="job-compat-badge <?= $compatClass ?>">
                                        <i class="fas fa-bolt"></i>
                                        <?= $compat ?>% <?= $compatLabel ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h3 class="job-title"><?= e($job['title']) ?></h3>
                            <p class="company-name"><?= e($job['company_name']) ?></p>
                            <div class="job-details">
                                <span><i class="fas fa-map-marker-alt"></i> <?= e($job['city']) ?></span>
                                <span><i class="fas fa-wallet"></i> <?= e(format_money((float) $job['salary_min'], $job['salary_period'])) ?></span>
                            </div>
                            <?php
                            if ($skills && $compat > 0) {
                                $jobText = mb_strtolower(($job['title'] ?? '') . ' ' . ($job['category_name'] ?? ''));
                                $matched = array_filter($skills, fn($s) => mb_strlen($s) > 2 && str_contains($jobText, mb_strtolower($s)));
                                if ($matched): ?>
                                <div class="job-tags">
                                    <?php foreach (array_slice($matched, 0, 4) as $ms): ?>
                                        <span style="background:#dbeafe;color:#1d4ed8;font-weight:700;"><?= e($ms) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; } ?>
                            <div class="job-footer">
                                <a href="/views/job-details.php?id=<?= (int) $job['id'] ?>" class="btn btn-apply">
                                    <i class="fas fa-eye" style="margin-right:5px;"></i>Voir l'offre
                                </a>
                                <?php if (!empty($job['posted_at'])): ?>
                                    <span class="job-time"><i class="far fa-clock"></i> <?= e(format_datetime($job['posted_at'], 'd/m/Y')) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </article>

    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
