<?php
/**
 * EMERGENCY FIX & DB INSPECTOR
 * Shows exactly who is in the database and fixes user names/sessions.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to DB
$db_info = '';
$all_users = array();
$pdo = null;

try {
    include(__DIR__ . '/application/config/database.php');
    $h = isset($_SERVER['DB_HOST']) ? $_SERVER['DB_HOST'] : (isset($db['default']['hostname']) ? $db['default']['hostname'] : 'localhost');
    $u = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : (isset($db['default']['username']) ? $db['default']['username'] : 'u782754055_kunjal_arora');
    $p = isset($_SERVER['DB_PASS']) ? $_SERVER['DB_PASS'] : (isset($db['default']['password']) ? $db['default']['password'] : 'Kunjal@7590012004');
    $d = isset($_SERVER['DB_NAME']) ? $_SERVER['DB_NAME'] : (isset($db['default']['database']) ? $db['default']['database'] : 'u782754055_alphamindz');

    $pdo = new PDO("mysql:host=$h;dbname=$d;charset=utf8mb4", $u, $p, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // Handle Rename Action if requested
    if (isset($_POST['rename_user_id']) && isset($_POST['new_first_name'])) {
        $stmt_up = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
        $stmt_up->execute([trim($_POST['new_first_name']), trim($_POST['new_last_name'] ?? ''), (int)$_POST['rename_user_id']]);
        $msg = "✅ Updated User #" . (int)$_POST['rename_user_id'] . " first_name to: " . htmlspecialchars($_POST['new_first_name']);
    }

    // Handle Delete Sahil if requested
    if (isset($_POST['delete_user_id'])) {
        $stmt_del = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt_del->execute([(int)$_POST['delete_user_id']]);
        $msg = "✅ Deleted User #" . (int)$_POST['delete_user_id'];
    }

    $stmt = $pdo->query("SELECT id, first_name, last_name, username, email, role, created_at FROM users ORDER BY id DESC LIMIT 50");
    $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $db_info = "DB Connection Error: " . $e->getMessage();
}

// Clear sessions
if (session_status() === PHP_SESSION_NONE) { @session_start(); }
$_SESSION = array();
@session_destroy();
foreach (array('ci_session', session_name(), 'PHPSESSID') as $cn) {
    if (!empty($cn)) {
        setcookie($cn, '', 1, '/');
        setcookie($cn, '', 1);
        if (isset($_COOKIE[$cn])) unset($_COOKIE[$cn]);
    }
}

// Delete session files
$deleted_sess = 0;
$dirs = array_unique(array_filter(array(sys_get_temp_dir(), '/tmp', '/home/befms79lg357/tmp', ini_get('session.save_path'))));
foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    foreach (array('sess_*', 'ci_session*') as $pat) {
        $files = glob($dir . '/' . $pat);
        if ($files) { foreach ($files as $f) { if (is_file($f) && @unlink($f)) $deleted_sess++; } }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>AlphaMindz Database Users Inspector</title>
<style>
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; max-width: 950px; margin: 30px auto; padding: 20px; background: #f1f5f9; color: #1e293b; }
.card { background: #fff; border-radius: 8px; padding: 24px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
h1, h2, h3 { margin-top: 0; color: #0f172a; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
th { background: #f8fafc; font-weight: 600; color: #475569; }
tr:hover { background: #f8fafc; }
.badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
.badge-admin { background: #fee2e2; color: #991b1b; }
.badge-student { background: #dcfce7; color: #166534; }
.highlight { background: #fef08a !important; font-weight: bold; }
.btn { display: inline-block; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; }
.btn-primary { background: #2563eb; color: #fff; }
.btn-danger { background: #dc2626; color: #fff; }
.btn-lg { padding: 12px 24px; font-size: 16px; }
.alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 15px; font-weight: 500; }
.alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
.alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
</style>
</head>
<body>

<h1>🔍 AlphaMindz Database & Session Inspector</h1>

<?php if (!empty($msg)): ?>
    <div class="alert alert-success"><?php echo $msg; ?></div>
<?php endif; ?>

<?php if (!empty($db_info)): ?>
    <div class="alert alert-error"><?php echo $db_info; ?></div>
<?php endif; ?>

<div class="card">
    <h3>Session Status</h3>
    <p>✅ All session cookies expired</p>
    <p>✅ Deleted <?php echo $deleted_sess; ?> session file(s) from server temp storage</p>
</div>

<div class="card">
    <h3>Users in Database (Total: <?php echo count($all_users); ?>)</h3>
    <p class="text-muted">Look at the rows below to see what name and email are currently saved in your database:</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_users as $u): ?>
                <?php 
                    $is_sahil = (stripos($u['first_name'], 'sahil') !== false || stripos($u['username'], 'sahil') !== false);
                    $is_example = (stripos($u['email'], 'example@gmail.com') !== false);
                    $row_class = ($is_sahil || $is_example) ? 'highlight' : '';
                ?>
                <tr class="<?php echo $row_class; ?>">
                    <td>#<?php echo $u['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($u['first_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($u['last_name']); ?></td>
                    <td><code><?php echo htmlspecialchars($u['username']); ?></code></td>
                    <td><strong><?php echo htmlspecialchars($u['email']); ?></strong></td>
                    <td><span class="badge badge-<?php echo $u['role']; ?>"><?php echo htmlspecialchars($u['role']); ?></span></td>
                    <td>
                        <form method="POST" style="display:inline-block; margin-right:5px;">
                            <input type="hidden" name="rename_user_id" value="<?php echo $u['id']; ?>">
                            <input type="hidden" name="new_first_name" value="te">
                            <input type="hidden" name="new_last_name" value="">
                            <button type="submit" class="btn btn-primary" title="Rename this user to 'te'">Rename to 'te'</button>
                        </form>
                        <form method="POST" style="display:inline-block;" onsubmit="return confirm('Really delete user #<?php echo $u['id']; ?>?');">
                            <input type="hidden" name="delete_user_id" value="<?php echo $u['id']; ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card" style="background: #eff6ff; border-color: #93c5fd; text-align: center;">
    <h3>Ready to test?</h3>
    <p>After checking the table above, open an Incognito window or click below:</p>
    <a href="<?php echo '/auth/login?logged_out=1'; ?>" class="btn btn-primary btn-lg">→ Go to Student Login</a>
</div>

</body>
</html>
