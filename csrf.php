<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 1000;
}

$unsafeMessage = null;
$safeMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');
    $amount = max(0, (int) ($_POST['amount'] ?? 0));

    if ($action === 'unsafe-transfer' && $amount > 0) {
        // Vulnerable state-changing action without CSRF token verification.
        $_SESSION['balance'] -= $amount;
        $unsafeMessage = "Unsafe transfer completed: $$amount deducted.";
    }

    if ($action === 'safe-transfer' && $amount > 0) {
        $providedToken = $_POST['csrf_token'] ?? null;
        if (verifyCsrf(is_string($providedToken) ? $providedToken : null)) {
            $_SESSION['balance'] -= $amount;
            $safeMessage = "Safe transfer completed: $$amount deducted after CSRF validation.";
        } else {
            $safeMessage = 'Safe transfer blocked: invalid CSRF token.';
        }
    }
}

renderHeader('CSRF Demonstration');
?>

<section class="card">
    <span class="tag">CSRF</span>
    <h2>State-Changing Requests and Token Protection</h2>
    <p>Current demo balance: <strong>$<?php echo e((string) $_SESSION['balance']); ?></strong></p>

    <div class="grid">
        <article class="card">
            <h3>Vulnerable Form (No CSRF Defense)</h3>
            <p>This form accepts requests without proving user intent.</p>
            <form method="post" action="csrf.php">
                <input type="hidden" name="action" value="unsafe-transfer">
                <label for="amount-unsafe">Amount</label>
                <input id="amount-unsafe" name="amount" type="number" min="1" value="50">
                <button type="submit">Submit Unsafe Transfer</button>
            </form>
            <?php if ($unsafeMessage !== null): ?>
                <p class="danger"><?php echo e($unsafeMessage); ?></p>
            <?php endif; ?>
        </article>

        <article class="card">
            <h3>Secure Form (CSRF Token)</h3>
            <p>This form requires a valid, session-bound anti-CSRF token.</p>
            <form method="post" action="csrf.php">
                <input type="hidden" name="action" value="safe-transfer">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                <label for="amount-safe">Amount</label>
                <input id="amount-safe" name="amount" type="number" min="1" value="50">
                <button type="submit">Submit Safe Transfer</button>
            </form>
            <?php if ($safeMessage !== null): ?>
                <p class="<?php echo str_contains($safeMessage, 'blocked') ? 'danger' : 'success'; ?>"><?php echo e($safeMessage); ?></p>
            <?php endif; ?>
        </article>
    </div>

    <div class="warning">
        Example attack vector: a malicious site can auto-submit the unsafe form while the victim is logged in.
        The secure form resists this by requiring a valid token that attackers cannot read from another origin.
    </div>
</section>

<section class="card">
    <h3>CSRF Best Practices</h3>
    <ul>
        <li>Protect all state-changing operations with unpredictable CSRF tokens.</li>
        <li>Validate request origin using SameSite cookies and Origin/Referer checks when appropriate.</li>
        <li>Use POST (or stronger methods) for actions that mutate state.</li>
        <li>Require user re-authentication or step-up checks for high-risk operations.</li>
    </ul>
</section>

<?php
renderFooter();
