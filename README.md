# SecureLens

This project demonstrates three common web vulnerabilities and their secure alternatives in a focused PHP application:

- Cross-Site Scripting (XSS)
- SQL Injection
- Cross-Site Request Forgery (CSRF)

The app includes intentionally vulnerable examples side-by-side with protected implementations so the behavior is easy to compare.

## Project Structure

```text
web-app-lab5/
├── README.md
├── index.php
├── xss.php
├── sqli.php
├── csrf.php
├── config.php
├── assets/
│   └── styles.css
├── includes/
│   ├── bootstrap.php
│   ├── database.php
│   ├── layout.php
│   └── security.php
└── storage/
    └── security_lab.sqlite (auto-created at runtime)
```

## Requirements

- PHP 8.0+
- SQLite extension enabled in PHP (PDO SQLite)

## Run Locally

From the project root:

```bash
php -S localhost:8000
```

Then open:

`http://localhost:8000/index.php`

If your system does not have PHP installed, install it first and retry.

## What Each Module Demonstrates

### 1) XSS ([xss.php](xss.php))

- Reflected XSS:
  - Vulnerable output renders raw user input directly.
  - Secure output uses `htmlspecialchars` to encode user content.
- Stored XSS:
  - Comments are saved and then rendered in both unsafe and safe blocks.

### 2) SQL Injection ([sqli.php](sqli.php))

- Vulnerable query builds SQL using direct string concatenation.
- Secure query uses prepared statements with bound parameters.
- Useful payload to observe behavior:
  - `' OR '1'='1`

### 3) CSRF ([csrf.php](csrf.php))

- Vulnerable transfer form processes state-changing requests without token checks.
- Secure transfer form includes and validates a session-based CSRF token.

## Security Best Practices Summary

### XSS Prevention

- Encode output in the correct context (HTML, attribute, JavaScript, URL).
- Treat all user input as untrusted.
- Use framework/template auto-escaping where possible.
- Add Content Security Policy (CSP) to reduce exploit impact.

### SQL Injection Prevention

- Use prepared statements for every query that includes user input.
- Avoid dynamic SQL string building with untrusted data.
- Validate input shape (length, format, data type).
- Use least-privilege database accounts.

### CSRF Prevention

- Require anti-CSRF tokens for all state-changing actions.
- Use `SameSite` cookies and verify request origin where appropriate.
- Restrict sensitive actions to POST/PUT/PATCH/DELETE instead of GET.
- Add extra verification for high-risk transactions.