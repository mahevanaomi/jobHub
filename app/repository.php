<?php

declare(strict_types=1);

require_once __DIR__ . '/database.php';

function query_all(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function query_one(string $sql, array $params = []): ?array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}

function execute_query(string $sql, array $params = []): bool
{
    $stmt = db()->prepare($sql);
    return $stmt->execute($params);
}

function last_insert_id(): int
{
    return (int) db()->lastInsertId();
}

function find_user_by_id(int $id): ?array
{
    return query_one('SELECT * FROM users WHERE id = ?', [$id]);
}

function find_user_by_email(string $email): ?array
{
    return query_one('SELECT * FROM users WHERE email = ?', [$email]);
}

function create_candidate_account(array $data): int
{
    db()->beginTransaction();

    execute_query(
        'INSERT INTO users (role, email, password_hash, first_name, last_name, phone, city, country) VALUES ("candidate", ?, ?, ?, ?, ?, ?, ?)',
        [
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['city'],
            $data['country'],
        ]
    );

    $userId = last_insert_id();

    execute_query(
        'INSERT INTO candidate_profiles (user_id, headline, experience_level, skills, bio, linkedin_url, github_url, portfolio_url, alerts_enabled, newsletter_enabled)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            $userId,
            $data['headline'],
            $data['experience_level'],
            $data['skills'],
            $data['bio'],
            $data['linkedin_url'],
            $data['github_url'],
            $data['portfolio_url'],
            !empty($data['alerts_enabled']) ? 1 : 0,
            !empty($data['newsletter_enabled']) ? 1 : 0,
        ]
    );

    db()->commit();

    return $userId;
}

function create_company_account(array $data): int
{
    db()->beginTransaction();

    execute_query(
        'INSERT INTO users (role, email, password_hash, first_name, last_name, phone, city, country) VALUES ("company", ?, ?, ?, ?, ?, ?, ?)',
        [
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['city'],
            $data['country'],
        ]
    );

    $userId = last_insert_id();

    execute_query(
        'INSERT INTO company_profiles (
            user_id, company_name, industry, company_size, description, website_url, contact_name, contact_role, contact_email, contact_phone, city, country, public_profile, application_notifications, newsletter_enabled
         ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            $userId,
            $data['company_name'],
            $data['industry'],
            $data['company_size'],
            $data['company_description'],
            $data['website_url'],
            trim($data['first_name'] . ' ' . $data['last_name']),
            'Responsable recrutement',
            $data['email'],
            $data['phone'],
            $data['city'],
            $data['country'],
            1,
            1,
            !empty($data['newsletter_enabled']) ? 1 : 0,
        ]
    );

    db()->commit();

    return $userId;
}

function authenticate(string $email, string $password): ?array
{
    $user = find_user_by_email($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return null;
    }

    return $user;
}

function get_categories(): array
{
    return query_all('SELECT * FROM categories ORDER BY name');
}

function get_featured_jobs(array $filters = []): array
{
    $sql = '
        SELECT j.*, c.name AS category_name, cp.company_name, cp.website_url
        FROM jobs j
        INNER JOIN company_profiles cp ON cp.user_id = j.company_user_id
        LEFT JOIN categories c ON c.id = j.category_id
        WHERE j.status = "published"
          AND (j.expires_at IS NULL OR j.expires_at >= CURDATE())
    ';

    $params = [];

    if (!empty($filters['search'])) {
        $sql .= ' AND (j.title LIKE ? OR cp.company_name LIKE ? OR j.tags LIKE ?)';
        $term = '%' . $filters['search'] . '%';
        array_push($params, $term, $term, $term);
    }

    if (!empty($filters['city'])) {
        $sql .= ' AND j.city LIKE ?';
        $params[] = '%' . $filters['city'] . '%';
    }

    if (!empty($filters['category'])) {
        $sql .= ' AND c.slug = ?';
        $params[] = $filters['category'];
    }

    if (!empty($filters['contract_type'])) {
        $sql .= ' AND j.contract_type = ?';
        $params[] = $filters['contract_type'];
    }

    $sql .= ' ORDER BY j.created_at DESC';

    return query_all($sql, $params);
}

function get_job_by_id(int $id): ?array
{
    return query_one(
        'SELECT j.*, c.name AS category_name, cp.company_name, cp.description AS company_description, cp.website_url, cp.industry, cp.company_size, cp.contact_email
         FROM jobs j
         INNER JOIN company_profiles cp ON cp.user_id = j.company_user_id
         LEFT JOIN categories c ON c.id = j.category_id
         WHERE j.id = ?',
        [$id]
    );
}

