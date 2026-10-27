<?php
session_start();
if (empty($_SESSION['student'])) { header('Location: login.php'); exit; }
if (empty($_SESSION['submitted_student'])) { header('Location: form.php'); exit; }
$data = $_SESSION['submitted_student'];
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$labels = ['full_name'=>'Full name','roll_number'=>'Roll / registration number','dob'=>'Date of birth','gender'=>'Gender','course'=>'Course','year'=>'Current year','phone'=>'Phone number','address'=>'Address','hobbies'=>'Interests'];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registration Output</title><style>
body{font-family:Arial,sans-serif;background:#f1f4f8;margin:0;padding:25px;color:#222}.card{max-width:650px;margin:30px auto;background:white;padding:25px;border:1px solid #ddd;border-radius:8px}h1{color:#174a7e;font-size:25px}.success{background:#eaf8ee;color:#216536;padding:12px;border-radius:4px}table{width:100%;border-collapse:collapse;margin:18px 0}th,td{border:1px solid #ccd4dd;padding:11px;text-align:left;overflow-wrap:anywhere}th{background:#edf2f7;width:38%}.btn{display:inline-block;margin:8px 8px 0 0;padding:11px 15px;background:#1d5c99;color:white;border-radius:4px;text-decoration:none}.secondary{background:#5b6572}.small{font-size:12px;color:#666}
</style></head><body><main class="card">
<h1>Registration Successful</h1><p class="success">Your student details were validated and submitted successfully.</p>
<h2>Submitted Information</h2><table><?php foreach($labels as $key=>$label): ?><tr><th><?=e($label)?></th><td><?=nl2br(e($data[$key] ?? '—'))?></td></tr><?php endforeach; ?></table>
<a class="btn" href="form.php">Edit Details</a><a class="btn secondary" href="login.php">Login Page</a>
<p class="small">Session-only demo: data is temporary and is not saved to a database.</p>
</main></body></html>