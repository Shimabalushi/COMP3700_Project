<?php
// signUp.php

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "activities";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("<p>Connection failed: " . $conn->connect_error . "</p>");
}

// Collect and sanitize form data
$userType = $_POST['userType'];
$gender = $_POST['gender'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$username = $_POST['username'];
$birthday = $_POST['birthday'];
$email = $_POST['email'];
$phone = $_POST['phoneNumber'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Simple password match check
if ($password !== $confirmPassword) {
  die("<p>Passwords do not match.</p>");
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL
$sql = "INSERT INTO users (user_type, gender, first_name, last_name, username, birthday, email, phone_number, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $userType, $gender, $firstName, $lastName, $username, $birthday, $email, $phone, $hashedPassword);

// Execute and check for errors
if (!$stmt->execute()) {
  die("<p>Error: " . htmlspecialchars($stmt->error) . "</p>");
}

// Show success XHTML
header("Content-Type: application/xhtml+xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Signup Success</title>
  <style>
    table { border-collapse: collapse; width: 70%; margin: 20px auto; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
  <h2 style="text-align: center;">Signup Successful</h2>
  <table>
    <tr><th>User Type</th><td><?php echo htmlspecialchars($userType); ?></td></tr>
    <tr><th>Gender</th><td><?php echo htmlspecialchars($gender); ?></td></tr>
    <tr><th>First Name</th><td><?php echo htmlspecialchars($firstName); ?></td></tr>
    <tr><th>Last Name</th><td><?php echo htmlspecialchars($lastName); ?></td></tr>
    <tr><th>Username</th><td><?php echo htmlspecialchars($username); ?></td></tr>
    <tr><th>Birthday</th><td><?php echo htmlspecialchars($birthday); ?></td></tr>
    <tr><th>Email</th><td><?php echo htmlspecialchars($email); ?></td></tr>
    <tr><th>Phone Number</th><td><?php echo htmlspecialchars($phone); ?></td></tr>
  </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
