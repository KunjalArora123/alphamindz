<?php
/**
 * EMERGENCY FIX: Directly patches Auth.php and config on the server.
 * Bypasses git deployment issues completely.
 * DELETE THIS FILE AFTER USE.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================================
// STEP 0: Flush PHP OPcache so old compiled code is purged
// ============================================================
$opcache_status = 'OPcache not available';
if (function_exists('opcache_reset')) {
    opcache_reset();
    $opcache_status = '✅ OPcache RESET — all cached bytecode cleared';
}
// Also invalidate specific files
$files_to_invalidate = array(
    __DIR__ . '/application/controllers/Auth.php',
    __DIR__ . '/application/controllers/Student.php',
    __DIR__ . '/application/config/config.php',
);
foreach ($files_to_invalidate as $f) {
    if (function_exists('opcache_invalidate') && file_exists($f)) {
        opcache_invalidate($f, true);
    }
}

$results = array();
$results[] = $opcache_status;

// ============================================================
// STEP 1: Check current Auth.php version
// ============================================================
$auth_path = __DIR__ . '/application/controllers/Auth.php';
$auth_exists = file_exists($auth_path);
$auth_has_nuke = false;
$auth_old_bug = false;
if ($auth_exists) {
    $c = file_get_contents($auth_path);
    $auth_has_nuke = (strpos($c, '_nuke_session') !== false);
    // Check if session check comes before POST in login()
    $login_pos = strpos($c, 'public function login()');
    if ($login_pos !== false) {
        $login_block = substr($c, $login_pos, 800);
        $sess_pos = strpos($login_block, "userdata('user_logged_in')");
        $post_pos = strpos($login_block, "REQUEST_METHOD");
        if ($sess_pos !== false && $post_pos !== false && $sess_pos < $post_pos) {
            $auth_old_bug = true;
        }
    }
}

// ============================================================
// STEP 2: Write the CORRECT Auth.php directly
// ============================================================
$new_auth = <<<'AUTHPHP'
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');
    }

    /**
     * Completely destroy session: userdata, files, cookies.
     */
    private function _nuke_session() {
        $keys = array('user_id','user_name','first_name','last_name','username','user_email','user_role','user_logged_in');
        foreach ($keys as $k) { $this->session->unset_userdata($k); }
        $this->session->sess_destroy();
        $cn = $this->config->item('sess_cookie_name');
        if (empty($cn)) $cn = 'ci_session';
        foreach (array_unique(array($cn, 'ci_session', session_name())) as $name) {
            if (empty($name)) continue;
            setcookie($name, '', 1, '/');
            setcookie($name, '', 1);
            if (isset($_COOKIE[$name])) unset($_COOKIE[$name]);
        }
        $_SESSION = array();
        if (session_status() === PHP_SESSION_ACTIVE) { @session_destroy(); }
    }

    public function login() {
        // ── POST: Always process credentials FIRST, never skip ──
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $login_input = trim($this->input->post('email'));
            $password    = $this->input->post('password');

            // Nuke previous session before authenticating
            $this->_nuke_session();

            $user = $this->db->group_start()
                             ->where('email', $login_input)
                             ->or_where('username', $login_input)
                             ->group_end()
                             ->get('users')->row();

            if ($user && password_verify($password, $user->password)) {
                $this->session->sess_regenerate(TRUE);
                $this->session->set_userdata(array(
                    'user_id'        => (int) $user->id,
                    'user_name'      => trim($user->first_name . ' ' . $user->last_name),
                    'first_name'     => $user->first_name,
                    'last_name'      => $user->last_name,
                    'username'       => $user->username,
                    'user_email'     => $user->email,
                    'user_role'      => $user->role,
                    'user_logged_in' => TRUE
                ));
                $display = !empty($user->first_name) ? $user->first_name : $user->username;
                $this->session->set_flashdata('success', 'Welcome back, ' . $display . '!');
                redirect('student');
                return;
            }
            $this->session->set_flashdata('error', 'Invalid email/username or password.');
            redirect('auth/login');
            return;
        }

        // ── GET: show form or redirect ──
        if ($this->input->get('logged_out')) {
            $this->_nuke_session();
        } elseif ($this->session->userdata('user_logged_in') && $this->session->userdata('user_id')) {
            redirect('student');
            return;
        }

        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        $this->output->set_header('Pragma: no-cache');
        $this->load->view('student/includes/header', array('title' => 'Login | AlphaMindz'));
        $this->load->view('login');
        $this->load->view('student/includes/footer');
    }

    public function register() {
        if ($this->session->userdata('user_logged_in')) { redirect('student'); }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $first_name = trim($this->input->post('first_name'));
            $last_name  = trim($this->input->post('last_name'));
            $email      = trim($this->input->post('email'));
            $password   = $this->input->post('password');
            $confirm    = $this->input->post('confirm_password');
            if ($password !== $confirm) {
                $this->session->set_flashdata('error', 'Passwords do not match.');
                redirect('auth/register');
            }
            $existing = $this->db->get_where('users', array('email' => $email))->row();
            if ($existing) {
                $this->session->set_flashdata('error', 'Email address is already registered.');
                redirect('auth/register');
            }
            $parts = explode('@', $email);
            $base  = preg_replace('/[^a-zA-Z0-9_]/', '', $parts[0]);
            $uname = $base; $ctr = 1;
            while ($this->db->get_where('users', array('username' => $uname))->num_rows() > 0) { $uname = $base . $ctr++; }
            $data = array(
                'first_name' => $first_name, 'last_name' => $last_name, 'username' => $uname,
                'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'student', 'created_at' => date('Y-m-d H:i:s')
            );
            if ($this->db->insert('users', $data)) {
                $this->session->set_flashdata('success', 'Registration successful! Please login.');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'An error occurred. Please try again.');
                redirect('auth/register');
            }
        }
        $this->load->view('student/includes/header', array('title' => 'Register | AlphaMindz'));
        $this->load->view('register');
        $this->load->view('student/includes/footer');
    }

    public function forgot_password() {
        if ($this->session->userdata('user_logged_in')) { redirect('student'); }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $email = trim($this->input->post('email'));
            $user  = $this->db->get_where('users', array('email' => $email))->row();
            if ($user) {
                $token   = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $this->db->where('id', $user->id);
                $this->db->update('users', array('reset_token' => $token, 'reset_token_expires' => $expires));
                $reset_link = site_url('auth/reset_password/' . $token);
                $this->load->config('email');
                $this->load->library('email');
                $from_email = isset($_SERVER['FROM_EMAIL']) ? $_SERVER['FROM_EMAIL'] : (getenv('FROM_EMAIL') ?: 'prismlogic510@gmail.com');
                $from_name  = isset($_SERVER['FROM_NAME'])  ? $_SERVER['FROM_NAME']  : (getenv('FROM_NAME')  ?: 'AlphaMindz');
                $this->email->from($from_email, $from_name);
                $this->email->to($email);
                $this->email->subject('Password Reset Request - AlphaMindz');
                $message = '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body style="font-family:Arial,sans-serif;background:#f4f6f9;margin:0;padding:20px;"><div style="max-width:550px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,.05);border:1px solid #e9ecef;"><div style="background:#2c3e50;padding:25px;text-align:center;"><h2 style="color:#fff;margin:0;font-size:22px;">AlphaMindz</h2></div><div style="padding:30px;color:#333;line-height:1.6;"><p>Hello <strong>'.htmlspecialchars($user->first_name).'</strong>,</p><p>Click below to reset your password:</p><div style="text-align:center;margin:30px 0;"><a href="'.$reset_link.'" style="background:#007bff;color:#fff;padding:14px 28px;text-decoration:none;border-radius:4px;font-weight:bold;display:inline-block;">Reset Password</a></div><p style="font-size:14px;color:#6c757d;">Valid for 1 hour.</p></div><div style="background:#f8f9fa;padding:15px;text-align:center;font-size:12px;color:#6c757d;border-top:1px solid #e9ecef;">&copy; '.date('Y').' AlphaMindz</div></div></body></html>';
                $this->email->message($message);
                if (!$this->email->send()) { log_message('error', 'Email error: '.$this->email->print_debugger(array('headers'))); }
            }
            $this->session->set_flashdata('success', 'If an account exists, a reset link has been sent.');
            redirect('auth/forgot_password');
        }
        $this->load->view('student/includes/header', array('title' => 'Forgot Password | AlphaMindz'));
        $this->load->view('forgot_password');
        $this->load->view('student/includes/footer');
    }

    public function reset_password($token = NULL) {
        if ($this->session->userdata('user_logged_in')) { redirect('student'); }
        if (empty($token)) { $this->session->set_flashdata('error', 'Invalid request.'); redirect('auth/forgot_password'); }
        $this->db->where('reset_token', $token);
        $this->db->where('reset_token_expires >=', date('Y-m-d H:i:s'));
        $user = $this->db->get('users')->row();
        if (!$user) { $this->session->set_flashdata('error', 'Link invalid or expired.'); redirect('auth/forgot_password'); }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $pw = $this->input->post('password');
            $cp = $this->input->post('confirm_password');
            if ($pw !== $cp) { $this->session->set_flashdata('error', 'Passwords do not match.'); redirect('auth/reset_password/'.$token); }
            if (strlen($pw) < 6) { $this->session->set_flashdata('error', 'Min 6 characters.'); redirect('auth/reset_password/'.$token); }
            $this->db->where('id', $user->id);
            $this->db->update('users', array('password' => password_hash($pw, PASSWORD_DEFAULT), 'reset_token' => NULL, 'reset_token_expires' => NULL));
            $this->session->set_flashdata('success', 'Password reset! Please login.');
            redirect('auth/login');
        }
        $data = array('title' => 'Reset Password | AlphaMindz', 'token' => $token);
        $this->load->view('student/includes/header', $data);
        $this->load->view('reset_password', $data);
        $this->load->view('student/includes/footer');
    }

    public function dashboard() { redirect('student'); }

    public function logout() {
        $this->_nuke_session();
        redirect('auth/login?logged_out=1');
    }
}
AUTHPHP;

