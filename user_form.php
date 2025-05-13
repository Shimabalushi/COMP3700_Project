<?php

// Database connection
$host = 'localhost';
$user = 'root';       // or your DB user
$pass = '';           // or your DB password
$dbname = 'activities'; // change this

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ContactMessage class
class ContactMessage {
    public $name, $email, $subject, $message;

    public function __construct($name, $email, $subject, $message) {
        $this->name = htmlspecialchars($name);
        $this->email = htmlspecialchars($email);
        $this->subject = htmlspecialchars($subject);
        $this->message = htmlspecialchars($message);
    }

    public function displayRow() {
        return "<tr>
            <td>{$this->name}</td>
            <td>{$this->email}</td>
            <td>{$this->subject}</td>
            <td>{$this->message}</td>
        </tr>";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);


    // Validation
    if (strlen($name) >= 3 && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($subject) >= 5 && strlen($message) >= 10) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "<p>Invalid input. Please go back and correct the form.</p>";
        exit;
    }
}

// Retrieve messages
$messages = [];
$sql = "SELECT name, email, subject, message FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = new ContactMessage($row['name'], $row['email'], $row['subject'], $row['message']);
    }
}
$conn->close();
?>

<!-- XHTML Output -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Submitted Contact Messages</title>
</head>
<body>
    <h1>All Contact Messages</h1>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Subject</th><th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($messages as $msg) {
                echo $msg->displayRow();
            }
            ?>
        </tbody>
    </table>
</body>
</html>
