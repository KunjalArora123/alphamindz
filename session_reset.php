<?php
/**
 * Session Reset & Deployment Diagnostic Tool
 * Visit this URL once, then delete this file from cPanel File Manager.
 */

// 1. Destroy current session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = array();
session_destroy();

// 2. Kill session cookies
$cookie_names = array('ci_session', session_name(), 'PHPSESSID');
foreach ($cookie_names as $cn) {
    if (!empty($cn)) {
        setcookie($cn, '', 1, '/');
        setcookie($cn, '', 1);
    }
}

// 3. Delete ALL session files from temp directories
$dirs_to_clean = array(
    sys_get_temp_dir(),
    '/tmp',
    '/home/befms79lg357/tmp',
    ini_get('session.save_path')
);
$deleted = 0;
foreach (array_unique(array_filter($dirs_to_clean)) as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/sess_*');
        if ($files) {
            foreach ($files as $f) {
                if (is_file($f) && @unlink($f)) {
                    $deleted++;
                }
            }
        }
        // Also try ci_session files
        $ci_files = glob($dir . '/ci_session*');
        if ($ci_files) {
            foreach ($ci_files as $f) {
                if (is_file($f) && @unlink($f)) {
                    $deleted++;
                }
            }
        }
    }
}

// 4. Try to reset any local git changes and pull latest
$git_output = '';
$git_cmds = array(
    'cd /home/befms79lg357/public_html && git stash 2>&1',
    'cd /home/befms79lg357/public_html && git checkout -- . 2>&1',
    'cd /home/befms79lg357/public_html && git pull origin main 2>&1'
);
foreach ($git_cmds as $cmd) {
    $git_output .= "$ " . $cmd . "\n";
    $result = @shell_exec($cmd);
    $git_output .= ($result ? $result : "(no output or command blocked)") . "\n\n";
}

// 5. Check which version of Auth.php is on server
$auth_file = __DIR__ . '/application/controllers/Auth.php';
$auth_check = 'FILE NOT FOUND';
$has_nuke = false;
$has_old_bug = false;
if (file_exists($auth_file)) {
    $contents = file_get_contents($auth_file);
    $has_nuke = (strpos($contents, '_nuke_session') !== false);
    $has_old_bug = (strpos($contents, "if (\$this->session->userdata('user_logged_in'))") !== false
                    && strpos($contents, "REQUEST_METHOD") !== false
                    && strpos($contents, '_nuke_session') === false);

    // Check line ordering
    $pos_logged_in = strpos($contents, "if (\$this->session->userdata('user_logged_in'))");
    $pos_post = strpos($contents, "REQUEST_METHOD");
    if ($pos_logged_in !== false && $pos_post !== false) {
        if ($pos_logged_in < $pos_post) {
            $auth_check = "⚠️ OLD VERSION - session check comes BEFORE POST handler (this is the bug!)";
        } else {
            $auth_check = "✅ FIXED - POST handler comes first";
        }
    }
    if ($has_nuke) {
        $auth_check = "✅ LATEST VERSION with _nuke_session() helper";
    }
} else {
    $auth_check = "❌ Auth.php NOT FOUND at expected path";
}

// 6. Check config
$config_file = __DIR__ . '/application/config/config.php';
$config_check = '';
if (file_exists($config_file)) {
    $cfg = file_get_contents($config_file);
    if (strpos($cfg, "sess_regenerate_destroy'] = TRUE") !== false) {
        $config_check = "✅ sess_regenerate_destroy = TRUE";
    } else {
        $config_check = "⚠️ sess_regenerate_destroy is NOT TRUE (old sessions persist!)";
    }
}

?>
<!DOCTYPE html>
<html>
<head><title>AlphaMindz Session Reset</title>
<style>
body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; }
.ok { color: green; font-weight: bold; }
.warn { color: orange; font-weight: bold; }
.err { color: red; font-weight: bold; }
pre { background: #f4f4f4; padding: 15px; border-radius: 6px; overflow-x: auto; font-size: 13px; }
h1 { color: #2c3e50; }
.card { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin: 15px 0; }
</style>
</head>
<body>
<h1>🔧 AlphaMindz Session Reset</h1>

<div class="card">
<h3>Session Cleanup</h3>
<p class="ok">✅ Current session destroyed</p>
<p class="ok">✅ Session cookies expired</p>
<p><?php echo $deleted; ?> old session file(s) deleted from server</p>
</div>

<div class="card">
<h3>Auth.php Version Check</h3>
<p><?php echo $auth_check; ?></p>
</div>

<div class="card">
<h3>Config Check</h3>
<p><?php echo $config_check; ?></p>
</div>

<div class="card">
<h3>Git Sync Attempt</h3>
<pre><?php echo htmlspecialchars($git_output); ?></pre>
</div>

<div class="card" style="background: #d4edda;">
<h3>✅ What to do now</h3>
<ol>
<li><strong>Close ALL browser tabs</strong> for this site</li>
<li>Open a fresh <strong>Incognito/Private window</strong> (Ctrl+Shift+N)</li>
<li>Go to your login page and login with <code>example@gmail.com</code></li>
<li>You should see <strong>"te"</strong> — not "sahil"</li>
<li><strong>DELETE this file</strong> from cPanel File Manager (session_reset.php)</li>
</ol>
</div>

<p><a href="auth/login?logged_out=1" style="display:inline-block; padding:12px 24px; background:#007bff; color:white; text-decoration:none; border-radius:4px; font-weight:bold;">→ Go to Login Page</a></p>
</body>
</html>
