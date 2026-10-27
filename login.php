<?php
session_start();
if (!isset($_SESSION['users'])) $_SESSION['users'] = [];
if (!empty($_SESSION['student'])) { header('Location: form.php'); exit; }
$error = $_SESSION['signup_message'] ?? '';
unset($_SESSION['signup_message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $user = $_SESSION['users'][$email] ?? null;
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['student'] = ['name' => $user['name'], 'email' => $email];
        header('Location: form.php'); exit;
    }
    $error = 'Invalid email or password. Please sign up first if you do not have an account.';
}
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Login</title><style>
body{font-family:Arial,sans-serif;background:#f1f4f8;margin:0;padding:25px;color:#222}.card{max-width:480px;margin:30px auto;background:white;padding:25px;border:1px solid #ddd;border-radius:8px}h1{color:#174a7e;font-size:25px}label{display:block;margin:13px 0 6px;font-weight:bold}input{width:100%;padding:10px;border:1px solid #bbb;border-radius:4px;box-sizing:border-box}button{margin-top:17px;padding:11px 15px;background:#1d5c99;color:white;border:0;border-radius:4px;cursor:pointer}.error{background:#fff0f0;color:#8b2020;padding:10px;border-radius:4px}.small{font-size:12px;color:#666}
</style></head><body><main class="card">
<h1>Student Registration Portal</h1><h2>Login</h2>
<?php if ($error): ?><p class="error"><?=e($error)?></p><?php endif; ?>
<form method="post" action="login.php">
<label for="email">Email</label><input id="email" type="email" name="email" required autocomplete="username">
<label for="password">Password</label><input id="password" type="password" name="password" required autocomplete="current-password">
<button type="submit">Login</button></form>
<p>New student? <a href="signup.php">Create an account</a></p>
<p class="small">Accounts are stored temporarily in the current PHP session.</p>
</main></body></html>