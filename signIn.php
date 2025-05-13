<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "activities";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("<p>Connection failed: " . $conn->connect_error . "</p>");
}

$email = strtolower(trim($_POST['email']));
$password = $_POST['pass'];

// Query only by email first, concatenating first_name and last_name as full_name
$sql = "SELECT CONCAT(first_name, ' ', last_name) AS full_name, email, password FROM signUp WHERE email = ?";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    // Output the error message if the query preparation failed
    die("Error preparing the SQL statement: " . $conn->error);
}

$stmt->bind_param("s", $email); // Bind the email parameter to the query
$stmt->execute();
$result = $stmt->get_result();

// Set header for HTML content
header("Content-Type: text/html; charset=UTF-8");

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Sign In Result</title>
</head>
<body>
  <h2>Sign In Result</h2>

  <?php if ($result->num_rows > 0): ?>
    <?php 
    $row = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $row["password"])): 
    ?>
      <table border="1" cellpadding="10">
        <tr>
          <th>Full Name</th>
          <th>Email</th>
        </tr>
        <tr>
          <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
          <td><?php echo htmlspecialchars($row["email"]); ?></td>
        </tr>
      </table>
    <?php else: ?>
      <p>Incorrect password. Please try again.</p>
    <?php endif; ?>
  <?php else: ?>
    <p>No user found with that email.</p>
  <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