// Write Auth.php
$auth_written = @file_put_contents($auth_path, $new_auth);
$results[] = $auth_written ? "✅ Auth.php PATCHED successfully (".strlen($new_auth)." bytes)" : "❌ FAILED to write Auth.php — check file permissions";

// ============================================================
// STEP 3: Fix config - sess_regenerate_destroy
// ============================================================
$config_path = __DIR__ . '/application/config/config.php';
if (file_exists($config_path)) {
    $cfg = file_get_contents($config_path);
    if (strpos($cfg, "sess_regenerate_destroy'] = FALSE") !== false) {
        $cfg = str_replace("sess_regenerate_destroy'] = FALSE", "sess_regenerate_destroy'] = TRUE", $cfg);
        $cw = @file_put_contents($config_path, $cfg);
        $results[] = $cw ? "✅ config.php PATCHED: sess_regenerate_destroy → TRUE" : "❌ FAILED to write config.php";
    } else {
        $results[] = "✅ config.php already has correct sess_regenerate_destroy setting";
    }
}

// ============================================================
// STEP 4: Clear ALL session files
// ============================================================
if (session_status() === PHP_SESSION_NONE) { @session_start(); }
$_SESSION = array();
@session_destroy();
foreach (array('ci_session', session_name(), 'PHPSESSID') as $cn) {
    if (!empty($cn)) { setcookie($cn, '', 1, '/'); setcookie($cn, '', 1); }
}

