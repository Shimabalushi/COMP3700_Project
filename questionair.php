<?php
// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "activities");
if ($conn->connect_error) {
    die("<p>Connection failed: " . $conn->connect_error . "</p>");
}

// Retrieve and sanitize input
$phone = htmlspecialchars($_POST['phoneNumber']);
$email = htmlspecialchars($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$gender = htmlspecialchars($_POST['gender']);
$feedback1 = htmlspecialchars($_POST['feedback1']);
$feedback2 = htmlspecialchars($_POST['feedback2']);
$rate = htmlspecialchars($_POST['rate']);
$subscribe = isset($_POST['subscribe']) ? 1 : 0;

// Insert into DB
$sql = "INSERT INTO feedback (phone, email, password, gender, feedback1, feedback2, rate, subscribe)
        VALUES ('$phone', '$email', '$password', '$gender', '$feedback1', '$feedback2', '$rate', '$subscribe')";

echo '<!DOCTYPE html><html><head><title>Submission Received</title></head><body>';
if ($conn->query($sql) === TRUE) {
    echo "<h2>Feedback submitted successfully! 🎉</h2>";
    echo "<table border='1' cellpadding='10'>
            <tr><th>Phone</th><td>$phone</td></tr>
            <tr><th>Email</th><td>$email</td></tr>
            <tr><th>Gender</th><td>$gender</td></tr>
            <tr><th>Easy to explore?</th><td>$feedback1</td></tr>
            <tr><th>Suggestions</th><td>$feedback2</td></tr>
            <tr><th>Rating</th><td>$rate</td></tr>
            <tr><th>Subscribed</th><td>" . ($subscribe ? 'Yes' : 'No') . "</td></tr>
          </table>";
} else {
    echo "<p>Error: " . $conn->error . "</p>";
}
echo '</body></html>';
$conn->close();
?>
