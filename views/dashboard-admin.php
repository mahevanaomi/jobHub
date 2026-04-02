<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$user = require_auth('admin');

// ── POST action handlers ──────────────────────────────────────────────────────
if (is_post()) {
    verify_csrf($_POST['_csrf'] ?? null);

    $action = trim($_POST['action'] ?? '');
    $id     = (int) ($_POST['id'] ?? 0);

    try {
        switch ($action) {
            case 'delete_user':
                admin_delete_user($id);
                flash('success', 'Utilisateur supprimé avec succès.');
                break;

            case 'delete_job':
                admin_delete_job($id);
                flash('success', 'Offre supprimée avec succès.');
                break;

            case 'delete_message':
                admin_delete_message($id);
                flash('success', 'Message supprimé avec succès.');
                break;

            case 'update_app_status':
                $status = trim($_POST['status'] ?? '');
                admin_update_application_status($id, $status);
                flash('success', 'Statut de candidature mis à jour.');
                break;

            case 'update_job_status':
                $status = trim($_POST['status'] ?? '');
                admin_update_job_status($id, $status);
                flash('success', 'Statut de l\'offre mis à jour.');
                break;

            default:
                flash('error', 'Action non reconnue.');
        }
    } catch (Throwable $e) {
        flash('error', 'Une erreur est survenue : ' . $e->getMessage());
    }

    $redirectTab = match ($action) {
        'delete_user'       => 'users',
        'delete_job'        => 'jobs',
        'update_job_status' => 'jobs',
        'delete_message'    => 'messages',
        'update_app_status' => 'applications',
        default             => 'overview',
    };

    redirect('/views/dashboard-admin.php?tab=' . $redirectTab);
}

// ── Data loading ──────────────────────────────────────────────────────────────
$tab = in_array($_GET['tab'] ?? '', ['overview', 'users', 'jobs', 'applications', 'messages'], true)
    ? $_GET['tab']
    : 'overview';

$globalStats = admin_global_stats();

$usersStats        = $globalStats['users']        ?? [];
$jobsStats         = $globalStats['jobs']          ?? [];
$applicationsStats = $globalStats['applications']  ?? [];
$messagesTotal     = (int) ($globalStats['messages']   ?? 0);
$categoriesTotal   = (int) ($globalStats['categories'] ?? 0);

// Tab-specific data (only load what is needed)
$recentUsers    = $tab === 'overview' ? admin_recent_users(8)           : [];
$topCompanies   = $tab === 'overview' ? admin_top_companies(5)          : [];
$jobsByCategory = $tab === 'overview' ? admin_jobs_by_category()        : [];
$jobsByCity     = $tab === 'overview' ? admin_jobs_by_city(8)           : [];
$recentActivity = $tab === 'overview' ? admin_recent_activity(15)       : [];

