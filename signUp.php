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

// Check if it's an update or sign up
if (isset($_POST['oldEmail'])) {
    // ==== UPDATE FLOW ====
    $oldEmail = htmlspecialchars(strtolower(trim($_POST['oldEmail'])));
    $newUsername = htmlspecialchars($_POST['username']);
    $newEmail = htmlspecialchars(strtolower(trim($_POST['email'])));
    $newPassword = $_POST['newPassword'];

    // Validate new password
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $newPassword)) {
        die("<p>New password must be at least 8 characters long and contain at least one letter and one number.</p>");
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Check if old email exists
    $checkSql = "SELECT * FROM signUp WHERE email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $oldEmail);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        die("<p>The provided old email does not exist.</p>");
    }

    // Update details
    $updateSql = "UPDATE signUp SET username = ?, email = ?, password = ? WHERE email = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssss", $newUsername, $newEmail, $hashedPassword, $oldEmail);

    if (!$updateStmt->execute()) {
        die("<p>Error updating record: " . htmlspecialchars($updateStmt->error) . "</p>");
    }

    $updateStmt->close();
    $checkStmt->close();
    $conn->close();

    header("Location: sign in.html");
    exit();

} else {
    // ==== SIGN UP FLOW ====
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

    // Check password match
    if ($password !== $confirmPassword) {
        die("<p>Passwords do not match.</p>");
    }

    // Validate password strength
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
        die("<p>Password must be at least 8 characters long and contain at least one letter and one number.</p>");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL insert
    $sql = "INSERT INTO signUp (user_type, gender, first_name, last_name, username, birthday, email, phone_number, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $userType, $gender, $firstName, $lastName, $username, $birthday, $email, $phone, $hashedPassword);

    if (!$stmt->execute()) {
        die("<p>Error: " . htmlspecialchars($stmt->error) . "</p>");
    }

    $stmt->close();
    $conn->close();

    header("Location: sign in.html");
    exit();
}
?>