function get_company_jobs(int $companyUserId): array
{
    return query_all(
        'SELECT j.*, c.name AS category_name,
                (SELECT COUNT(*) FROM applications a WHERE a.job_id = j.id) AS application_count
         FROM jobs j
         LEFT JOIN categories c ON c.id = j.category_id
         WHERE j.company_user_id = ?
         ORDER BY j.created_at DESC',
        [$companyUserId]
    );
}

function get_candidate_profile(int $userId): ?array
{
    return query_one(
        'SELECT u.*, cp.*
         FROM users u
         INNER JOIN candidate_profiles cp ON cp.user_id = u.id
         WHERE u.id = ?',
        [$userId]
    );
}

function get_company_profile(int $userId): ?array
{
    return query_one(
        'SELECT u.*, cp.*
         FROM users u
         INNER JOIN company_profiles cp ON cp.user_id = u.id
         WHERE u.id = ?',
        [$userId]
    );
}

function update_candidate_profile(int $userId, array $data): void
{
    db()->beginTransaction();

    execute_query(
        'UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, city = ?, country = ? WHERE id = ?',
        [$data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['city'], $data['country'], $userId]
    );

    execute_query(
        'UPDATE candidate_profiles
         SET birth_date = ?, headline = ?, experience_level = ?, skills = ?, bio = ?, cv_filename = COALESCE(?, cv_filename), linkedin_url = ?, github_url = ?, portfolio_url = ?, alerts_enabled = ?, visibility = ?, newsletter_enabled = ?
         WHERE user_id = ?',
        [
            $data['birth_date'] ?: null,
            $data['headline'],
            $data['experience_level'],
            $data['skills'],
            $data['bio'],
            $data['cv_filename'] ?? null,
            $data['linkedin_url'],
            $data['github_url'],
            $data['portfolio_url'],
            !empty($data['alerts_enabled']) ? 1 : 0,
            !empty($data['visibility']) ? 1 : 0,
            !empty($data['newsletter_enabled']) ? 1 : 0,
            $userId,
        ]
    );

    db()->commit();
}

function update_company_profile(int $userId, array $data): void
{
    db()->beginTransaction();

    execute_query(
        'UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, city = ?, country = ?, avatar = COALESCE(?, avatar) WHERE id = ?',
        [$data['first_name'], $data['last_name'], $data['email'], $data['phone'], $data['city'], $data['country'], $data['avatar'] ?? null, $userId]
    );

    execute_query(
        'UPDATE company_profiles
         SET company_name = ?, industry = ?, company_size = ?, description = ?, website_url = ?, founded_year = ?, address = ?, postal_code = ?, city = ?, country = ?, contact_name = ?, contact_role = ?, contact_email = ?, contact_phone = ?, linkedin_url = ?, twitter_url = ?, facebook_url = ?, public_profile = ?, application_notifications = ?, newsletter_enabled = ?
         WHERE user_id = ?',
        [
            $data['company_name'],
            $data['industry'],
            $data['company_size'],
            $data['description'],
            $data['website_url'],
            $data['founded_year'] ?: null,
            $data['address'],
            $data['postal_code'],
            $data['city'],
            $data['country'],
            $data['contact_name'],
            $data['contact_role'],
            $data['contact_email'],
            $data['contact_phone'],
            $data['linkedin_url'],
            $data['twitter_url'],
            $data['facebook_url'],
            !empty($data['public_profile']) ? 1 : 0,
            !empty($data['application_notifications']) ? 1 : 0,
            !empty($data['newsletter_enabled']) ? 1 : 0,
            $userId,
        ]
    );

    db()->commit();
}

function create_job(int $companyUserId, array $data): int
{
    execute_query(
        'INSERT INTO jobs (
            company_user_id, category_id, title, contract_type, city, country, remote_allowed, salary_min, salary_max, salary_period, description, responsibilities, requirements, bonus_skills, benefits, experience_level, education_level, tags, expires_at, status
         ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "published")',
        [
            $companyUserId,
            $data['category_id'],
            $data['title'],
            $data['contract_type'],
            $data['city'],
            $data['country'],
            !empty($data['remote_allowed']) ? 1 : 0,
            $data['salary_min'] ?: null,
            $data['salary_max'] ?: null,
            $data['salary_period'],
            $data['description'],
            $data['responsibilities'],
            $data['requirements'],
            $data['bonus_skills'],
            $data['benefits'],
            $data['experience_level'],
            $data['education_level'],
            $data['tags'],
            $data['expires_at'] ?: null,
        ]
    );

    return last_insert_id();
}

