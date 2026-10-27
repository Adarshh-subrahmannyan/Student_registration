<?php
session_start();
if (empty($_SESSION['student'])) { header('Location: login.php'); exit; }
$errors = $_SESSION['form_errors'] ?? [];
$old = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_old']);
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function old($key, $default='') { global $old; return e($old[$key] ?? $default); }
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Details Form</title><style>
body{font-family:Arial,sans-serif;background:#f1f4f8;margin:0;padding:25px;color:#222}.card{max-width:650px;margin:20px auto;background:white;padding:25px;border:1px solid #ddd;border-radius:8px}h1{color:#174a7e;font-size:25px}label{display:block;margin:13px 0 6px;font-weight:bold}input,select,textarea{width:100%;padding:10px;border:1px solid #bbb;border-radius:4px;box-sizing:border-box;font:inherit}button,.btn{display:inline-block;margin-top:17px;padding:11px 15px;background:#1d5c99;color:white;border:0;border-radius:4px;text-decoration:none;cursor:pointer}.error{background:#fff0f0;color:#8b2020;padding:10px;border-radius:4px}.top{display:flex;justify-content:space-between;gap:12px;align-items:center}.small{font-size:12px;color:#666}
</style></head><body><main class="card">
<div class="top"><div><h1>Student Details Form</h1><p>Welcome, <?=e($_SESSION['student']['name'])?></p></div><a href="login.php" class="btn">Back to Login</a></div>
<?php if ($errors): ?><div class="error"><strong>Please correct:</strong><ul><?php foreach($errors as $error): ?><li><?=e($error)?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form id="studentForm" action="submit.php" method="post">
<label for="full_name">Full name</label><input id="full_name" name="full_name" required maxlength="100" value="<?=old('full_name',$_SESSION['student']['name'])?>">
<label for="roll_number">Roll / registration number</label><input id="roll_number" name="roll_number" required maxlength="30" value="<?=old('roll_number')?>">
<label for="dob">Date of birth</label><input id="dob" name="dob" type="date" required max="<?=date('Y-m-d')?>" value="<?=old('dob')?>">
<label for="gender">Gender</label><select id="gender" name="gender" required><option value="">-- Select --</option><?php foreach(['Female','Male','Non-binary','Prefer not to say'] as $v): ?><option <?=($old['gender']===$v?'selected':'')?> value="<?=e($v)?>"><?=e($v)?></option><?php endforeach; ?></select>
<label for="course">Course</label><select id="course" name="course" required><option value="">-- Select course --</option><?php foreach(['B.Tech IT','B.Tech CSE','B.Tech ECE','BCA','MCA','Other'] as $v): ?><option <?=($old['course']===$v?'selected':'')?> value="<?=e($v)?>"><?=e($v)?></option><?php endforeach; ?></select>
<label for="year">Current year</label><select id="year" name="year" required><option value="">-- Select year --</option><?php foreach(['1st Year','2nd Year','3rd Year','4th Year'] as $v): ?><option <?=($old['year']===$v?'selected':'')?> value="<?=e($v)?>"><?=e($v)?></option><?php endforeach; ?></select>
<label for="phone">Phone number</label><input id="phone" name="phone" type="tel" pattern="[0-9+\-\s]{7,20}" required maxlength="20" value="<?=old('phone')?>">
<label for="address">Address</label><textarea id="address" name="address" rows="3" required maxlength="300"><?=old('address')?></textarea>
<label for="hobbies">Interests (optional)</label><input id="hobbies" name="hobbies" maxlength="150" value="<?=old('hobbies')?>">
<label><input style="width:auto" type="checkbox" name="confirm" value="yes" required> I confirm that these details are correct.</label>
<button type="submit">Submit Details</button></form>
<p class="small">This demonstration stores submitted information temporarily in the PHP session.</p>
</main><script>
document.getElementById('studentForm').addEventListener('submit',function(ev){const d=document.getElementById('dob').value;if(d && d>new Date().toISOString().slice(0,10)){ev.preventDefault();alert('Date of birth cannot be in the future.');}});
</script></body></html>