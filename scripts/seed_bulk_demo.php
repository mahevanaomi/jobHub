<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$pdo = db();
$passwordHash = password_hash('password', PASSWORD_DEFAULT);

$companyCount = 24;
$candidateCount = 48;
$jobsPerCompany = 5;
$applicationTarget = 220;

$industries = [
    'Technologie', 'Finance', 'Santé', 'Éducation', 'Logistique', 'Industrie',
    'Retail', 'Télécom', 'RH', 'Marketing', 'Média', 'Cybersécurité',
];
$cities = ['Douala', 'Yaoundé', 'Bafoussam', 'Kribi', 'Garoua', 'Bertoua', 'Ngaoundéré'];
$companySizes = ['1-10', '11-50', '51-200', '201-500', '500+'];
$contractTypes = ['CDI', 'CDD', 'Stage', 'Freelance', 'Alternance'];
$experienceLevels = ['0-1', '1-3', '3-5', '5-10', '10+'];
$educationLevels = ['Bac', 'Bac+2', 'Bac+3', 'Bac+5', 'Doctorat'];
$firstNames = ['Alice', 'Brice', 'Chantal', 'David', 'Elise', 'Fabrice', 'Grace', 'Herve', 'Ines', 'Junior', 'Karen', 'Lionel', 'Merveille', 'Nadine', 'Ornella', 'Patrick', 'Quentin', 'Ruth', 'Sonia', 'Thierry', 'Ulrich', 'Vanessa', 'Willy', 'Yann', 'Zita'];
$lastNames = ['Nkoa', 'Tchoumi', 'Essomba', 'Ngono', 'Kamga', 'Biya', 'Mba', 'Fouda', 'Ngassam', 'Mbappe', 'Kouam', 'Njoya'];
$headlines = ['Développeur Full Stack', 'Designer Produit', 'Analyste Marketing', 'Administrateur Réseau', 'Comptable Senior', 'Chef de projet digital'];
$skillsPool = ['PHP', 'MySQL', 'JavaScript', 'Laravel', 'Docker', 'UI', 'UX', 'SEO', 'Réseau', 'Linux', 'Excel', 'PowerBI', 'Node.js', 'React'];
$statuses = ['submitted', 'reviewing', 'interview', 'accepted', 'rejected'];

function random_item(array $items): mixed
{
    return $items[array_rand($items)];
}

function random_name(array $firstNames, array $lastNames): array
{
    return [random_item($firstNames), random_item($lastNames)];
}

function random_skills(array $skillsPool, int $min = 4, int $max = 7): string
{
    shuffle($skillsPool);
    return implode(',', array_slice($skillsPool, 0, random_int($min, $max)));
}

$pdo->beginTransaction();

$pdo->exec("DELETE a FROM applications a INNER JOIN users u ON u.id = a.candidate_user_id WHERE u.email LIKE 'bulk.candidate.%@jobhub.cm'");
$pdo->exec("DELETE j FROM jobs j INNER JOIN users u ON u.id = j.company_user_id WHERE u.email LIKE 'bulk.company.%@jobhub.cm'");
$pdo->exec("DELETE cp FROM candidate_profiles cp INNER JOIN users u ON u.id = cp.user_id WHERE u.email LIKE 'bulk.candidate.%@jobhub.cm'");
$pdo->exec("DELETE cp FROM company_profiles cp INNER JOIN users u ON u.id = cp.user_id WHERE u.email LIKE 'bulk.company.%@jobhub.cm'");
$pdo->exec("DELETE FROM users WHERE email LIKE 'bulk.company.%@jobhub.cm' OR email LIKE 'bulk.candidate.%@jobhub.cm'");
$pdo->exec("DELETE FROM contact_messages WHERE email LIKE 'bulk.contact.%@jobhub.cm'");

// Ensure admin account exists
$adminExists = query_one('SELECT id FROM users WHERE email = ?', ['admin@jobhub.cm']);
if (!$adminExists) {
    execute_query(
        'INSERT INTO users (role, email, password_hash, first_name, last_name, phone, city, country) VALUES ("admin", ?, ?, "Admin", "JobHub", "+237 699 99 99 99", "Douala", "Cameroun")',
        ['admin@jobhub.cm', $passwordHash]
    );
    echo "Compte admin créé: admin@jobhub.cm\n";
}

$categories = query_all('SELECT id, name FROM categories ORDER BY id');
$categoryIds = array_column($categories, 'id');

