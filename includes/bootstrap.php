<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/security.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

initializeDatabase();
