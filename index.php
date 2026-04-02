<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

renderHeader('Security Vulnerabilities');
?>

<section class="card">
    <span class="tag">Overview</span>
    <h2>Common Web Vulnerabilities Demonstration</h2>
    <p>
        This mini project demonstrates vulnerable and secure implementations of Cross-Site Scripting (XSS),
        SQL Injection, and Cross-Site Request Forgery (CSRF). Each page includes practical examples and
        recommended defensive patterns.
    </p>
    <div class="warning">
        Each module presents a direct comparison between insecure and secure implementations,
        making defensive coding decisions easy to evaluate.
    </div>
</section>

<section class="grid" aria-label="Vulnerability modules">
    <article class="card">
        <span class="tag">Module 1</span>
        <h3>Cross-Site Scripting (XSS)</h3>
        <p>See how unescaped user input executes in the browser, and how output encoding stops it.</p>
        <a href="xss.php"><button type="button">Open XSS Lab</button></a>
    </article>

    <article class="card">
        <span class="tag">Module 2</span>
        <h3>SQL Injection</h3>
        <p>Compare raw query concatenation against prepared statements with bound parameters.</p>
        <a href="sqli.php"><button type="button">Open SQLi Lab</button></a>
    </article>

    <article class="card">
        <span class="tag">Module 3</span>
        <h3>Cross-Site Request Forgery (CSRF)</h3>
        <p>Observe unsafe state changes and then secure them with anti-CSRF tokens and validation.</p>
        <a href="csrf.php"><button type="button">Open CSRF Lab</button></a>
    </article>
</section>

<?php
renderFooter();
