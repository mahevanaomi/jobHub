<?php

declare(strict_types=1);

function app_config(): array
{
    return [
        'db' => [
            'host' => getenv('JOBHUB_DB_HOST') ?: 'localhost',
            'port' => getenv('JOBHUB_DB_PORT') ?: '3306',
            'name' => getenv('JOBHUB_DB_NAME') ?: 'recrutement',
            'user' => getenv('JOBHUB_DB_USER') ?: 'jobhub',
            'pass' => getenv('JOBHUB_DB_PASS') ?: 'jobhub123',
        ],
    ];
}

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = app_config()['db'];
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['port'],
        $config['name']
    );

    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
