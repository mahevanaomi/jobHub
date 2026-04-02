<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

function assert_true(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

echo "Smoke tests base de données\n";

$users = query_one('SELECT COUNT(*) AS total FROM users');
$jobs = query_one('SELECT COUNT(*) AS total FROM jobs');
$applications = query_one('SELECT COUNT(*) AS total FROM applications');
$companies = query_one('SELECT COUNT(*) AS total FROM company_profiles');
$candidates = query_one('SELECT COUNT(*) AS total FROM candidate_profiles');

assert_true((int) ($users['total'] ?? 0) >= 70, 'Volume utilisateurs insuffisant');
assert_true((int) ($companies['total'] ?? 0) >= 20, 'Volume entreprises insuffisant');
assert_true((int) ($candidates['total'] ?? 0) >= 40, 'Volume candidats insuffisant');
assert_true((int) ($jobs['total'] ?? 0) >= 100, 'Volume offres insuffisant');
assert_true((int) ($applications['total'] ?? 0) >= 150, 'Volume candidatures insuffisant');

$candidate = find_user_by_email('bulk.candidate.01@jobhub.cm');
$company = find_user_by_email('bulk.company.01@jobhub.cm');

assert_true($candidate !== null, 'Compte bulk candidat introuvable');
assert_true($company !== null, 'Compte bulk entreprise introuvable');
assert_true(authenticate('bulk.candidate.01@jobhub.cm', 'password') !== null, 'Authentification candidat KO');
assert_true(authenticate('bulk.company.01@jobhub.cm', 'password') !== null, 'Authentification entreprise KO');

$candidateStats = candidate_dashboard_stats((int) $candidate['id']);
$companyStats = company_dashboard_stats((int) $company['id']);
$recommended = get_recommended_jobs_for_candidate((int) $candidate['id']);
$companyJobs = get_company_jobs((int) $company['id']);
$companyApplications = get_company_applications((int) $company['id']);

assert_true(is_array($candidateStats), 'Stats candidat indisponibles');
assert_true(is_array($companyStats), 'Stats entreprise indisponibles');
assert_true(count($recommended) >= 1, 'Pas d\'offres recommandées');
assert_true(count($companyJobs) >= 1, 'Pas d\'offres entreprise');
assert_true(count($companyApplications) >= 1, 'Pas de candidatures entreprise');

$job = get_job_by_id((int) $companyJobs[0]['id']);
assert_true($job !== null, 'Détail offre introuvable');

echo "OK: volumes et accès métier validés.\n";
