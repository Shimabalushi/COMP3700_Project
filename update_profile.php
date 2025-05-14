<?php
// 1. DB Connection
$conn = new mysqli("localhost", "root", "", "activities");
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error . "</p>");
}

// 2. Collect and sanitize POST data
$oldEmail = filter_var($_POST['oldEmail'], FILTER_SANITIZE_EMAIL);
$username = htmlspecialchars($_POST['username']);
$newEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$newPassword = $_POST['newPassword'] ?? null;

// 3. Validate required fields
if (empty($oldEmail) || empty($username) || empty($newEmail)) {
    die("Error: Required fields are missing.");
}

// 4. First verify the old email exists
$checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkStmt->bind_param("s", $oldEmail);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows === 0) {
    header("Location: profile.php?error=email_not_found");
    exit();
}
$checkStmt->close();

// 5. Prepare update query
if (!empty($newPassword)) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateStmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE email = ?");
    $updateStmt->bind_param("ssss", $username, $newEmail, $hashedPassword, $oldEmail);
} else {
    $updateStmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE email = ?");
    $updateStmt->bind_param("sss", $username, $newEmail, $oldEmail);
}

// 6. Execute the update
if ($updateStmt->execute()) {
    header("Location: profile.php?success=1");
    exit();
} else {
    header("Location: profile.php?error=update_failed");
    exit();
}

$updateStmt->close();
$conn->close();
?>