$roleFilter   = trim($_GET['role']   ?? '');
$searchFilter = trim($_GET['search'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');

$allUsers        = $tab === 'users'        ? admin_all_users($roleFilter, $searchFilter)   : [];
$allJobs         = $tab === 'jobs'         ? admin_all_jobs($statusFilter, $searchFilter)  : [];
$allApplications = $tab === 'applications' ? admin_all_applications($statusFilter)         : [];
$allMessages     = $tab === 'messages'     ? admin_all_messages()                          : [];

// Pipeline summary for overview
$pipelineCounts = [
    'submitted' => (int) ($applicationsStats['submitted'] ?? 0),
    'reviewing' => (int) ($applicationsStats['reviewing'] ?? 0),
    'interview' => (int) ($applicationsStats['interview'] ?? 0),
    'accepted'  => (int) ($applicationsStats['accepted']  ?? 0),
    'rejected'  => (int) ($applicationsStats['rejected']  ?? 0),
];

// Max city jobs for bar chart scaling
$maxCityJobs = 1;
foreach ($jobsByCity as $cityRow) {
    if ((int) $cityRow['total'] > $maxCityJobs) $maxCityJobs = (int) $cityRow['total'];
}

// Member since (French months, no strftime)
$frenchMonths = ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
$memberSince  = '';
if (!empty($user['created_at'])) {
    $ts = strtotime((string) $user['created_at']);
    if ($ts !== false) $memberSince = $frenchMonths[(int) date('n', $ts) - 1] . ' ' . date('Y', $ts);
}

$pageTitle = 'Administration — JobHub';
$assetBase = '..';
$rootPath  = '/index.php';
$viewsPath = '.';

require __DIR__ . '/../app/views/header.php';
?>

<style>
/* =====================================================================
   ADMIN DASHBOARD — SCOPED STYLES
   Follows the same design language as dashboard-candidat.php
   ===================================================================== */

/* ── Breadcrumb ─────────────────────────────────────────────────────── */
.dashboard-breadcrumb {
    background: #1a1a2e;
    border-bottom: 1px solid rgba(233, 69, 96, 0.25);
    padding: 8px 0;
}
.dashboard-breadcrumb .container {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.breadcrumb-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.75);
    text-decoration: none;
    padding: 4px 11px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    transition: background 0.18s, color 0.18s;
    white-space: nowrap;
}
.breadcrumb-link:hover { background: rgba(233, 69, 96, 0.28); color: #fff; border-color: rgba(233, 69, 96, 0.4); }
.breadcrumb-link.active { background: rgba(233, 69, 96, 0.22); color: #fde8ec; border-color: rgba(233, 69, 96, 0.45); }
.breadcrumb-sep     { font-size: 12px; color: rgba(255,255,255,0.28); }
.breadcrumb-current { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.55); display:inline-flex;align-items:center;gap:5px; }

/* ── Hero ────────────────────────────────────────────────────────────── */
.dashboard-hero-admin {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 52%, #e94560 100%) !important;
}
.hero-admin-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e94560, #c2253e);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: 0.02em;
    flex-shrink: 0;
    border: 3px solid rgba(255, 255, 255, 0.30);
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.28);
    margin-bottom: 18px;
}
.hero-stat-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
}
.hero-stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 13px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.13);
    border: 1px solid rgba(255, 255, 255, 0.20);
    font-size: 13px;
    font-weight: 600;
}
.hero-stat-badge i { opacity: 0.80; font-size: 12px; }
.hero-member-since {
    margin-top: 11px;
    font-size: 13px;
    color: rgba(255, 255, 255, 0.58);
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* ── Metric grid 8 (2 rows × 4) ─────────────────────────────────────── */
.metric-grid-8 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}
@media (max-width: 900px) { .metric-grid-8 { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 540px) { .metric-grid-8 { grid-template-columns: 1fr 1fr; } }

/* Micro progress bar on metric cards */
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

/* ── Admin tab navigation ────────────────────────────────────────────── */
.admin-tabs {
    display: flex;
    gap: 0;
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: 2rem;
    overflow-x: auto;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}
.admin-tabs::-webkit-scrollbar { display: none; }
.admin-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    text-decoration: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    white-space: nowrap;
    transition: color 0.15s, border-color 0.15s;
    letter-spacing: 0.01em;
}
.admin-tab i { font-size: 0.8rem; opacity: 0.75; }
.admin-tab:hover { color: #1e293b; border-bottom-color: #cbd5e1; }
.admin-tab.active { color: #e94560; border-bottom-color: #e94560; }
.admin-tab.active i { opacity: 1; }
.tab-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.35rem;
    height: 1.35rem;
    padding: 0 0.3rem;
    border-radius: 9999px;
    font-size: 0.68rem;
    font-weight: 700;
    background: #f1f5f9;
    color: #475569;
    line-height: 1;
}
.admin-tab.active .tab-badge { background: #fde8ec; color: #e94560; }

/* ── Filter bar ──────────────────────────────────────────────────────── */
.admin-filter-bar {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
    padding: 0.85rem 1.1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.85rem;
}
.admin-filter-bar label {
    font-size: 0.775rem;
    font-weight: 700;
    color: #64748b;
    white-space: nowrap;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.admin-filter-bar select,
.admin-filter-bar input[type="text"] {
    padding: 0.4rem 0.7rem;
    font-size: 0.875rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    background: #fff;
    color: #1e293b;
    min-width: 140px;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.admin-filter-bar select:focus,
.admin-filter-bar input[type="text"]:focus {
    outline: none;
    border-color: #e94560;
    box-shadow: 0 0 0 3px rgba(233, 69, 96, 0.1);
}

/* ── Admin table ─────────────────────────────────────────────────────── */
.admin-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.admin-table thead tr { background: #f8fafc; }
.admin-table th {
    text-align: left;
    padding: 0.6rem 1rem;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #94a3b8;
    border-bottom: 1px solid #e2e8f0;
}
.admin-table td {
    padding: 0.75rem 1rem;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.admin-table tbody tr:nth-child(even) td { background: #fafbfc; }
.admin-table tbody tr:hover td { background: #f0f6ff; }
.admin-table tr:last-child td { border-bottom: none; }
.admin-table .cell-name strong { display: block; font-weight: 600; color: #1e293b; }
.admin-table .cell-name small  { color: #64748b; font-size: 0.8rem; }
.admin-table .cell-actions { display: flex; align-items: center; gap: 0.5rem; }

/* User avatar initials (in tables) */
.user-avatar-initials {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e94560, #c2253e);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.02em;
    flex-shrink: 0;
    border: 2px solid rgba(233, 69, 96, 0.22);
}
.user-avatar-initials.role-company   { background: linear-gradient(135deg, #0369a1, #0ea5e9); border-color: rgba(3,105,161,0.2); }
.user-avatar-initials.role-candidate { background: linear-gradient(135deg, #15803d, #22c55e); border-color: rgba(21,128,61,0.2); }
.user-avatar-initials.role-admin     { background: linear-gradient(135deg, #e94560, #c2253e); border-color: rgba(233,69,96,0.2); }
.user-cell-inner { display: flex; align-items: center; gap: 10px; }
.user-cell-text strong { display: block; font-weight: 600; color: #1e293b; font-size: 0.875rem; }
.user-cell-text small  { color: #64748b; font-size: 0.78rem; }

/* ── Role badges ─────────────────────────────────────────────────────── */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.22rem 0.7rem;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border: 1px solid transparent;
}
.role-badge-admin     { background: #fde8ec; color: #c2253e; border-color: rgba(233,69,96,0.25); }
.role-badge-company   { background: #e0f2fe; color: #0369a1; border-color: rgba(3,105,161,0.2); }
.role-badge-candidate { background: #dcfce7; color: #15803d; border-color: rgba(21,128,61,0.2); }

/* ── Danger / delete button ──────────────────────────────────────────── */
.btn-danger {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 0.5rem;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
    white-space: nowrap;
}
.btn-danger:hover { background: #fee2e2; border-color: #fca5a5; }

.confirm-delete { display: inline; }
.confirm-delete button[type="submit"] {
    all: unset;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    white-space: nowrap;
}
.confirm-delete button[type="submit"]:hover { background: #fee2e2; border-color: #fca5a5; }

/* ── Activity feed (timeline) ───────────────────────────────────────── */
.activity-feed { display: flex; flex-direction: column; gap: 0; }
.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.85rem 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
}
.activity-item:last-child { border-bottom: none; }
.activity-icon {
    flex-shrink: 0;
    width: 2.1rem;
    height: 2.1rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    margin-top: 0.05rem;
}
.activity-icon-application  { background: #ede9fe; color: #7c3aed; }
.activity-icon-registration { background: #dcfce7; color: #16a34a; }
.activity-icon-job          { background: #e0f2fe; color: #0369a1; }
.activity-body { flex: 1; min-width: 0; }
.activity-body strong { font-weight: 600; color: #1e293b; }
.activity-body span   { color: #64748b; }
.activity-type-label {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 2px;
}
.activity-type-label.lbl-application  { color: #7c3aed; }
.activity-type-label.lbl-registration { color: #16a34a; }
.activity-type-label.lbl-job          { color: #0369a1; }
.activity-date { font-size: 0.72rem; color: #94a3b8; white-space: nowrap; margin-top: 0.1rem; flex-shrink: 0; }

/* ── Pipeline bars (funnel style) ───────────────────────────────────── */
.pipeline-funnel { display: flex; flex-direction: column; gap: 0.55rem; margin-top: 0.5rem; }
.pipeline-funnel-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.85rem;
}
.pipeline-funnel-label {
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 110px;
    flex-shrink: 0;
}
.pipeline-funnel-track {
    flex: 1;
    height: 10px;
    background: #f1f5f9;
    border-radius: 9999px;
    overflow: hidden;
}
.pipeline-funnel-fill {
    height: 100%;
    border-radius: 9999px;
    transition: width 0.5s ease;
}
.pipeline-funnel-count { font-weight: 700; color: #1e293b; font-size: 0.85rem; min-width: 2rem; text-align: right; }

/* Pipeline fill colors per status */
.pfill-submitted { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
.pfill-reviewing { background: linear-gradient(90deg, #f59e0b, #d97706); }
.pfill-interview { background: linear-gradient(90deg, #34d399, #059669); }
.pfill-accepted  { background: linear-gradient(90deg, #2dd4bf, #0f766e); }
.pfill-rejected  { background: linear-gradient(90deg, #f87171, #b91c1c); }

/* ── Category stat grid ─────────────────────────────────────────────── */
.category-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.75rem;
    margin-top: 0.5rem;
}
.category-stat-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.4rem;
    padding: 1rem 0.65rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.875rem;
    text-align: center;
    transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
    cursor: default;
}
.category-stat-card:hover { border-color: #e94560; box-shadow: 0 4px 16px rgba(233,69,96,0.10); transform: translateY(-1px); }
.category-stat-icon  { font-size: 1.25rem; color: #e94560; margin-bottom: 0.1rem; }
.category-stat-label { font-size: 0.75rem; font-weight: 600; color: #475569; line-height: 1.25; }
.category-stat-count { font-size: 1.15rem; font-weight: 800; color: #1e293b; }

/* ── Bar chart (cities) ─────────────────────────────────────────────── */
.bar-chart { display: flex; flex-direction: column; gap: 0.6rem; margin-top: 0.5rem; }
.bar-item { display: grid; grid-template-columns: 110px 1fr 36px; align-items: center; gap: 0.75rem; font-size: 0.82rem; }
.bar-label { font-weight: 600; color: #475569; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; }
.bar-track { background: #f1f5f9; border-radius: 9999px; height: 9px; overflow: hidden; }
.bar-fill  { height: 100%; border-radius: 9999px; background: linear-gradient(90deg, #e94560, #c2253e); transition: width 0.4s ease; }
.bar-value { font-weight: 700; color: #1e293b; text-align: right; font-size: 0.82rem; }

/* ── Top companies mini-table ───────────────────────────────────────── */
.companies-table { width: 100%; font-size: 0.875rem; border-collapse: collapse; }
.companies-table th { text-align: left; padding: 0.5rem 0.75rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; border-bottom: 1px solid #e2e8f0; }
.companies-table td { padding: 0.65rem 0.75rem; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.companies-table tr:last-child td { border-bottom: none; }
.companies-table tbody tr:hover td { background: #fafbfc; }
.company-rank { width: 2rem; height: 2rem; border-radius: 50%; background: #f1f5f9; color: #64748b; font-size: 0.72rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; }
.company-rank-1 { background: #fef9c3; color: #ca8a04; }
.company-rank-2 { background: #f1f5f9; color: #475569; }
.company-rank-3 { background: #fff7ed; color: #c2410c; }

/* ── Inline status form sizing ──────────────────────────────────────── */
.inline-status-form { display: inline-flex; align-items: center; gap: 0.5rem; }
.inline-status-form select { font-size: 0.8rem; padding: 0.35rem 0.55rem; border: 1px solid #e2e8f0; border-radius: 0.45rem; background: #fff; color: #1e293b; transition: border-color 0.15s; }
.inline-status-form select:focus { outline: none; border-color: #e94560; }
.inline-status-form .btn { padding: 0.35rem 0.75rem; font-size: 0.8rem; }

/* ── Flash messages ─────────────────────────────────────────────────── */
.flash-message { padding: 0.85rem 1.25rem; border-radius: 0.65rem; font-size: 0.875rem; font-weight: 500; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.65rem; }
.flash-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.flash-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* ── Empty states ───────────────────────────────────────────────────── */
.admin-empty {
    padding: 3rem 1rem;
    text-align: center;
    color: #94a3b8;
}
.admin-empty-icon {
    width: 60px;
    height: 60px;
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(233,69,96,0.09), rgba(233,69,96,0.17));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #e94560;
    margin: 0 auto 14px;
}
.admin-empty h3 { font-size: 1.05rem; color: #475569; margin-bottom: 6px; }
.admin-empty p  { font-size: 0.9rem; color: #94a3b8; margin-bottom: 0; }

/* ── Layout: workbench + sidebar ────────────────────────────────────── */
.overview-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
@media (max-width: 860px) { .overview-grid { grid-template-columns: 1fr; } }

/* ── Responsive table wrapper ───────────────────────────────────────── */
.table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }

/* ── Section kicker ─────────────────────────────────────────────────── */
.section-kicker { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #e94560; margin-bottom: 0.2rem; }

/* ── Responsive ─────────────────────────────────────────────────────── */
@media (max-width: 860px) {
    .bar-item { grid-template-columns: 80px 1fr 32px; }
    .pipeline-funnel-label { min-width: 90px; }
}
@media (max-width: 600px) {
    .hero-stat-row { gap: 8px; }
    .hero-stat-badge { font-size: 12px; padding: 6px 11px; }
    .admin-tabs { gap: 0; }
    .admin-tab { padding: 9px 12px; font-size: 0.82rem; }
}
</style>

<?php
// ── Template helper functions ─────────────────────────────────────────────────
function activity_icon_class(string $type): string {
    return match ($type) {
        'application'  => 'activity-icon-application',
        'registration' => 'activity-icon-registration',
        'job'          => 'activity-icon-job',
        default        => 'activity-icon-registration',
    };
}

function activity_fa_icon(string $type): string {
    return match ($type) {
        'application'  => 'fa-paper-plane',
        'registration' => 'fa-user-plus',
        'job'          => 'fa-briefcase',
        default        => 'fa-bell',
    };
}

function activity_label(string $type): string {
    return match ($type) {
        'application'  => 'Candidature',
        'registration' => 'Inscription',
        'job'          => 'Offre publiée',
        default        => 'Activité',
    };
}

function status_label_fr(string $status): string {
    return match ($status) {
        'submitted' => 'Soumise',
        'reviewing' => 'En revue',
        'interview' => 'Entretien',
        'accepted'  => 'Acceptée',
        'rejected'  => 'Refusée',
        'published' => 'Publiée',
        'draft'     => 'Brouillon',
        'closed'    => 'Clôturée',
        default     => ucfirst($status),
    };
}

function role_label_fr(string $role): string {
    return match ($role) {
        'admin'     => 'Administrateur',
        'company'   => 'Entreprise',
        'candidate' => 'Candidat',
        default     => ucfirst($role),
    };
}
?>

<!-- ════════════════════════════════════════════════════════════════════
     BREADCRUMB
     ════════════════════════════════════════════════════════════════════ -->
<div class="dashboard-breadcrumb">
    <div class="container">
        <a href="/index.php" class="breadcrumb-link">
            <i class="fas fa-home"></i> Voir le site
        </a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">
            <i class="fas fa-shield-halved" style="color:#e94560;"></i>
            Administration
        </span>
        <span style="flex:1;"></span>
        <a href="?tab=overview"     class="breadcrumb-link <?= $tab === 'overview'     ? 'active' : '' ?>"><i class="fas fa-gauge-high"></i> Tableau de bord</a>
        <a href="?tab=users"        class="breadcrumb-link <?= $tab === 'users'        ? 'active' : '' ?>"><i class="fas fa-users"></i> Utilisateurs</a>
        <a href="?tab=jobs"         class="breadcrumb-link <?= $tab === 'jobs'         ? 'active' : '' ?>"><i class="fas fa-briefcase"></i> Offres</a>
        <a href="?tab=applications" class="breadcrumb-link <?= $tab === 'applications' ? 'active' : '' ?>"><i class="fas fa-paper-plane"></i> Candidatures</a>
        <a href="?tab=messages"     class="breadcrumb-link <?= $tab === 'messages'     ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Messages</a>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════════════
     HERO
     ════════════════════════════════════════════════════════════════════ -->
<section class="dashboard-hero dashboard-hero-admin">
    <div class="container dashboard-hero-grid">

        <div class="dashboard-overview">
            <div class="hero-admin-avatar">
                <?= mb_strtoupper(mb_substr((string) ($user['first_name'] ?? 'A'), 0, 1) . mb_substr((string) ($user['last_name'] ?? ''), 0, 1)) ?>
            </div>

            <span class="dashboard-kicker"><i class="fas fa-shield-halved"></i> Console d'administration</span>
            <h1 class="dashboard-title">Bonjour, <?= e($user['first_name']) ?> <?= e($user['last_name']) ?></h1>
            <p class="dashboard-copy">Pilote la plateforme depuis un espace centralisé. Gère les comptes, modère les offres, suis le pipeline de recrutement et traite les demandes de contact.</p>

            <?php if ($memberSince): ?>
                <p class="hero-member-since">
                    <i class="fas fa-calendar-alt"></i> Administrateur depuis <?= e($memberSince) ?>
                    &nbsp;·&nbsp;
                    <i class="fas fa-clock" style="margin-left:4px;"></i> Dernière connexion : Aujourd'hui
                </p>
            <?php endif; ?>

            <div class="hero-stat-row">
                <span class="hero-stat-badge">
                    <i class="fas fa-users"></i>
                    <?= (int) ($usersStats['total'] ?? 0) ?> utilisateurs
                </span>
                <span class="hero-stat-badge">
                    <i class="fas fa-briefcase"></i>
                    <?= (int) ($jobsStats['published'] ?? 0) ?> offres publiées
                </span>
                <span class="hero-stat-badge">
                    <i class="fas fa-paper-plane"></i>
                    <?= (int) ($applicationsStats['total'] ?? 0) ?> candidatures
                </span>
                <?php if ($messagesTotal > 0): ?>
                    <span class="hero-stat-badge" style="background:rgba(233,69,96,0.22);border-color:rgba(233,69,96,0.35);">
                        <i class="fas fa-envelope"></i>
                        <?= $messagesTotal ?> message<?= $messagesTotal > 1 ? 's' : '' ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-panel-stack">

            <!-- Platform summary panel -->
            <article class="dashboard-panel">
                <span class="panel-label">Vue de la plateforme</span>
                <div class="panel-big-number"><?= (int) ($applicationsStats['total'] ?? 0) ?></div>
                <span style="color:#94a3b8;font-size:.8rem;">candidatures au total</span>

                <div class="panel-micro-grid" style="margin-top:.9rem;">
                    <div class="panel-micro-card">
                        <strong><?= (int) ($usersStats['candidates'] ?? 0) ?> candidats</strong>
                        <span>Comptes actifs</span>
                    </div>
                    <div class="panel-micro-card">
                        <strong><?= (int) ($usersStats['companies'] ?? 0) ?> entreprises</strong>
                        <span>Recruteurs inscrits</span>
                    </div>
                    <div class="panel-micro-card">
                        <strong><?= (int) ($jobsStats['published'] ?? 0) ?> publiées</strong>
                        <span>Offres en ligne</span>
                    </div>
                    <div class="panel-micro-card">
                        <strong><?= $categoriesTotal ?> catégories</strong>
                        <span>Secteurs couverts</span>
                    </div>
                </div>
            </article>

            <!-- Quick navigation panel -->
            <article class="dashboard-panel dashboard-panel-muted">
                <span class="panel-label">Navigation rapide</span>
                <div class="inline-action-list">
                    <a href="?tab=users" class="inline-action-card">
                        <strong><i class="fas fa-users" style="margin-right:6px;color:#1d4ed8;"></i>Utilisateurs</strong>
                        <span>Gérer comptes et rôles</span>
                    </a>
                    <a href="?tab=jobs" class="inline-action-card">
                        <strong><i class="fas fa-briefcase" style="margin-right:6px;color:#b45309;"></i>Offres d'emploi</strong>
                        <span>Modérer et piloter les annonces</span>
                    </a>
                    <a href="?tab=applications" class="inline-action-card">
                        <strong><i class="fas fa-paper-plane" style="margin-right:6px;color:#15803d;"></i>Candidatures</strong>
                        <span>Suivre l'avancement du pipeline</span>
                    </a>
                    <a href="?tab=messages" class="inline-action-card">
                        <strong><i class="fas fa-envelope" style="margin-right:6px;color:#7c3aed;"></i>Messages</strong>
                        <span>Lire les demandes de contact</span>
                    </a>
                </div>
            </article>

        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════════════════════
     KEY METRICS
     ════════════════════════════════════════════════════════════════════ -->
<section class="inscription-section" style="padding-bottom:0;">
    <div class="container">
        <?php
        $appTotal = (int) ($applicationsStats['total'] ?? 0);
        $metrics  = [
            ['icon' => 'fa-users',          'grad' => 'linear-gradient(135deg,#dbeafe,#bfdbfe)',              'color' => '#1d4ed8', 'label' => 'Utilisateurs',   'value' => (int) ($usersStats['total']       ?? 0), 'pct' => 100,                                                                                'pfill' => 'background:linear-gradient(90deg,#3b82f6,#1d4ed8)'],
            ['icon' => 'fa-user-tie',        'grad' => 'linear-gradient(135deg,#dcfce7,#bbf7d0)',              'color' => '#15803d', 'label' => 'Candidats',       'value' => (int) ($usersStats['candidates']  ?? 0), 'pct' => (int) ($usersStats['total'] ?? 0) > 0 ? round((int)($usersStats['candidates']??0)/(int)$usersStats['total']*100) : 0, 'pfill' => 'background:linear-gradient(90deg,#34d399,#15803d)'],
            ['icon' => 'fa-building',        'grad' => 'linear-gradient(135deg,#e0f2fe,#bae6fd)',              'color' => '#0369a1', 'label' => 'Entreprises',     'value' => (int) ($usersStats['companies']   ?? 0), 'pct' => (int) ($usersStats['total'] ?? 0) > 0 ? round((int)($usersStats['companies']??0)/(int)$usersStats['total']*100) : 0, 'pfill' => 'background:linear-gradient(90deg,#38bdf8,#0369a1)'],
            ['icon' => 'fa-briefcase',       'grad' => 'linear-gradient(135deg,#ede9fe,#ddd6fe)',              'color' => '#7c3aed', 'label' => 'Offres totales',  'value' => (int) ($jobsStats['total']        ?? 0), 'pct' => 100,                                                                                'pfill' => 'background:linear-gradient(90deg,#a78bfa,#7c3aed)'],
            ['icon' => 'fa-broadcast-tower', 'grad' => 'linear-gradient(135deg,#ccfbf1,#99f6e4)',              'color' => '#0f766e', 'label' => 'Offres publiées', 'value' => (int) ($jobsStats['published']    ?? 0), 'pct' => (int)($jobsStats['total']??0) > 0 ? round((int)($jobsStats['published']??0)/(int)$jobsStats['total']*100) : 0,  'pfill' => 'background:linear-gradient(90deg,#2dd4bf,#0f766e)'],
            ['icon' => 'fa-paper-plane',     'grad' => 'linear-gradient(135deg,rgba(233,69,96,.1),rgba(233,69,96,.2))', 'color' => '#e94560', 'label' => 'Candidatures',  'value' => $appTotal,                              'pct' => 100,                                                                                'pfill' => 'background:linear-gradient(90deg,#f87171,#e94560)'],
            ['icon' => 'fa-tags',            'grad' => 'linear-gradient(135deg,#fef3c7,#fde68a)',              'color' => '#b45309', 'label' => 'Catégories',      'value' => $categoriesTotal,                       'pct' => 100,                                                                                'pfill' => 'background:linear-gradient(90deg,#fbbf24,#b45309)'],
            ['icon' => 'fa-envelope',        'grad' => 'linear-gradient(135deg,#fde8ec,#fecdd3)',              'color' => '#e94560', 'label' => 'Messages',        'value' => $messagesTotal,                         'pct' => 100,                                                                                'pfill' => 'background:linear-gradient(90deg,#fb7185,#e94560)'],
        ];
        ?>
        <div class="metric-grid-8">
            <?php foreach ($metrics as $m): ?>
                <article class="metric-card-premium">
                    <div class="metric-icon" style="background:<?= $m['grad'] ?>;color:<?= $m['color'] ?>;"><i class="fas <?= $m['icon'] ?>"></i></div>
                    <div class="metric-copy" style="flex:1;">
                        <span><?= $m['label'] ?></span>
                        <strong><?= $m['value'] ?></strong>
                        <div class="metric-progress-wrap">
                            <div class="metric-progress-fill" style="width:<?= $m['pct'] ?>%;<?= $m['pfill'] ?>;"></div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════════════════════
     TAB NAVIGATION + TAB CONTENT
     ════════════════════════════════════════════════════════════════════ -->
<section class="inscription-section dashboard-stage">
    <div class="container">

        <?php if ($msg = ($_SESSION['_flash']['success'] ?? null)): ?>
            <div class="flash-message flash-success">
                <i class="fas fa-circle-check"></i> <?= e($msg) ?>
            </div>
            <?php unset($_SESSION['_flash']['success']); ?>
        <?php elseif ($msg = ($_SESSION['_flash']['error'] ?? null)): ?>
            <div class="flash-message flash-error">
                <i class="fas fa-circle-exclamation"></i> <?= e($msg) ?>
            </div>
            <?php unset($_SESSION['_flash']['error']); ?>
        <?php endif; ?>

        <!-- Tab nav -->
        <nav class="admin-tabs" role="tablist">
            <a href="?tab=overview"
               class="admin-tab <?= $tab === 'overview' ? 'active' : '' ?>"
               role="tab" aria-selected="<?= $tab === 'overview' ? 'true' : 'false' ?>">
                <i class="fas fa-gauge-high"></i> Vue d'ensemble
            </a>
            <a href="?tab=users"
               class="admin-tab <?= $tab === 'users' ? 'active' : '' ?>"
               role="tab" aria-selected="<?= $tab === 'users' ? 'true' : 'false' ?>">
                <i class="fas fa-users"></i> Utilisateurs
                <span class="tab-badge"><?= (int) ($usersStats['total'] ?? 0) ?></span>
            </a>
            <a href="?tab=jobs"
               class="admin-tab <?= $tab === 'jobs' ? 'active' : '' ?>"
               role="tab" aria-selected="<?= $tab === 'jobs' ? 'true' : 'false' ?>">
                <i class="fas fa-briefcase"></i> Offres d'emploi
                <span class="tab-badge"><?= (int) ($jobsStats['total'] ?? 0) ?></span>
            </a>
            <a href="?tab=applications"
               class="admin-tab <?= $tab === 'applications' ? 'active' : '' ?>"
               role="tab" aria-selected="<?= $tab === 'applications' ? 'true' : 'false' ?>">
                <i class="fas fa-paper-plane"></i> Candidatures
                <span class="tab-badge"><?= (int) ($applicationsStats['total'] ?? 0) ?></span>
            </a>
            <a href="?tab=messages"
               class="admin-tab <?= $tab === 'messages' ? 'active' : '' ?>"
               role="tab" aria-selected="<?= $tab === 'messages' ? 'true' : 'false' ?>">
                <i class="fas fa-envelope"></i> Messages
                <span class="tab-badge"><?= $messagesTotal ?></span>
            </a>
        </nav>

        <!-- ================================================================
             TAB: OVERVIEW
             ================================================================ -->
        <?php if ($tab === 'overview'): ?>

        <!-- Row 1: Activity feed (wide) + Pipeline & Top companies (stacked) -->
        <div class="dashboard-workbench" style="margin-bottom:1.5rem;">

            <!-- Main column: activity feed -->
            <div class="dashboard-main">
                <article class="surface-card">
                    <div class="surface-head">
                        <div>
                            <p class="section-kicker">Activité plateforme</p>
                            <h2>Fil d'activité récente</h2>
                            <p class="surface-subtitle">Les 15 dernières actions enregistrées sur la plateforme.</p>
                        </div>
                    </div>
                    <?php if (!$recentActivity): ?>
                        <div class="admin-empty">
                            <div class="admin-empty-icon"><i class="fas fa-clock-rotate-left"></i></div>
                            <h3>Aucune activité récente</h3>
                            <p>Les nouvelles inscriptions, candidatures et publications apparaîtront ici.</p>
                        </div>
                    <?php else: ?>
                        <div class="activity-feed">
                            <?php foreach ($recentActivity as $event):
                                $evType   = (string) ($event['type']   ?? 'registration');
                                $evWho    = (string) ($event['who']    ?? '—');
                                $evTarget = (string) ($event['target'] ?? '');
                                $evDate   = (string) ($event['date']   ?? '');
                                $evLblClass = 'lbl-' . $evType;
                            ?>
                                <div class="activity-item">
                                    <div class="activity-icon <?= activity_icon_class($evType) ?>">
                                        <i class="fas <?= activity_fa_icon($evType) ?>"></i>
                                    </div>
                                    <div class="activity-body">
                                        <div class="activity-type-label <?= $evLblClass ?>">
                                            <?= e(activity_label($evType)) ?>
                                        </div>
                                        <strong><?= e($evWho) ?></strong>
                                        <?php if ($evTarget): ?>
                                            <span> — <?= e($evTarget) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="activity-date"><?= e(format_datetime($evDate, 'd/m H:i')) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </div>

            <!-- Sidebar column: pipeline + top companies -->
            <aside class="dashboard-sidebar">

                <!-- Pipeline funnel -->
                <article class="surface-card surface-card-tight">
                    <div class="surface-head">
                        <div>
                            <p class="section-kicker">Candidatures</p>
                            <h2>Pipeline global</h2>
                            <p class="surface-subtitle">Distribution des statuts.</p>
                        </div>
                        <a href="?tab=applications" class="btn btn-outline" style="font-size:12px;padding:5px 11px;">Voir tout</a>
                    </div>
                    <?php
                    $pipelineTotal = array_sum($pipelineCounts);
                    $pipelineIcons = [
                        'submitted' => 'fa-inbox',
                        'reviewing' => 'fa-magnifying-glass',
                        'interview' => 'fa-comments',
                        'accepted'  => 'fa-circle-check',
                        'rejected'  => 'fa-circle-xmark',
                    ];
                    ?>
                    <div class="pipeline-funnel">
                        <?php foreach ($pipelineCounts as $pStatus => $pCount):
                            $pPct = $pipelineTotal > 0 ? round($pCount / $pipelineTotal * 100) : 0;
                        ?>
                            <div class="pipeline-funnel-item">
                                <div class="pipeline-funnel-label">
                                    <span class="status-pill status-<?= e($pStatus) ?>" style="font-size:.65rem;padding:3px 8px;">
                                        <i class="fas <?= $pipelineIcons[$pStatus] ?? 'fa-circle' ?>"></i>
                                        <?= status_label_fr($pStatus) ?>
                                    </span>
                                </div>
                                <div class="pipeline-funnel-track">
                                    <div class="pipeline-funnel-fill pfill-<?= e($pStatus) ?>" style="width:<?= max(4, $pPct) ?>%;"></div>
                                </div>
                                <span class="pipeline-funnel-count"><?= (int) $pCount ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>

                <!-- Top companies -->
                <article class="surface-card surface-card-tight">
                    <div class="surface-head">
                        <div>
                            <p class="section-kicker">Entreprises</p>
                            <h2>Top recruteurs</h2>
                            <p class="surface-subtitle">Par candidatures reçues.</p>
                        </div>
                    </div>
                    <?php if (!$topCompanies): ?>
                        <div class="admin-empty" style="padding:1.25rem 0;">
                            <div class="admin-empty-icon" style="width:44px;height:44px;font-size:16px;"><i class="fas fa-building"></i></div>
                            <p>Aucune entreprise.</p>
                        </div>
                    <?php else: ?>
                        <table class="companies-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Entreprise</th>
                                    <th>Offres</th>
                                    <th>Candidatures</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topCompanies as $i => $company): ?>
                                    <tr>
                                        <td><span class="company-rank company-rank-<?= $i + 1 ?>"><?= $i + 1 ?></span></td>
                                        <td><strong style="font-size:.85rem;"><?= e($company['company_name']) ?></strong></td>
                                        <td><?= (int) ($company['job_count'] ?? 0) ?></td>
                                        <td><?= (int) ($company['app_count'] ?? 0) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </article>

            </aside>
        </div>

        <!-- Row 2: Categories + Cities -->
        <div class="overview-grid">

            <!-- Jobs by category -->
            <article class="surface-card">
                <div class="surface-head">
                    <div>
                        <p class="section-kicker">Catégories</p>
                        <h2>Offres par catégorie</h2>
                        <p class="surface-subtitle">Répartition des offres actives dans chaque secteur.</p>
                    </div>
                </div>
                <?php if (!$jobsByCategory): ?>
                    <div class="admin-empty">
                        <div class="admin-empty-icon"><i class="fas fa-tags"></i></div>
                        <p>Aucune donnée de catégorie disponible.</p>
                    </div>
                <?php else: ?>
                    <div class="category-stat-grid">
                        <?php foreach ($jobsByCategory as $cat): ?>
                            <div class="category-stat-card">
                                <div class="category-stat-icon">
                                    <i class="<?= e($cat['icon'] ?: 'fas fa-tag') ?>"></i>
                                </div>
                                <div class="category-stat-label"><?= e($cat['name']) ?></div>
                                <div class="category-stat-count"><?= (int) ($cat['job_count'] ?? 0) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

            <!-- Jobs by city -->
            <article class="surface-card">
                <div class="surface-head">
                    <div>
                        <p class="section-kicker">Géographie</p>
                        <h2>Offres par ville</h2>
                        <p class="surface-subtitle">Les 8 villes avec le plus d'offres actives.</p>
                    </div>
                </div>
                <?php if (!$jobsByCity): ?>
                    <div class="admin-empty">
                        <div class="admin-empty-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <p>Aucune donnée géographique disponible.</p>
                    </div>
                <?php else: ?>
                    <div class="bar-chart">
                        <?php foreach ($jobsByCity as $cityRow):
                            $pct = $maxCityJobs > 0 ? round(((int) $cityRow['total'] / $maxCityJobs) * 100) : 0;
                        ?>
                            <div class="bar-item">
                                <div class="bar-label" title="<?= e($cityRow['city']) ?>"><?= e($cityRow['city']) ?></div>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:<?= $pct ?>%;"></div>
                                </div>
                                <div class="bar-value"><?= (int) $cityRow['total'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>

        </div>

        <!-- Row 3: Recent users full-width -->
        <article class="surface-card">
            <div class="surface-head">
                <div>
                    <p class="section-kicker">Inscriptions</p>
                    <h2>Derniers inscrits</h2>
                    <p class="surface-subtitle">Les 8 comptes créés le plus récemment sur la plateforme.</p>
                </div>
                <a href="?tab=users" class="btn btn-outline">Tous les utilisateurs</a>
            </div>
            <?php if (!$recentUsers): ?>
                <div class="admin-empty">
                    <div class="admin-empty-icon"><i class="fas fa-user-plus"></i></div>
                    <h3>Aucun utilisateur récent</h3>
                    <p>Les nouveaux comptes inscrits apparaîtront ici.</p>
                </div>
            <?php else: ?>
                <div class="table-scroll">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Rôle</th>
                                <th>Ville</th>
                                <th>Inscription</th>
                                <th>Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentUsers as $u):
                                $uInitials = mb_strtoupper(mb_substr((string)($u['first_name'] ?? 'U'), 0, 1) . mb_substr((string)($u['last_name'] ?? ''), 0, 1));
                            ?>
                                <tr>
                                    <td>
                                        <div class="user-cell-inner">
                                            <div class="user-avatar-initials role-<?= e($u['role']) ?>"><?= e($uInitials) ?></div>
                                            <div class="user-cell-text">
                                                <strong><?= e($u['first_name'] . ' ' . $u['last_name']) ?></strong>
                                                <small><?= e($u['email']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="role-badge role-badge-<?= e($u['role']) ?>">
                                            <?= role_label_fr($u['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= e($u['city'] ?? '—') ?></td>
                                    <td style="white-space:nowrap;"><?= e(format_datetime($u['created_at'] ?? '', 'd/m/Y')) ?></td>
                                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.8rem;color:#64748b;">
                                        <?= e($u['extra_info'] ?? '—') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </article>

        <?php endif; // end overview ?>

        <!-- ================================================================
             TAB: USERS
             ================================================================ -->
        <?php if ($tab === 'users'): ?>

        <article class="surface-card">
            <div class="surface-head">
                <div>
                    <p class="section-kicker">Gestion des comptes</p>
                    <h2>Tous les utilisateurs</h2>
                    <p class="surface-subtitle">Filtrez par rôle ou recherchez par nom / email. Les suppressions sont irréversibles et effacent toutes les données associées.</p>
                </div>
                <span style="font-size:.875rem;font-weight:700;color:#64748b;"><?= count($allUsers) ?> résultat<?= count($allUsers) !== 1 ? 's' : '' ?></span>
            </div>

            <form method="get" class="admin-filter-bar">
                <input type="hidden" name="tab" value="users">
                <label for="filter-role">Rôle</label>
                <select id="filter-role" name="role">
                    <option value="">Tous les rôles</option>
                    <?php foreach (['candidate', 'company', 'admin'] as $r): ?>
                        <option value="<?= e($r) ?>" <?= $roleFilter === $r ? 'selected' : '' ?>><?= role_label_fr($r) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="filter-search">Recherche</label>
                <input id="filter-search" type="text" name="search" value="<?= e($searchFilter) ?>" placeholder="Nom, email…" style="flex:1;min-width:180px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-magnifying-glass"></i> Filtrer</button>
                <?php if ($roleFilter || $searchFilter): ?>
                    <a href="?tab=users" class="btn btn-outline">Réinitialiser</a>
                <?php endif; ?>
            </form>

            <?php if (!$allUsers): ?>
                <div class="admin-empty">
                    <div class="admin-empty-icon"><i class="fas fa-users"></i></div>
                    <h3>Aucun résultat</h3>
                    <p>Aucun utilisateur ne correspond aux critères de filtrage.</p>
                </div>
            <?php else: ?>
                <div class="table-scroll">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Rôle</th>
                                <th>Ville</th>
                                <th>Inscription</th>
                                <th>Infos complémentaires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allUsers as $u):
                                $uInitials = mb_strtoupper(mb_substr((string)($u['first_name'] ?? 'U'), 0, 1) . mb_substr((string)($u['last_name'] ?? ''), 0, 1));
                            ?>
                                <tr>
                                    <td>
                                        <div class="user-cell-inner">
                                            <div class="user-avatar-initials role-<?= e($u['role']) ?>"><?= e($uInitials) ?></div>
                                            <div class="user-cell-text">
                                                <strong><?= e($u['first_name'] . ' ' . $u['last_name']) ?></strong>
                                                <small><?= e($u['email']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="role-badge role-badge-<?= e($u['role']) ?>">
                                            <?= role_label_fr($u['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= e($u['city'] ?? '—') ?></td>
                                    <td style="white-space:nowrap;"><?= e(format_datetime($u['created_at'] ?? '', 'd/m/Y')) ?></td>
                                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.8rem;color:#64748b;" title="<?= e($u['extra_info'] ?? '') ?>">
                                        <?= e($u['extra_info'] ?? '—') ?>
                                    </td>
                                    <td class="cell-actions">
                                        <?php if ((int) $u['id'] !== (int) $user['id']): ?>
                                            <form method="post" class="confirm-delete"
                                                  onsubmit="return confirm('Supprimer définitivement cet utilisateur et toutes ses données ?');">
                                                <input type="hidden" name="_csrf"   value="<?= e(csrf_token()) ?>">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="id"     value="<?= (int) $u['id'] ?>">
                                                <button type="submit">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span style="font-size:.78rem;color:#94a3b8;font-style:italic;">Vous-même</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </article>

        <?php endif; // end users ?>

        <!-- ================================================================
             TAB: JOBS
             ================================================================ -->
        <?php if ($tab === 'jobs'): ?>

        <article class="surface-card">
            <div class="surface-head">
                <div>
                    <p class="section-kicker">Modération</p>
                    <h2>Toutes les offres d'emploi</h2>
                    <p class="surface-subtitle">Modifiez le statut des offres ou supprimez-les définitivement. Chaque suppression retire aussi les candidatures associées.</p>
                </div>
                <span style="font-size:.875rem;font-weight:700;color:#64748b;"><?= count($allJobs) ?> résultat<?= count($allJobs) !== 1 ? 's' : '' ?></span>
            </div>

            <form method="get" class="admin-filter-bar">
                <input type="hidden" name="tab" value="jobs">
                <label for="filter-status-job">Statut</label>
                <select id="filter-status-job" name="status">
                    <option value="">Tous les statuts</option>
                    <?php foreach (['published', 'draft', 'closed'] as $s): ?>
                        <option value="<?= e($s) ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= status_label_fr($s) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="filter-search-job">Recherche</label>
                <input id="filter-search-job" type="text" name="search" value="<?= e($searchFilter) ?>" placeholder="Titre, entreprise…" style="flex:1;min-width:180px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-magnifying-glass"></i> Filtrer</button>
                <?php if ($statusFilter || $searchFilter): ?>
                    <a href="?tab=jobs" class="btn btn-outline">Réinitialiser</a>
                <?php endif; ?>
            </form>

            <?php if (!$allJobs): ?>
                <div class="admin-empty">
                    <div class="admin-empty-icon"><i class="fas fa-briefcase"></i></div>
                    <h3>Aucune offre trouvée</h3>
                    <p>Aucune offre ne correspond aux critères de recherche.</p>
                </div>
            <?php else: ?>
                <div class="application-list">
                    <?php foreach ($allJobs as $job):
                        $jobStatus = (string) ($job['status'] ?? 'draft');
                        $jobStatusPill = match ($jobStatus) {
                            'published' => 'interview',
                            'closed'    => 'rejected',
                            default     => 'reviewing',
                        };
                    ?>
                        <article class="application-item">
                            <div class="application-item-head">
                                <div style="min-width:0;">
                                    <h3><?= e($job['title']) ?></h3>
                                    <p>
                                        <i class="fas fa-building"       style="color:#94a3b8;"></i> <?= e($job['company_name'] ?? '—') ?>
                                        &nbsp;·&nbsp;
                                        <i class="fas fa-tag"            style="color:#94a3b8;"></i> <?= e($job['category_name'] ?? 'Général') ?>
                                        &nbsp;·&nbsp;
                                        <i class="fas fa-map-marker-alt" style="color:#94a3b8;"></i> <?= e($job['city'] ?? '—') ?>
                                        &nbsp;·&nbsp;
                                        <span style="font-size:.78rem;color:#64748b;"><?= e($job['contract_type'] ?? '—') ?></span>
                                    </p>
                                </div>
                                <div style="display:flex;align-items:center;gap:.65rem;flex-shrink:0;">
                                    <span class="status-pill status-<?= $jobStatusPill ?>">
                                        <?= status_label_fr($jobStatus) ?>
                                    </span>
                                    <span style="font-size:.8rem;color:#64748b;white-space:nowrap;">
                                        <i class="fas fa-users"></i> <?= (int) ($job['application_count'] ?? 0) ?> candidature<?= ((int)($job['application_count']??0)) !== 1 ? 's' : '' ?>
                                    </span>
                                </div>
                            </div>
                            <div class="application-item-meta" style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                                <form method="post" class="inline-status-form">
                                    <input type="hidden" name="_csrf"   value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="update_job_status">
                                    <input type="hidden" name="id"     value="<?= (int) $job['id'] ?>">
                                    <select name="status">
                                        <?php foreach (['published', 'draft', 'closed'] as $s): ?>
                                            <option value="<?= e($s) ?>" <?= $jobStatus === $s ? 'selected' : '' ?>><?= status_label_fr($s) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-outline">Changer le statut</button>
                                </form>

                                <form method="post" class="confirm-delete"
                                      onsubmit="return confirm('Supprimer cette offre et toutes ses candidatures ?');">
                                    <input type="hidden" name="_csrf"   value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="delete_job">
                                    <input type="hidden" name="id"     value="<?= (int) $job['id'] ?>">
                                    <button type="submit">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>

                                <?php if (!empty($job['created_at'])): ?>
                                    <span style="font-size:.78rem;color:#94a3b8;margin-left:auto;white-space:nowrap;">
                                        <i class="far fa-calendar"></i> Publiée le <?= e(format_datetime($job['created_at'], 'd/m/Y')) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </article>

        <?php endif; // end jobs ?>

        <!-- ================================================================
             TAB: APPLICATIONS
             ================================================================ -->
        <?php if ($tab === 'applications'): ?>

        <article class="surface-card">
            <div class="surface-head">
                <div>
                    <p class="section-kicker">Suivi des candidatures</p>
                    <h2>Toutes les candidatures</h2>
                    <p class="surface-subtitle">Filtrez par statut et mettez à jour chaque candidature directement depuis cette vue.</p>
                </div>
                <span style="font-size:.875rem;font-weight:700;color:#64748b;"><?= count($allApplications) ?> résultat<?= count($allApplications) !== 1 ? 's' : '' ?></span>
            </div>

            <form method="get" class="admin-filter-bar">
                <input type="hidden" name="tab" value="applications">
                <label for="filter-status-app">Statut</label>
                <select id="filter-status-app" name="status">
                    <option value="">Tous les statuts</option>
                    <?php foreach (['submitted', 'reviewing', 'interview', 'accepted', 'rejected'] as $s): ?>
                        <option value="<?= e($s) ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= status_label_fr($s) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary"><i class="fas fa-magnifying-glass"></i> Filtrer</button>
                <?php if ($statusFilter): ?>
                    <a href="?tab=applications" class="btn btn-outline">Réinitialiser</a>
                <?php endif; ?>
            </form>

            <?php if (!$allApplications): ?>
                <div class="admin-empty">
                    <div class="admin-empty-icon"><i class="fas fa-paper-plane"></i></div>
                    <h3>Aucune candidature</h3>
                    <p>Aucune candidature ne correspond aux critères sélectionnés.</p>
                </div>
            <?php else: ?>
                <div class="application-list">
                    <?php foreach ($allApplications as $app):
                        $appStatus   = (string) ($app['status'] ?? 'submitted');
                        $appInitials = mb_strtoupper(
                            mb_substr((string)($app['first_name'] ?? 'U'), 0, 1) .
                            mb_substr((string)($app['last_name']  ?? ''), 0, 1)
                        );
                    ?>
                        <article class="application-item">
                            <div class="application-item-head">
                                <div style="display:flex;align-items:flex-start;gap:.9rem;min-width:0;">
                                    <div class="user-avatar-initials role-candidate" style="margin-top:2px;"><?= e($appInitials) ?></div>
                                    <div style="min-width:0;">
                                        <h3><?= e(($app['first_name'] ?? '') . ' ' . ($app['last_name'] ?? '')) ?></h3>
                                        <p>
                                            <i class="fas fa-envelope"  style="color:#94a3b8;"></i> <?= e($app['email'] ?? '—') ?>
                                            &nbsp;·&nbsp;
                                            <i class="fas fa-briefcase" style="color:#94a3b8;"></i> <?= e($app['job_title'] ?? '—') ?>
                                            &nbsp;·&nbsp;
                                            <i class="fas fa-building"  style="color:#94a3b8;"></i> <?= e($app['company_name'] ?? '—') ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="status-pill status-<?= e($appStatus) ?>"><?= status_label_fr($appStatus) ?></span>
                            </div>
                            <div class="application-item-meta" style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                                <form method="post" class="inline-status-form">
                                    <input type="hidden" name="_csrf"   value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="update_app_status">
                                    <input type="hidden" name="id"     value="<?= (int) $app['id'] ?>">
                                    <select name="status">
                                        <?php foreach (['submitted', 'reviewing', 'interview', 'accepted', 'rejected'] as $s): ?>
                                            <option value="<?= e($s) ?>" <?= $appStatus === $s ? 'selected' : '' ?>><?= status_label_fr($s) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-outline">Mettre à jour</button>
                                </form>

                                <?php if (!empty($app['applied_at'])): ?>
                                    <span style="font-size:.78rem;color:#94a3b8;margin-left:auto;white-space:nowrap;">
                                        <i class="far fa-clock"></i> <?= e(format_datetime($app['applied_at'], 'd/m/Y H:i')) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </article>

        <?php endif; // end applications ?>

        <!-- ================================================================
             TAB: MESSAGES
             ================================================================ -->
        <?php if ($tab === 'messages'): ?>

        <article class="surface-card">
            <div class="surface-head">
                <div>
                    <p class="section-kicker">Support & contact</p>
                    <h2>Messages de contact</h2>
                    <p class="surface-subtitle">Tous les messages reçus via le formulaire de contact. Supprimez chaque message après traitement.</p>
                </div>
                <span style="font-size:.875rem;font-weight:700;color:#64748b;"><?= count($allMessages) ?> message<?= count($allMessages) !== 1 ? 's' : '' ?></span>
            </div>

            <?php if (!$allMessages): ?>
                <div class="admin-empty">
                    <div class="admin-empty-icon"><i class="fas fa-envelope-open"></i></div>
                    <h3>Boîte de réception vide</h3>
                    <p>Aucun message de contact reçu pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="application-list">
                    <?php foreach ($allMessages as $msg):
                        $msgInitials = mb_strtoupper(mb_substr(trim((string)($msg['name'] ?? 'M')), 0, 2));
                    ?>
                        <article class="application-item">
                            <div class="application-item-head">
                                <div style="display:flex;align-items:flex-start;gap:.9rem;min-width:0;">
                                    <div class="user-avatar-initials" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);border-color:rgba(124,58,237,0.2);margin-top:2px;">
                                        <?= e($msgInitials) ?>
                                    </div>
                                    <div style="min-width:0;">
                                        <h3><?= e($msg['name'] ?? '—') ?></h3>
                                        <p>
                                            <i class="fas fa-envelope" style="color:#94a3b8;"></i> <?= e($msg['email'] ?? '—') ?>
                                            <?php if (!empty($msg['subject'])): ?>
                                                &nbsp;·&nbsp;
                                                <i class="fas fa-tag" style="color:#94a3b8;"></i> <?= e($msg['subject']) ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <?php if (!empty($msg['created_at'])): ?>
                                    <span style="font-size:.78rem;color:#94a3b8;white-space:nowrap;flex-shrink:0;">
                                        <i class="far fa-calendar"></i> <?= e(format_datetime($msg['created_at'], 'd/m/Y H:i')) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($msg['message'])): ?>
                                <div style="margin:.5rem 0 .75rem;padding:.75rem 1rem;background:#f8fafc;border-radius:.6rem;border-left:3px solid #e94560;font-size:.875rem;color:#475569;line-height:1.65;">
                                    <?= e(mb_strimwidth((string) $msg['message'], 0, 320, '…')) ?>
                                </div>
                            <?php endif; ?>

                            <div class="application-item-meta">
                                <form method="post" class="confirm-delete"
                                      onsubmit="return confirm('Supprimer ce message définitivement ?');">
                                    <input type="hidden" name="_csrf"   value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="delete_message">
                                    <input type="hidden" name="id"     value="<?= (int) $msg['id'] ?>">
                                    <button type="submit">
                                        <i class="fas fa-trash"></i> Supprimer le message
                                    </button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </article>

        <?php endif; // end messages ?>

    </div>
</section>

<?php require __DIR__ . '/../app/views/footer.php'; ?>