function update_job(int $jobId, int $companyUserId, array $data): void
{
    execute_query(
        'UPDATE jobs
         SET category_id = ?, title = ?, contract_type = ?, city = ?, country = ?, remote_allowed = ?, salary_min = ?, salary_max = ?, salary_period = ?, description = ?, responsibilities = ?, requirements = ?, bonus_skills = ?, benefits = ?, experience_level = ?, education_level = ?, tags = ?, expires_at = ?, status = "published"
         WHERE id = ? AND company_user_id = ?',
        [
            $data['category_id'],
            $data['title'],
            $data['contract_type'],
            $data['city'],
            $data['country'],
            !empty($data['remote_allowed']) ? 1 : 0,
            $data['salary_min'] ?: null,
            $data['salary_max'] ?: null,
            $data['salary_period'],
            $data['description'],
            $data['responsibilities'],
            $data['requirements'],
            $data['bonus_skills'],
            $data['benefits'],
            $data['experience_level'],
            $data['education_level'],
            $data['tags'],
            $data['expires_at'] ?: null,
            $jobId,
            $companyUserId,
        ]
    );
}

function get_job_for_company(int $jobId, int $companyUserId): ?array
{
    return query_one('SELECT * FROM jobs WHERE id = ? AND company_user_id = ?', [$jobId, $companyUserId]);
}

function get_candidate_applications(int $candidateUserId): array
{
    return query_all(
        'SELECT a.*, j.title, j.city, j.country, cp.company_name
         FROM applications a
         INNER JOIN jobs j ON j.id = a.job_id
         INNER JOIN company_profiles cp ON cp.user_id = j.company_user_id
         WHERE a.candidate_user_id = ?
         ORDER BY a.applied_at DESC',
        [$candidateUserId]
    );
}

function get_company_applications(int $companyUserId): array
{
    return query_all(
        'SELECT a.*, j.title, u.first_name, u.last_name, u.email, u.phone, cp.headline
         FROM applications a
         INNER JOIN jobs j ON j.id = a.job_id
         INNER JOIN users u ON u.id = a.candidate_user_id
         LEFT JOIN candidate_profiles cp ON cp.user_id = u.id
         WHERE j.company_user_id = ?
         ORDER BY a.applied_at DESC',
        [$companyUserId]
    );
}

function has_applied(int $jobId, int $candidateUserId): bool
{
    return query_one('SELECT id FROM applications WHERE job_id = ? AND candidate_user_id = ?', [$jobId, $candidateUserId]) !== null;
}

function create_application(int $jobId, int $candidateUserId, string $coverLetter = ''): void
{
    execute_query(
        'INSERT INTO applications (job_id, candidate_user_id, cover_letter, status) VALUES (?, ?, ?, "submitted")',
        [$jobId, $candidateUserId, $coverLetter]
    );
}

function candidate_dashboard_stats(int $candidateUserId): array
{
    $stats = query_one(
        'SELECT
            COUNT(*) AS total,
            SUM(status = "submitted") AS submitted,
            SUM(status = "reviewing") AS reviewing,
            SUM(status = "interview") AS interview
         FROM applications
         WHERE candidate_user_id = ?',
        [$candidateUserId]
    ) ?: [];

    $stats['saved'] = 0;
    return $stats;
}

function company_dashboard_stats(int $companyUserId): array
{
    $jobCount = query_one('SELECT COUNT(*) AS total FROM jobs WHERE company_user_id = ?', [$companyUserId]);
    $applicationCount = query_one(
        'SELECT COUNT(*) AS total FROM applications a INNER JOIN jobs j ON j.id = a.job_id WHERE j.company_user_id = ?',
        [$companyUserId]
    );
    $publishedCount = query_one('SELECT COUNT(*) AS total FROM jobs WHERE company_user_id = ? AND status = "published"', [$companyUserId]);
    $interviewCount = query_one(
        'SELECT COUNT(*) AS total FROM applications a INNER JOIN jobs j ON j.id = a.job_id WHERE j.company_user_id = ? AND a.status = "interview"',
        [$companyUserId]
    );

    return [
        'jobs' => (int) ($jobCount['total'] ?? 0),
        'applications' => (int) ($applicationCount['total'] ?? 0),
        'published' => (int) ($publishedCount['total'] ?? 0),
        'interview' => (int) ($interviewCount['total'] ?? 0),
    ];
}

function get_recommended_jobs_for_candidate(int $candidateUserId, int $limit = 6): array
{
    return query_all(
        'SELECT j.*, c.name AS category_name, cp.company_name
         FROM jobs j
         INNER JOIN company_profiles cp ON cp.user_id = j.company_user_id
         LEFT JOIN categories c ON c.id = j.category_id
         WHERE j.status = "published"
           AND j.id NOT IN (SELECT job_id FROM applications WHERE candidate_user_id = ?)
         ORDER BY j.created_at DESC
         LIMIT ' . (int) $limit,
        [$candidateUserId]
    );
}

