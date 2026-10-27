<?php
session_start();
if (empty($_SESSION['student'])) { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: form.php'); exit; }
require_once __DIR__ . '/db.php';

$keys = ['full_name','roll_number','dob','gender','course','year','phone','address','hobbies'];
$data = [];
foreach ($keys as $key) $data[$key] = trim((string)($_POST[$key] ?? ''));
$errors = [];
if (mb_strlen($data['full_name']) < 2 || mb_strlen($data['full_name']) > 100) $errors[] = 'Full name must be 2–100 characters.';
if ($data['roll_number'] === '' || mb_strlen($data['roll_number']) > 30) $errors[] = 'Enter a valid roll number.';
$date = DateTime::createFromFormat('Y-m-d', $data['dob']);
if (!$date || $date->format('Y-m-d') !== $data['dob'] || $data['dob'] > date('Y-m-d')) $errors[] = 'Enter a valid date of birth.';
if (!in_array($data['gender'], ['Female','Male','Non-binary','Prefer not to say'], true)) $errors[] = 'Select a valid gender.';
if (!in_array($data['course'], ['B.Tech IT','B.Tech CSE','B.Tech ECE','BCA','MCA','Other'], true)) $errors[] = 'Select a valid course.';
if (!in_array($data['year'], ['1st Year','2nd Year','3rd Year','4th Year'], true)) $errors[] = 'Select a valid year.';
if (!preg_match('/^[0-9+\-\s]{7,20}$/', $data['phone'])) $errors[] = 'Enter a valid phone number.';
if ($data['address'] === '' || mb_strlen($data['address']) > 300) $errors[] = 'Address is required and must be at most 300 characters.';
if (mb_strlen($data['hobbies']) > 150) $errors[] = 'Interests must be at most 150 characters.';
if (($_POST['confirm'] ?? '') !== 'yes') $errors[] = 'Please confirm your details.';

if ($errors) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old'] = $data;
    header('Location: form.php'); exit;
}

try {
    $pdo = database();
    $statement = $pdo->prepare(
        'INSERT INTO student_details
            (user_id, full_name, roll_number, dob, gender, course, current_year, phone, address, hobbies)
         VALUES
            (:user_id, :full_name, :roll_number, :dob, :gender, :course, :current_year, :phone, :address, :hobbies)
         ON DUPLICATE KEY UPDATE
            full_name = VALUES(full_name),
            roll_number = VALUES(roll_number),
            dob = VALUES(dob),
            gender = VALUES(gender),
            course = VALUES(course),
            current_year = VALUES(current_year),
            phone = VALUES(phone),
            address = VALUES(address),
            hobbies = VALUES(hobbies)'
    );
    $statement->execute([
        'user_id' => $_SESSION['student']['id'],
        'full_name' => $data['full_name'],
        'roll_number' => $data['roll_number'],
        'dob' => $data['dob'],
        'gender' => $data['gender'],
        'course' => $data['course'],
        'current_year' => $data['year'],
        'phone' => $data['phone'],
        'address' => $data['address'],
        'hobbies' => $data['hobbies'],
    ]);
    header('Location: output.php'); exit;
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    $_SESSION['form_old'] = $data;
    $_SESSION['database_error'] = 'Could not save your details. Check the database configuration and try again.';
    header('Location: form.php'); exit;
}
?>