SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS company_profiles;
DROP TABLE IF EXISTS candidate_profiles;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('candidate', 'company', 'admin') NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    city VARCHAR(120) DEFAULT NULL,
    country VARCHAR(120) DEFAULT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(140) NOT NULL UNIQUE,
    icon VARCHAR(80) DEFAULT NULL,
    description VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE candidate_profiles (
    user_id INT PRIMARY KEY,
    birth_date DATE DEFAULT NULL,
    headline VARCHAR(180) DEFAULT NULL,
    experience_level VARCHAR(50) DEFAULT NULL,
    skills TEXT DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    cv_filename VARCHAR(255) DEFAULT NULL,
    linkedin_url VARCHAR(255) DEFAULT NULL,
    github_url VARCHAR(255) DEFAULT NULL,
    portfolio_url VARCHAR(255) DEFAULT NULL,
    alerts_enabled TINYINT(1) NOT NULL DEFAULT 1,
    visibility TINYINT(1) NOT NULL DEFAULT 1,
    newsletter_enabled TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_candidate_profile_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE company_profiles (
    user_id INT PRIMARY KEY,
    company_name VARCHAR(190) NOT NULL,
    industry VARCHAR(120) DEFAULT NULL,
    company_size VARCHAR(50) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    website_url VARCHAR(255) DEFAULT NULL,
    founded_year INT DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    postal_code VARCHAR(50) DEFAULT NULL,
    city VARCHAR(120) DEFAULT NULL,
    country VARCHAR(120) DEFAULT NULL,
    contact_name VARCHAR(190) DEFAULT NULL,
    contact_role VARCHAR(120) DEFAULT NULL,
    contact_email VARCHAR(190) DEFAULT NULL,
    contact_phone VARCHAR(50) DEFAULT NULL,
    linkedin_url VARCHAR(255) DEFAULT NULL,
    twitter_url VARCHAR(255) DEFAULT NULL,
    facebook_url VARCHAR(255) DEFAULT NULL,
    public_profile TINYINT(1) NOT NULL DEFAULT 1,
    application_notifications TINYINT(1) NOT NULL DEFAULT 1,
    newsletter_enabled TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_company_profile_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_user_id INT NOT NULL,
    category_id INT DEFAULT NULL,
    title VARCHAR(190) NOT NULL,
    contract_type VARCHAR(80) NOT NULL,
    city VARCHAR(120) NOT NULL,
    country VARCHAR(120) NOT NULL,
    remote_allowed TINYINT(1) NOT NULL DEFAULT 0,
    salary_min DECIMAL(12,2) DEFAULT NULL,
    salary_max DECIMAL(12,2) DEFAULT NULL,
    salary_period VARCHAR(50) DEFAULT NULL,
    description TEXT NOT NULL,
    responsibilities TEXT DEFAULT NULL,
    requirements TEXT DEFAULT NULL,
    bonus_skills TEXT DEFAULT NULL,
    benefits TEXT DEFAULT NULL,
    experience_level VARCHAR(80) DEFAULT NULL,
    education_level VARCHAR(80) DEFAULT NULL,
    tags VARCHAR(255) DEFAULT NULL,
    expires_at DATE DEFAULT NULL,
    status ENUM('draft', 'published', 'closed') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_jobs_company FOREIGN KEY (company_user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_jobs_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    candidate_user_id INT NOT NULL,
    cover_letter TEXT DEFAULT NULL,
    status ENUM('submitted', 'reviewing', 'interview', 'accepted', 'rejected') NOT NULL DEFAULT 'submitted',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_job_candidate (job_id, candidate_user_id),
    CONSTRAINT fk_applications_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    CONSTRAINT fk_applications_candidate FOREIGN KEY (candidate_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(190) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    subject VARCHAR(120) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (name, slug, icon, description) VALUES
('Développement', 'developpement', 'fas fa-code', 'Backend, frontend, mobile et architecture logicielle'),
('Design', 'design', 'fas fa-paint-brush', 'Produit, identité visuelle et UX'),
('Marketing', 'marketing', 'fas fa-chart-line', 'Croissance, contenu et acquisition'),
('Finance', 'finance', 'fas fa-wallet', 'Finance d''entreprise, comptabilité et contrôle'),
('Ressources Humaines', 'rh', 'fas fa-users', 'Talent acquisition, RH et people ops'),
('IT & Réseau', 'it-reseau', 'fas fa-network-wired', 'Infrastructure, support et cybersécurité');

INSERT INTO users (id, role, email, password_hash, first_name, last_name, phone, city, country) VALUES
(1, 'company', 'demo@jobhub.cm', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Grace', 'Tech', '+237 699 00 00 00', 'Douala', 'Cameroun'),
(2, 'candidate', 'talent@jobhub.cm', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Naomi', 'Talent', '+237 677 00 00 00', 'Yaoundé', 'Cameroun'),
(3, 'admin', 'admin@jobhub.cm', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'JobHub', '+237 699 99 99 99', 'Douala', 'Cameroun');

INSERT INTO company_profiles (user_id, company_name, industry, company_size, description, website_url, founded_year, address, postal_code, city, country, contact_name, contact_role, contact_email, contact_phone, linkedin_url, public_profile, application_notifications, newsletter_enabled) VALUES
(1, 'JobHub Studio', 'Technologie RH', '11-50', 'Entreprise spécialisée dans les plateformes de recrutement et les produits digitaux à fort impact.', 'https://jobhub.example', 2021, 'Bonanjo, Boulevard de la Liberté', '0000', 'Douala', 'Cameroun', 'Grace Tech', 'Fondatrice', 'demo@jobhub.cm', '+237 699 00 00 00', 'https://linkedin.com/company/jobhub-studio', 1, 1, 1);

INSERT INTO candidate_profiles (user_id, birth_date, headline, experience_level, skills, bio, linkedin_url, github_url, portfolio_url, alerts_enabled, visibility, newsletter_enabled) VALUES
(2, '1998-04-12', 'Développeuse Full Stack', '3-5', 'PHP, MySQL, JavaScript, UI, APIs', 'Profil démo prêt à postuler et à enrichir.', 'https://linkedin.com/in/naomi-talent', 'https://github.com/naomi-talent', 'https://portfolio.example', 1, 1, 1);

INSERT INTO jobs (company_user_id, category_id, title, contract_type, city, country, remote_allowed, salary_min, salary_max, salary_period, description, responsibilities, requirements, bonus_skills, benefits, experience_level, education_level, tags, expires_at, status) VALUES
(1, 1, 'Développeur PHP Full Stack', 'CDI', 'Douala', 'Cameroun', 1, 450000, 700000, 'mois', 'Nous recherchons un développeur PHP capable de piloter des interfaces métiers robustes et élégantes.', 'Construire des modules PHP, maintenir la base de données, améliorer l''expérience recruteur et candidat.', 'Très bonne maîtrise de PHP, MySQL, HTML/CSS et architecture MVC.', 'Expérience produit, UX et performance SQL.', 'Télétravail partiel, prime de performance, budget formation.', '3-5', 'Bac+3', 'PHP,MySQL,MVC,UX', DATE_ADD(CURDATE(), INTERVAL 45 DAY), 'published'),
(1, 3, 'Growth Marketing Manager', 'CDI', 'Yaoundé', 'Cameroun', 0, 350000, 550000, 'mois', 'Piloter la croissance et les campagnes d''acquisition de la marque JobHub.', 'Lancer des campagnes, suivre les KPIs, améliorer le funnel d''inscription.', 'Maîtrise analytics, marketing digital et copywriting.', 'Connaissances en produit SaaS.', 'Prime trimestrielle, budget ads, équipe ambitieuse.', '3-5', 'Bac+3', 'Marketing,Growth,Analytics', DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'published');