function store_contact_message(array $data): void
{
    execute_query(
        'INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)',
        [$data['name'], $data['email'], $data['phone'], $data['subject'], $data['message']]
    );
}

function update_application_status(int $companyUserId, int $applicationId, string $status): void
{
    $allowed = ['submitted', 'reviewing', 'interview', 'accepted', 'rejected'];
    if (!in_array($status, $allowed, true)) {
        throw new InvalidArgumentException('Statut invalide.');
    }

    execute_query(
        'UPDATE applications a
         INNER JOIN jobs j ON j.id = a.job_id
         SET a.status = ?
         WHERE a.id = ? AND j.company_user_id = ?',
        [$status, $applicationId, $companyUserId]
    );
}

// ── Admin functions ──────────────────────────────────────────────

function admin_global_stats(): array
{
    $users = query_one('SELECT COUNT(*) AS total, SUM(role="candidate") AS candidates, SUM(role="company") AS companies FROM users WHERE role != "admin"');
    $jobs = query_one('SELECT COUNT(*) AS total, SUM(status="published") AS published, SUM(status="draft") AS draft, SUM(status="closed") AS closed FROM jobs');
    $apps = query_one('SELECT COUNT(*) AS total, SUM(status="submitted") AS submitted, SUM(status="reviewing") AS reviewing, SUM(status="interview") AS interview, SUM(status="accepted") AS accepted, SUM(status="rejected") AS rejected FROM applications');
    $messages = query_one('SELECT COUNT(*) AS total FROM contact_messages');
    $categories = query_one('SELECT COUNT(*) AS total FROM categories');

    return [
        'users' => $users,
        'jobs' => $jobs,
        'applications' => $apps,
        'messages' => (int) ($messages['total'] ?? 0),
        'categories' => (int) ($categories['total'] ?? 0),
    ];
}

function admin_recent_users(int $limit = 10): array
{
    return query_all('SELECT u.*, CASE WHEN u.role = "company" THEN (SELECT cp.company_name FROM company_profiles cp WHERE cp.user_id = u.id) ELSE (SELECT cp.headline FROM candidate_profiles cp WHERE cp.user_id = u.id) END AS extra_info FROM users u WHERE u.role != "admin" ORDER BY u.created_at DESC LIMIT ' . (int) $limit);
}

function admin_all_users(string $role = '', string $search = ''): array
{
    $sql = 'SELECT u.*, CASE WHEN u.role = "company" THEN (SELECT cp.company_name FROM company_profiles cp WHERE cp.user_id = u.id) ELSE (SELECT cp.headline FROM candidate_profiles cp WHERE cp.user_id = u.id) END AS extra_info FROM users u WHERE u.role != "admin"';
    $params = [];

    if ($role) {
        $sql .= ' AND u.role = ?';
        $params[] = $role;
    }
    if ($search) {
        $sql .= ' AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?)';
        $term = '%' . $search . '%';
        array_push($params, $term, $term, $term);
    }

    $sql .= ' ORDER BY u.created_at DESC';
    return query_all($sql, $params);
}

function admin_all_jobs(string $status = '', string $search = ''): array
{
    $sql = 'SELECT j.*, c.name AS category_name, cp.company_name, (SELECT COUNT(*) FROM applications a WHERE a.job_id = j.id) AS application_count FROM jobs j INNER JOIN company_profiles cp ON cp.user_id = j.company_user_id LEFT JOIN categories c ON c.id = j.category_id WHERE 1=1';
    $params = [];

    if ($status) {
        $sql .= ' AND j.status = ?';
        $params[] = $status;
    }
    if ($search) {
        $sql .= ' AND (j.title LIKE ? OR cp.company_name LIKE ?)';
        $term = '%' . $search . '%';
        array_push($params, $term, $term);
    }

    $sql .= ' ORDER BY j.created_at DESC';
    return query_all($sql, $params);
}

function admin_all_applications(string $status = ''): array
{
    $sql = 'SELECT a.*, j.title AS job_title, j.city AS job_city, cp.company_name, u.first_name, u.last_name, u.email AS candidate_email, can.headline FROM applications a INNER JOIN jobs j ON j.id = a.job_id INNER JOIN company_profiles cp ON cp.user_id = j.company_user_id INNER JOIN users u ON u.id = a.candidate_user_id LEFT JOIN candidate_profiles can ON can.user_id = u.id WHERE 1=1';
    $params = [];

    if ($status) {
        $sql .= ' AND a.status = ?';
        $params[] = $status;
    }

    $sql .= ' ORDER BY a.applied_at DESC';
    return query_all($sql, $params);
}

