<?php
// 1. DB Connection
$conn = new mysqli("localhost", "root", "", "activities");
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error . "</p>");
}

// 2. Collect and sanitize POST data
$username = htmlspecialchars($_POST['Username']);
$email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars($_POST['Phone-number']);
$birthday = $_POST['Birthday']; // format yyyy-mm-dd
$gender = htmlspecialchars($_POST['gender']);
$newPassword = $_POST['newPassword'];

// 3. Hash password only if provided
$hashedPassword = !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : null;

// 4. Insert or Update User
$sql = "INSERT INTO users (username, email, phone, birthday, gender" . ($hashedPassword ? ", password" : "") . ")
        VALUES (?, ?, ?, ?, ?" . ($hashedPassword ? ", ?" : "") . ")
        ON DUPLICATE KEY UPDATE 
        email = VALUES(email), 
        phone = VALUES(phone),
        birthday = VALUES(birthday),
        gender = VALUES(gender)"
        . ($hashedPassword ? ", password = VALUES(password)" : "");

$stmt = $conn->prepare($sql);

if ($hashedPassword) {
    $stmt->bind_param("ssssss", $username, $email, $phone, $birthday, $gender, $hashedPassword);
} else {
    $stmt->bind_param("sssss", $username, $email, $phone, $birthday, $gender);
}

echo '<!DOCTYPE html><html><head><title>Profile Updated</title></head><body>';

if ($stmt->execute()) {
    echo "<h2>Profile updated successfully! ✅</h2>";
    echo "<table border='1' cellpadding='10'>
            <tr><th>Username</th><td>$username</td></tr>
            <tr><th>Email</th><td>$email</td></tr>
            <tr><th>Phone</th><td>$phone</td></tr>
            <tr><th>Birthday</th><td>$birthday</td></tr>
            <tr><th>Gender</th><td>$gender</td></tr>
            <tr><th>Password Updated</th><td>" . ($hashedPassword ? 'Yes' : 'No') . "</td></tr>
          </table>";
} else {
    echo "<p>Error: " . $stmt->error . "</p>";
}

echo '</body></html>';
$stmt->close();
$conn->close();
?>
