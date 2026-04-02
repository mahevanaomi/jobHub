<?php

declare(strict_types=1);

require_once __DIR__ . '/repository.php';

function current_user(): ?array
{
    static $user = false;

    if ($user !== false) {
        return $user;
    }

    $userId = $_SESSION['user_id'] ?? null;
    $user = $userId ? find_user_by_id((int) $userId) : null;

    return $user;
}

function login_user(array $user): void
{
    $_SESSION['user_id'] = (int) $user['id'];
}

function logout_user(): void
{
    unset($_SESSION['user_id']);
}

function require_auth(?string $role = null): array
{
    $user = current_user();

    if (!$user) {
        flash('error', 'Vous devez vous connecter pour accéder à cette page.');
        redirect('/views/login.php');
    }

    if ($role && $user['role'] !== $role) {
        flash('error', 'Accès non autorisé.');
        redirect('/index.php');
    }

    return $user;
}

function guest_only(): void
{
    $user = current_user();

    if (!$user) {
        return;
    }

    if ($user['role'] === 'admin') {
        redirect('/views/dashboard-admin.php');
    }

    redirect($user['role'] === 'company' ? '/views/dashboard-entreprise.php' : '/views/dashboard-candidat.php');
}

function is_admin(): bool
{
    $user = current_user();
    return $user && $user['role'] === 'admin';
}