$companyIds = [];
for ($i = 1; $i <= $companyCount; $i++) {
    [$firstName, $lastName] = random_name($firstNames, $lastNames);
    $city = random_item($cities);
    $industry = random_item($industries);
    $companyName = sprintf('Bulk Company %02d %s', $i, $industry);
    $email = sprintf('bulk.company.%02d@jobhub.cm', $i);

    execute_query(
        'INSERT INTO users (role, email, password_hash, first_name, last_name, phone, city, country)
         VALUES ("company", ?, ?, ?, ?, ?, ?, "Cameroun")',
        [$email, $passwordHash, $firstName, $lastName, '+237 6' . random_int(10000000, 99999999), $city]
    );
    $userId = last_insert_id();
    $companyIds[] = $userId;

    execute_query(
        'INSERT INTO company_profiles (user_id, company_name, industry, company_size, description, website_url, founded_year, address, postal_code, city, country, contact_name, contact_role, contact_email, contact_phone, linkedin_url, public_profile, application_notifications, newsletter_enabled)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "Cameroun", ?, "Responsable RH", ?, ?, ?, 1, 1, 1)',
        [
            $userId,
            $companyName,
            $industry,
            random_item($companySizes),
            $companyName . ' recrute sur des rôles à fort impact et travaille sur des projets digitaux variés.',
            'https://company-' . $i . '.example',
            random_int(2010, 2025),
            'Avenue ' . random_int(1, 120),
            (string) random_int(1000, 9999),
            $city,
            $firstName . ' ' . $lastName,
            $email,
            '+237 6' . random_int(10000000, 99999999),
            'https://linkedin.com/company/bulk-company-' . $i,
        ]
    );
}

$candidateIds = [];
for ($i = 1; $i <= $candidateCount; $i++) {
    [$firstName, $lastName] = random_name($firstNames, $lastNames);
    $city = random_item($cities);
    $email = sprintf('bulk.candidate.%02d@jobhub.cm', $i);

    execute_query(
        'INSERT INTO users (role, email, password_hash, first_name, last_name, phone, city, country)
         VALUES ("candidate", ?, ?, ?, ?, ?, ?, "Cameroun")',
        [$email, $passwordHash, $firstName, $lastName, '+237 6' . random_int(10000000, 99999999), $city]
    );
    $userId = last_insert_id();
    $candidateIds[] = $userId;

    execute_query(
        'INSERT INTO candidate_profiles (user_id, birth_date, headline, experience_level, skills, bio, linkedin_url, github_url, portfolio_url, alerts_enabled, visibility, newsletter_enabled)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1, 1)',
        [
            $userId,
            sprintf('%d-%02d-%02d', random_int(1989, 2002), random_int(1, 12), random_int(1, 28)),
            random_item($headlines),
            random_item($experienceLevels),
            random_skills($skillsPool),
            'Profil candidat généré pour les tests de charge et les tests fonctionnels.',
            'https://linkedin.com/in/bulk-candidate-' . $i,
            'https://github.com/bulk-candidate-' . $i,
            'https://portfolio-bulk-' . $i . '.example',
        ]
    );
}

$jobIds = [];
$jobTitles = [
    'Développeur Backend PHP', 'Développeur Frontend React', 'DevOps Engineer', 'Product Designer',
    'Data Analyst', 'Chargé Marketing Digital', 'Commercial B2B', 'Ingénieur Réseau',
    'Chef de projet SI', 'Comptable Général', 'Talent Acquisition Specialist', 'Support Applicatif',
    'Architecte Cloud', 'UX Researcher', 'Scrum Master', 'Ingénieur QA',
    'Community Manager', 'Responsable Communication', 'Développeur Mobile Flutter',
    'DBA MySQL/PostgreSQL', 'Administrateur Systèmes Linux', 'Analyste Cybersécurité',
    'Lead Développeur Full Stack', 'Product Owner', 'Growth Hacker', 'Directeur Technique',
];

foreach ($companyIds as $companyId) {
    for ($j = 1; $j <= $jobsPerCompany; $j++) {
        $title = random_item($jobTitles);
        $city = random_item($cities);
        $salaryMin = random_int(180000, 700000);
        $salaryMax = $salaryMin + random_int(50000, 350000);

        execute_query(
            'INSERT INTO jobs (company_user_id, category_id, title, contract_type, city, country, remote_allowed, salary_min, salary_max, salary_period, description, responsibilities, requirements, bonus_skills, benefits, experience_level, education_level, tags, expires_at, status)
             VALUES (?, ?, ?, ?, ?, "Cameroun", ?, ?, ?, "mois", ?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(CURDATE(), INTERVAL ? DAY), "published")',
            [
                $companyId,
                random_item($categoryIds),
                $title,
                random_item($contractTypes),
                $city,
                random_int(0, 1),
                $salaryMin,
                $salaryMax,
                'Nous recherchons un profil autonome, rigoureux et orienté résultat pour renforcer une équipe en croissance.',
                "Participer aux rituels d'équipe\nLivrer des fonctionnalités\nMaintenir un haut niveau de qualité",
                "Maîtrise du poste ciblé\nBon relationnel\nCapacité d'analyse",
                "Expérience SaaS\nBonne culture produit",
                "Prime de performance\nTélétravail partiel\nMutuelle",
                random_item($experienceLevels),
                random_item($educationLevels),
                random_skills($skillsPool, 3, 6),
                random_int(20, 90),
            ]
        );
        $jobIds[] = last_insert_id();
    }
}

