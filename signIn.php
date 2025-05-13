<?php
// signIn.php

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "activities";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
  die("<p>Connection failed: " . $conn->connect_error . "</p>");
}

$email = $_POST['email'];
$password = $_POST['pass'];

// Query the database for user
$sql = "SELECT full_name, email FROM users WHERE email = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

header("Content-Type: application/xhtml+xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Sign In Result</title>
</head>
<body>
  <h2>Sign In Result</h2>

  <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="10">
      <tr>
        <th>Full Name</th>
        <th>Email</th>
      </tr>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
          <td><?php echo htmlspecialchars($row["email"]); ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No matching user found. Please check your credentials.</p>
  <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
