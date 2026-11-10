<?php
/**
 * Git Clean & Sync Tool for cPanel
 * Resolves the "Your local changes would be overwritten by merge" error.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dir = __DIR__;
chdir($dir);

$output = array();

// 1. Git status
$status = shell_exec('git status 2>&1');
$output[] = "=== Git Status Before ===";
$output[] = $status ? $status : "(shell_exec disabled or no output)";

// 2. Discard local modifications on server
$reset = shell_exec('git reset --hard 2>&1');
$output[] = "=== Git Reset Result ===";
$output[] = $reset;

// 3. Clean untracked if needed
$clean = shell_exec('git clean -fd 2>&1');
$output[] = "=== Git Clean Result ===";
$output[] = $clean;

// 4. Pull latest from origin main
$pull = shell_exec('git pull origin main 2>&1');
$output[] = "=== Git Pull Result ===";
$output[] = $pull;

// 5. Final status
$status_after = shell_exec('git status 2>&1');
$output[] = "=== Git Status After ===";
$output[] = $status_after;

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Git Clean & Sync</title>
<style>
body { font-family: monospace; background: #0f172a; color: #f8fafc; padding: 25px; }
pre { background: #1e293b; padding: 20px; border-radius: 8px; border: 1px solid #334155; line-height: 1.5; font-size: 14px; white-space: pre-wrap; }
h1 { color: #38bdf8; }
.btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 15px; }
</style>
</head>
<body>
<h1>🚀 cPanel Git Auto-Cleaner & Syncer</h1>
<p>This script discards any untracked local edits on the server so <code>git pull</code> can complete without conflicts.</p>

<pre><?php echo htmlspecialchars(implode("\n\n", $output)); ?></pre>

<p><a href="/auth/login?logged_out=1" class="btn">→ Go to Login Page</a></p>
</body>
</html>
