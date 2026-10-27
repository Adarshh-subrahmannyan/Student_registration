<?php
session_start();
require_once __DIR__ . '/db.php';
$error = '';
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
        $error = 'Name must be between 2 and 100 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $pdo = database();
            $statement = $pdo->prepare(
                'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)'
            );
            $statement->execute([
                'name' => $name,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            $_SESSION['signup_message'] = 'Account created. Please log in.';
            header('Location: login.php');
            exit;
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $error = 'An account with this email already exists.';
            } else {
                error_log($exception->getMessage());
                $error = 'Could not save your account. Check the database configuration and try again.';
            }
        }
    }
}
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Signup</title>
<style>
body{font-family:Arial,sans-serif;background:#f1f4f8;margin:0;padding:25px;color:#222}.card{max-width:480px;margin:30px auto;background:white;padding:25px;border:1px solid #ddd;border-radius:8px}h1{color:#174a7e;font-size:25px}label{display:block;margin:13px 0 6px;font-weight:bold}input{width:100%;padding:10px;border:1px solid #bbb;border-radius:4px;box-sizing:border-box}button,.btn{display:inline-block;margin-top:17px;padding:11px 15px;background:#1d5c99;color:white;border:0;border-radius:4px;text-decoration:none;cursor:pointer}.error{background:#fff0f0;color:#8b2020;padding:10px;border-radius:4px}.small{font-size:12px;color:#666}
</style>
</head>
<body><main class="card">
<h1>Student Registration Portal</h1><h2>Create Account</h2>
<?php if ($error): ?><p class="error"><?=e($error)?></p><?php endif; ?>
<form method="post" action="signup.php" id="signupForm">
<label for="name">Full name</label><input id="name" name="name" required minlength="2" maxlength="100" value="<?=e($name)?>">
<label for="email">Email</label><input id="email" type="email" name="email" required maxlength="254" value="<?=e($email)?>">
<label for="password">Password (minimum 8 characters)</label><input id="password" type="password" name="password" required minlength="8">
<label for="confirm_password">Confirm password</label><input id="confirm_password" type="password" name="confirm_password" required minlength="8">
<p id="message" class="small" aria-live="polite"></p>
<button type="submit">Sign Up</button>
</form>
<p>Already have an account? <a href="login.php">Login</a></p>
<p class="small">Your account is saved in the student registration database.</p>
</main>
<script>
const p=document.getElementById('password'), c=document.getElementById('confirm_password'), m=document.getElementById('message');
function check(){const ok=p.value===c.value;c.setCustomValidity(ok?'':'Passwords do not match');m.textContent=c.value?(ok?'Passwords match.':'Passwords do not match.'):'';}
p.addEventListener('input',check);c.addEventListener('input',check);
</script></body></html>