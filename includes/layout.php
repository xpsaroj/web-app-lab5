<?php

declare(strict_types=1);

function renderHeader(string $title): void
{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo e($title); ?> | <?php echo e(APP_NAME); ?></title>
        <link rel="stylesheet" href="assets/styles.css">
    </head>
    <body>
    <header class="site-header">
        <div class="container">
            <p class="brand"><?php echo e(APP_NAME); ?></p>
            <nav class="nav" aria-label="Main navigation">
                <a href="index.php">Home</a>
                <a href="xss.php">XSS</a>
                <a href="sqli.php">SQL Injection</a>
                <a href="csrf.php">CSRF</a>
            </nav>
        </div>
    </header>
    <main class="container">
    <?php
}

function renderFooter(): void
{
    ?>
        <p class="footer">A focused reference implementation of common web vulnerability patterns and mitigations in PHP.</p>
    </main>
    </body>
    </html>
    <?php
}