shuffle($jobIds);
shuffle($candidateIds);

$pairs = [];
$applicationsCreated = 0;
while ($applicationsCreated < $applicationTarget) {
    $candidateId = $candidateIds[array_rand($candidateIds)];
    $jobId = $jobIds[array_rand($jobIds)];
    $pairKey = $candidateId . ':' . $jobId;

    if (isset($pairs[$pairKey])) {
        continue;
    }

    $pairs[$pairKey] = true;

    $coverLetters = [
        'Je suis très motivé(e) par ce poste et je pense que mes compétences correspondent parfaitement au profil recherché. Mon expérience dans le domaine me permettrait de contribuer rapidement à vos projets.',
        'Passionné(e) par les défis techniques, je souhaite rejoindre votre équipe pour apporter ma vision et mon expertise. Je suis disponible immédiatement pour un entretien.',
        'Fort(e) de plusieurs années d\'expérience, je suis convaincu(e) que je peux apporter une vraie valeur ajoutée à votre organisation. N\'hésitez pas à me contacter pour échanger.',
        'Votre offre a retenu toute mon attention. Mon parcours diversifié et ma polyvalence me permettent de m\'adapter rapidement à de nouveaux environnements de travail.',
        'Je me permets de postuler car cette opportunité correspond exactement à mon projet professionnel. Je serais ravi(e) d\'en discuter lors d\'un entretien.',
        '',
    ];

    execute_query(
        'INSERT INTO applications (job_id, candidate_user_id, cover_letter, status, applied_at)
         VALUES (?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL ? DAY))',
        [
            $jobId,
            $candidateId,
            random_item($coverLetters),
            random_item($statuses),
            random_int(0, 21),
        ]
    );
    $applicationsCreated++;
}

$contactSubjects = ['Question candidat', 'Question entreprise', 'Support technique', 'Partenariat', 'Signalement de bug', 'Demande de fonctionnalité', 'Problème de connexion', 'Facturation'];
$contactMessages = [
    'Bonjour, je n\'arrive pas à téléverser mon CV depuis mon téléphone. Le bouton ne répond pas. Pouvez-vous m\'aider ?',
    'Nous aimerions savoir s\'il est possible de publier plus de 10 offres simultanément. Merci de nous contacter.',
    'Je souhaiterais supprimer mon compte et toutes mes données personnelles. Pouvez-vous me guider dans cette démarche ?',
    'Votre plateforme est excellente ! Nous aimerions explorer un partenariat pour nos recrutements au Cameroun.',
    'J\'ai postulé à une offre il y a 3 semaines et je n\'ai toujours pas de retour. Est-ce normal ?',
    'Le formulaire d\'inscription affiche une erreur quand j\'utilise un email avec un accent. Merci de corriger.',
    'Serait-il possible d\'ajouter un filtre par salaire dans la recherche d\'offres ? Ce serait très utile.',
    'Mon profil entreprise ne s\'affiche pas correctement sur mobile. Les images sont coupées.',
    'Bravo pour la plateforme ! C\'est exactement ce qu\'il manquait au Cameroun. Continuez ainsi !',
    'Je voudrais savoir si vous proposez un plan premium pour les entreprises avec des fonctionnalités avancées.',
];

for ($i = 1; $i <= 18; $i++) {
    [$firstName, $lastName] = random_name($firstNames, $lastNames);
    execute_query(
        'INSERT INTO contact_messages (name, email, phone, subject, message, created_at)
         VALUES (?, ?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL ? DAY))',
        [
            $firstName . ' ' . $lastName,
            sprintf('bulk.contact.%02d@jobhub.cm', $i),
            '+237 6' . random_int(10000000, 99999999),
            random_item($contactSubjects),
            random_item($contactMessages),
            random_int(0, 10),
        ]
    );
}

$pdo->commit();

echo "Seed terminé.\n";
echo "Entreprises créées: {$companyCount}\n";
echo "Candidats créés: {$candidateCount}\n";
echo "Offres créées: " . count($jobIds) . "\n";
echo "Candidatures créées: {$applicationsCreated}\n";
echo "Messages créés: 18\n";
