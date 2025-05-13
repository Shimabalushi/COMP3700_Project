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
$userType = htmlspecialchars($_POST['userType']);
$gender = htmlspecialchars($_POST['gender']);
$firstName = htmlspecialchars($_POST['firstName']);
$lastName = htmlspecialchars($_POST['lastName']);
$username = htmlspecialchars($_POST['username']);
$birthday = htmlspecialchars($_POST['birthday']);
$email = htmlspecialchars(strtolower(trim($_POST['email'])));
$phone = htmlspecialchars($_POST['phoneNumber']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Simple password match check
if ($password !== $confirmPassword) {
  die("<p>Passwords do not match.</p>");
}

// Validate password strength (example: at least 8 characters, 1 letter, 1 number)
if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
  die("<p>Password must be at least 8 characters long and contain at least one letter and one number.</p>");
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL
$sql = "INSERT INTO signUp (user_type, gender, first_name, last_name, username, birthday, email, phone_number, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $userType, $gender, $firstName, $lastName, $username, $birthday, $email, $phone, $hashedPassword);

// Execute and check for errors
if (!$stmt->execute()) {
  die("<p>Error: " . htmlspecialchars($stmt->error) . "</p>");
}

// Close the statement and connection
$stmt->close();
$conn->close();

header("Location: sign in.html");
exit();


?>
