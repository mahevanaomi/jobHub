<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = compact('type', 'message');
}

function consume_flash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return $messages;
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function remember_old_input(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old_input(): void
{
    unset($_SESSION['_old']);
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function verify_csrf(?string $token): void
{
    if (!$token || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(419);
        exit('Session expirée. Veuillez réessayer.');
    }
}

function format_money(?float $amount, ?string $period = null): string
{
    if ($amount === null) {
        return 'Non communiqué';
    }

    $value = number_format($amount, 0, ',', ' ');
    $suffix = $period ? ' / ' . $period : '';

    return $value . ' FCFA' . $suffix;
}

function format_datetime(?string $value, string $format = 'd/m/Y'): string
{
    if (!$value) {
        return '-';
    }

    $date = new DateTimeImmutable($value);
    return $date->format($format);
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value);
    return trim((string) $value, '-');
}

function render_view(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/views/' . $view . '.php';
}

function upload_file(array $file, string $directory, array $allowedExtensions, int $maxBytes = 5242880): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Le fichier n\'a pas pu être téléversé.');
    }

    if (($file['size'] ?? 0) > $maxBytes) {
        throw new RuntimeException('Le fichier dépasse la taille autorisée.');
    }

    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        throw new RuntimeException('Format de fichier non autorisé.');
    }

    $basePath = dirname(__DIR__) . '/storage/uploads/' . trim($directory, '/');
    if (!is_dir($basePath) && !mkdir($basePath, 0775, true) && !is_dir($basePath)) {
        throw new RuntimeException('Impossible de créer le dossier de stockage.');
    }

    $filename = bin2hex(random_bytes(12)) . '.' . $extension;
    $targetPath = $basePath . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new RuntimeException('Impossible d\'enregistrer le fichier.');
    }

    return '/storage/uploads/' . trim($directory, '/') . '/' . $filename;
}
