<?php
$pageTitle = $pageTitle ?? 'JobHub';
$currentUser = current_user();
$flashMessages = consume_flash();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
    <link rel="icon" type="image/svg+xml" href="<?= e($assetBase ?? '') ?>/favicon.svg">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= e($assetBase ?? '') ?>/views/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="brand-lockup">
                    <div class="logo-mark">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="logo">
                        <a href="<?= e($rootPath ?? '/index.php') ?>">
                            <span>Job<strong>Hub</strong></span>
                            <small>Recruiting Engine</small>
                        </a>
                    </div>
                </div>
                <ul class="nav-menu">
                    <li><a href="<?= e($rootPath ?? '/index.php') ?>" class="nav-link">Accueil</a></li>
                    <li><a href="<?= e($rootPath ?? '/index.php') ?>#jobs" class="nav-link">Emplois</a></li>
                    <li><a href="<?= e($viewsPath ?? '/views') ?>/about.php" class="nav-link">À Propos</a></li>
                    <li><a href="<?= e($viewsPath ?? '/views') ?>/contact.php" class="nav-link">Contact</a></li>
                </ul>
                <div class="nav-buttons">
                    <?php if ($currentUser): ?>
                        <span class="user-chip"><?= e($currentUser['first_name']) ?> · <?= e($currentUser['role'] === 'admin' ? 'Admin' : $currentUser['role']) ?></span>
                        <?php if ($currentUser['role'] === 'admin'): ?>
                            <a href="<?= e($viewsPath ?? '/views') ?>/dashboard-admin.php" class="btn btn-outline">Admin</a>
                        <?php elseif ($currentUser['role'] === 'company'): ?>
                            <a href="<?= e($viewsPath ?? '/views') ?>/dashboard-entreprise.php" class="btn btn-outline">Dashboard</a>
                        <?php else: ?>
                            <a href="<?= e($viewsPath ?? '/views') ?>/dashboard-candidat.php" class="btn btn-outline">Dashboard</a>
                        <?php endif; ?>
                        <a href="<?= e($viewsPath ?? '/views') ?>/logout.php" class="btn btn-primary">Déconnexion</a>
                    <?php else: ?>
                        <a href="<?= e($viewsPath ?? '/views') ?>/login.php" class="btn btn-outline">Connexion</a>
                        <a href="<?= e($viewsPath ?? '/views') ?>/inscription.php" class="btn btn-primary">Inscription</a>
                    <?php endif; ?>
                </div>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <?php if ($flashMessages): ?>
        <section class="container flash-stack">
            <?php foreach ($flashMessages as $message): ?>
                <div class="flash flash-<?= e($message['type']) ?>"><?= e($message['message']) ?></div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
