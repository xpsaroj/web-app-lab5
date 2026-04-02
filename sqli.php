<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$db = getDb();
$usernameInput = trim((string) ($_GET['username'] ?? ''));
$unsafeResults = [];
$safeResults = [];
$unsafeQueryText = null;

if ($usernameInput !== '') {
    $unsafeQueryText = "SELECT id, username, role FROM users WHERE username = '$usernameInput'";
    try {
        // Intentionally vulnerable example.
        $unsafeResults = $db->query($unsafeQueryText)->fetchAll();
    } catch (Throwable $exception) {
        $unsafeResults = [['id' => '-', 'username' => 'Query error', 'role' => $exception->getMessage()]];
    }

    $safeStmt = $db->prepare('SELECT id, username, role FROM users WHERE username = :username');
    $safeStmt->execute([':username' => $usernameInput]);
    $safeResults = $safeStmt->fetchAll();
}

renderHeader('SQL Injection Demonstration');
?>

<section class="card">
    <span class="tag">SQL Injection</span>
    <h2>Unsafe vs Prepared Statement</h2>
    <p>Try input like <strong>' OR '1'='1</strong> to observe how concatenated SQL is abused.</p>

    <form method="get" action="sqli.php" class="form">
        <label for="username">Username filter</label>
        <input id="username" name="username" value="<?php echo e($usernameInput); ?>" placeholder="alice">
        <button type="submit">Run Query</button>
    </form>

    <?php if ($usernameInput !== ''): ?>
        <div class="subcontainer">
            <div class="danger">
                <strong>Vulnerable SQL:</strong>
                <p><?php echo e((string) $unsafeQueryText); ?></p>
            </div>
            
            <div class="grid">
                <div class="card">
                    <h3>Unsafe Results</h3>
                    <?php if (count($unsafeResults) === 0): ?>
                        <p>No rows returned.</p>
                        <?php else: ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($unsafeResults as $row): ?>
                                        <tr>
                                            <td><?php echo e((string) $row['id']); ?></td>
                                            <td><?php echo e((string) $row['username']); ?></td>
                                            <td><?php echo e((string) $row['role']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card">
                                <h3>Safe Results (Prepared Statement)</h3>
                                <?php if (count($safeResults) === 0): ?>
                                    <p>No rows returned.</p>
                                    <?php else: ?>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Username</th>
                                                    <th>Role</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($safeResults as $row): ?>
                                                    <tr>
                                                        <td><?php echo e((string) $row['id']); ?></td>
                                                        <td><?php echo e((string) $row['username']); ?></td>
                                                        <td><?php echo e((string) $row['role']); ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
    <?php endif; ?>
</section>

<section class="card">
    <h3>SQL Injection Best Practices</h3>
    <ul>
        <li>Use parameterized queries or prepared statements for all database access.</li>
        <li>Apply least-privilege database accounts to limit impact of compromise.</li>
        <li>Validate input formats and enforce expected length and character constraints.</li>
        <li>Use centralized data-access layers and avoid building raw SQL from user input.</li>
    </ul>
</section>

<?php
renderFooter();
