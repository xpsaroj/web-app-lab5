<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$reflectedInput = $_GET['q'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = trim((string) ($_POST['author'] ?? 'Guest'));
    $content = trim((string) ($_POST['content'] ?? ''));

    if ($content !== '') {
        $db = getDb();
        $insert = $db->prepare('INSERT INTO comments (author, content, created_at) VALUES (:author, :content, :created_at)');
        $insert->execute([
            ':author' => $author !== '' ? $author : 'Guest',
            ':content' => $content,
            ':created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    header('Location: xss.php');
    exit;
}

$db = getDb();
$comments = $db->query('SELECT author, content, created_at FROM comments ORDER BY id DESC LIMIT 8')->fetchAll();

renderHeader('XSS Demonstration');
?>

<section class="card">
    <span class="tag">Reflected XSS</span>
    <h2>Reflected XSS Example</h2>
    <p>Try a payload like <strong>&lt;script&gt;alert("XSS")&lt;/script&gt;</strong> in the search box.</p>
    <form method="get" action="xss.php">
        <label for="q">Search input</label>
        <input id="q" name="q" value="<?php echo e((string) $reflectedInput); ?>" placeholder="Type any text or test payload">
        <button type="submit">Test Input</button>
    </form>

    <div class="subcontainer">
        <div class="grid">
            <div class="danger">
                <strong>Vulnerable output (unsafe):</strong><br>
                <?php echo (string) $reflectedInput; ?>
            </div>
            <div class="success">
                <strong>Secure output (encoded):</strong><br>
                <?php echo e((string) $reflectedInput); ?>
            </div>
        </div>
    </div>
</section>

<section class="card">
    <span class="tag">Stored XSS</span>
    <h2>Stored XSS Example</h2>
    <p>Post a comment and compare unsafe rendering with encoded rendering.</p>

    <form method="post" action="xss.php">
        <label for="author">Author</label>
        <input id="author" name="author" placeholder="Your name">

        <label for="content">Comment</label>
        <textarea id="content" name="content" placeholder="Try normal text or a script payload"></textarea>

        <button type="submit">Add Comment</button>
    </form>

    <h3>Unsafe Rendering (Vulnerable)</h3>
    <div class="danger">
        <?php if (count($comments) === 0): ?>
            <p>No comments yet.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <p>
                    <strong><?php echo (string) $comment['author']; ?>:</strong>
                    <?php echo (string) $comment['content']; ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <h3>Safe Rendering (Secure)</h3>
    <div class="success">
        <?php if (count($comments) === 0): ?>
            <p>No comments yet.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <p>
                    <strong><?php echo e((string) $comment['author']); ?>:</strong>
                    <?php echo e((string) $comment['content']); ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="card">
    <h3>XSS Best Practices</h3>
    <ul>
        <li>Apply output encoding based on context (HTML, attribute, JavaScript, URL).</li>
        <li>Validate and sanitize input, but do not rely on input filtering alone.</li>
        <li>Use Content Security Policy (CSP) to reduce exploitation impact.</li>
        <li>Prefer templating engines that auto-escape output by default.</li>
    </ul>
</section>

<?php
renderFooter();
