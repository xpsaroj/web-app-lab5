<?php

declare(strict_types=1);

function getDb(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $storagePath = dirname(DB_PATH);
    if (!is_dir($storagePath)) {
        mkdir($storagePath, 0777, true);
    }

    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
}

function initializeDatabase(): void
{
    $db = getDb();

    $db->exec(
        'CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            role TEXT NOT NULL,
            password TEXT NOT NULL
        )'
    );

    $db->exec(
        'CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            author TEXT NOT NULL,
            content TEXT NOT NULL,
            created_at TEXT NOT NULL
        )'
    );

    $existingUsers = (int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($existingUsers === 0) {
        $seedUsers = [
            ['alice', 'student', 'alice123'],
            ['bob', 'admin', 'bob123'],
            ['charlie', 'instructor', 'charlie123'],
        ];

        $insert = $db->prepare('INSERT INTO users (username, role, password) VALUES (:username, :role, :password)');
        foreach ($seedUsers as [$username, $role, $password]) {
            $insert->execute([
                ':username' => $username,
                ':role' => $role,
                ':password' => $password,
            ]);
        }
    }
}