function admin_all_messages(): array
{
    return query_all('SELECT * FROM contact_messages ORDER BY created_at DESC');
}

function admin_delete_user(int $userId): void
{
    execute_query('DELETE FROM users WHERE id = ? AND role != "admin"', [$userId]);
}

function admin_delete_job(int $jobId): void
{
    execute_query('DELETE FROM jobs WHERE id = ?', [$jobId]);
}

function admin_delete_message(int $messageId): void
{
    execute_query('DELETE FROM contact_messages WHERE id = ?', [$messageId]);
}

function admin_update_application_status(int $applicationId, string $status): void
{
    $allowed = ['submitted', 'reviewing', 'interview', 'accepted', 'rejected'];
    if (!in_array($status, $allowed, true)) {
        throw new InvalidArgumentException('Statut invalide.');
    }
    execute_query('UPDATE applications SET status = ? WHERE id = ?', [$status, $applicationId]);
}

function admin_update_job_status(int $jobId, string $status): void
{
    $allowed = ['draft', 'published', 'closed'];
    if (!in_array($status, $allowed, true)) {
        throw new InvalidArgumentException('Statut invalide.');
    }
    execute_query('UPDATE jobs SET status = ? WHERE id = ?', [$status, $jobId]);
}

function admin_monthly_registrations(int $months = 6): array
{
    return query_all(
        'SELECT DATE_FORMAT(created_at, "%Y-%m") AS month, role, COUNT(*) AS total FROM users WHERE role != "admin" AND created_at >= DATE_SUB(CURDATE(), INTERVAL ? MONTH) GROUP BY month, role ORDER BY month ASC',
        [$months]
    );
}

function admin_monthly_applications(int $months = 6): array
{
    return query_all(
        'SELECT DATE_FORMAT(applied_at, "%Y-%m") AS month, status, COUNT(*) AS total FROM applications WHERE applied_at >= DATE_SUB(CURDATE(), INTERVAL ? MONTH) GROUP BY month, status ORDER BY month ASC',
        [$months]
    );
}

function admin_top_companies(int $limit = 5): array
{
    return query_all(
        'SELECT cp.company_name, cp.user_id, COUNT(j.id) AS job_count, (SELECT COUNT(*) FROM applications a INNER JOIN jobs jj ON jj.id = a.job_id WHERE jj.company_user_id = cp.user_id) AS app_count FROM company_profiles cp LEFT JOIN jobs j ON j.company_user_id = cp.user_id GROUP BY cp.user_id ORDER BY job_count DESC LIMIT ' . (int) $limit
    );
}

function admin_jobs_by_category(): array
{
    return query_all(
        'SELECT c.name, c.icon, COUNT(j.id) AS job_count FROM categories c LEFT JOIN jobs j ON j.category_id = c.id GROUP BY c.id ORDER BY job_count DESC'
    );
}

function admin_jobs_by_city(int $limit = 8): array
{
    return query_all(
        'SELECT city, COUNT(*) AS total FROM jobs GROUP BY city ORDER BY total DESC LIMIT ' . (int) $limit
    );
}

function admin_recent_activity(int $limit = 15): array
{
    $activities = [];

    $recentApps = query_all('SELECT a.applied_at AS date, CONCAT(u.first_name, " ", u.last_name) AS who, j.title AS target, "application" AS type FROM applications a INNER JOIN users u ON u.id = a.candidate_user_id INNER JOIN jobs j ON j.id = a.job_id ORDER BY a.applied_at DESC LIMIT ' . (int) $limit);
    foreach ($recentApps as $row) { $row['type'] = 'application'; $activities[] = $row; }

    $recentUsers = query_all('SELECT created_at AS date, CONCAT(first_name, " ", last_name) AS who, role AS target, "registration" AS type FROM users WHERE role != "admin" ORDER BY created_at DESC LIMIT ' . (int) $limit);
    foreach ($recentUsers as $row) { $row['type'] = 'registration'; $activities[] = $row; }

    $recentJobs = query_all('SELECT j.created_at AS date, cp.company_name AS who, j.title AS target, "job" AS type FROM jobs j INNER JOIN company_profiles cp ON cp.user_id = j.company_user_id ORDER BY j.created_at DESC LIMIT ' . (int) $limit);
    foreach ($recentJobs as $row) { $row['type'] = 'job'; $activities[] = $row; }

    usort($activities, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

    return array_slice($activities, 0, $limit);
}