$deleted = 0;
$dirs = array_unique(array_filter(array(sys_get_temp_dir(), '/tmp', '/home/befms79lg357/tmp', ini_get('session.save_path'))));
foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    foreach (array('sess_*', 'ci_session*') as $pat) {
        $files = glob($dir . '/' . $pat);
        if ($files) { foreach ($files as $f) { if (is_file($f) && @unlink($f)) $deleted++; } }
    }
}
$results[] = "✅ Sessions cleared: $deleted file(s) deleted, cookies expired";

// ============================================================
// STEP 5: Verify the patched Auth.php
// ============================================================
$verify = '';
if (file_exists($auth_path)) {
    $v = file_get_contents($auth_path);
    $verify = (strpos($v, '_nuke_session') !== false) ? '✅ Auth.php verified: contains _nuke_session()' : '⚠️ Auth.php does NOT contain fix';
}

// ============================================================
// STEP 6: Quick DB check — what users exist for example@gmail.com?
// ============================================================
$db_info = '';
try {
    // Read CI database config
    $db_config_path = __DIR__ . '/application/config/database.php';
    if (file_exists($db_config_path)) {
        include($db_config_path);
        if (isset($db['default'])) {
            $dsn = 'mysql:host='.$db['default']['hostname'].';dbname='.$db['default']['database'];
            $pdo = new PDO($dsn, $db['default']['username'], $db['default']['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT id, first_name, last_name, username, email, role FROM users WHERE email = ? OR username = ? LIMIT 5");
            $stmt->execute(array('example@gmail.com', 'example@gmail.com'));
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($rows)) {
                $db_info = "⚠️ No user found with email 'example@gmail.com'";
            } else {
                $db_info = "Users matching 'example@gmail.com':\n";
                foreach ($rows as $r) {
                    $db_info .= "  ID={$r['id']} name={$r['first_name']} {$r['last_name']} username={$r['username']} email={$r['email']}\n";
                }
            }

            // Also check sahil
            $stmt2 = $pdo->prepare("SELECT id, first_name, last_name, username, email FROM users WHERE first_name = 'sahil' OR username = 'sahil' LIMIT 5");
            $stmt2->execute();
            $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows2)) {
                $db_info .= "\nUsers matching 'sahil':\n";
                foreach ($rows2 as $r) {
                    $db_info .= "  ID={$r['id']} name={$r['first_name']} {$r['last_name']} username={$r['username']} email={$r['email']}\n";
                }
            }
        }
    }
} catch (Exception $e) {
    $db_info = "DB check error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head><title>AlphaMindz Emergency Fix</title>
<style>
body{font-family:Arial,sans-serif;max-width:750px;margin:40px auto;padding:20px;background:#f8f9fa;}
h1{color:#2c3e50;} .card{border:1px solid #ddd;border-radius:8px;padding:20px;margin:15px 0;background:#fff;}
pre{background:#f0f0f0;padding:15px;border-radius:6px;overflow-x:auto;font-size:13px;white-space:pre-wrap;}
.ok{color:green;font-weight:bold;} .err{color:red;font-weight:bold;}
</style>
</head>
<body>
<h1>🚨 AlphaMindz Emergency Fix</h1>

<div class="card">
<h3>Before Fix</h3>
<p>Auth.php existed: <?php echo $auth_exists ? 'Yes' : 'No'; ?></p>
<p>Had _nuke_session: <?php echo $auth_has_nuke ? '✅ Yes' : '❌ No'; ?></p>
<p>Had old bug (session check before POST): <?php echo $auth_old_bug ? '⚠️ YES — THIS WAS THE PROBLEM' : 'No'; ?></p>
</div>

<div class="card">
<h3>Fix Results</h3>
<?php foreach ($results as $r): ?>
<p><?php echo $r; ?></p>
<?php endforeach; ?>
<p><?php echo $verify; ?></p>
</div>

<div class="card">
<h3>Database Check</h3>
<pre><?php echo htmlspecialchars($db_info); ?></pre>
</div>

<div class="card" style="background:#d4edda;border-color:#28a745;">
<h3>✅ Now test the login</h3>
<ol>
<li>Click the button below to go to the login page</li>
<li>Login with <strong>example@gmail.com</strong></li>
<li>You should see <strong>"te"</strong> — NOT "sahil"</li>
<li>After confirming it works, <strong>delete fix_deploy.php and session_reset.php</strong> from cPanel File Manager</li>
</ol>
<br>
<a href="auth/login?logged_out=1" style="display:inline-block;padding:14px 30px;background:#007bff;color:#fff;text-decoration:none;border-radius:6px;font-weight:bold;font-size:16px;">→ Go to Login Page (clean session)</a>
</div>

</body>
</html>
