<?php
// Database connection parameters
$host = 'localhost';
$db   = 'activities';
$user = 'root';
$pass = '';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Handle connection error
    echo 'Database connection failed: ' . $e->getMessage();
    exit;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $phone_number     = $_POST['phone-number'] ?? '';
    $email_address    = $_POST['email-address'] ?? '';
    $card_name        = $_POST['card-name'] ?? '';
    $card_number      = $_POST['card-number'] ?? '';
    $expiration_date  = $_POST['expiration-date'] ?? '';
    $cvv              = $_POST['cvv'] ?? '';

    // Prepare SQL statement
    $sql = "INSERT INTO payments (phone_number, email_address, card_name, card_number, expiration_date, cvv)
            VALUES (:phone_number, :email_address, :card_name, :card_number, :expiration_date, :cvv)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters and execute statement
    $stmt->execute([
        ':phone_number'    => $phone_number,
        ':email_address'   => $email_address,
        ':card_name'       => $card_name,
        ':card_number'     => $card_number,
        ':expiration_date' => $expiration_date,
        ':cvv'             => $cvv,
    ]);

    // Display success message
    echo '<h2>Payment Successful</h2>';
    echo '<p>Thank you for your payment. Here are the details:</p>';
    echo '<ul>';
    echo '<li><strong>Phone Number:</strong> ' . htmlspecialchars($phone_number) . '</li>';
    echo '<li><strong>Email Address:</strong> ' . htmlspecialchars($email_address) . '</li>';
    echo '<li><strong>Card Holder Name:</strong> ' . htmlspecialchars($card_name) . '</li>';
    echo '<li><strong>Card Number:</strong> ' . htmlspecialchars($card_number) . '</li>';
    echo '<li><strong>Expiration Date:</strong> ' . htmlspecialchars($expiration_date) . '</li>';
    echo '</ul>';
} else {
    // If not a POST request, redirect to the form page
    header('Location: payment_form.html');
    exit;
}
?>
