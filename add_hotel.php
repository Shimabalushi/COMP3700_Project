<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "activities";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotelName = mysqli_real_escape_string($conn, $_POST['hotelName']);
    $hotelLocation = mysqli_real_escape_string($conn, $_POST['hotelLocation']);
    $hotelDescription = mysqli_real_escape_string($conn, $_POST['hotelDescription']);
    $hotelRating = mysqli_real_escape_string($conn, $_POST['hotelRating']);

    // Ensure upload directory exists
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // File upload validation
    if (isset($_FILES["hotelImage"]) && $_FILES["hotelImage"]["error"] == 0) {
        $imageFileType = strtolower(pathinfo($_FILES["hotelImage"]["name"], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file type
        if (!in_array($imageFileType, $allowedTypes)) {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Validate file size (5MB max)
        if ($_FILES["hotelImage"]["size"] > 5000000) {
            die("Sorry, your file is too large.");
        }

        // Validate image content
        $check = getimagesize($_FILES["hotelImage"]["tmp_name"]);
        if ($check === false) {
            die("File is not a valid image.");
        }

        // Generate a unique file name
        $newFileName = uniqid("hotel_", true) . '.' . $imageFileType;
        $target_file = $target_dir . $newFileName;

        // Move the file
        if (move_uploaded_file($_FILES["hotelImage"]["tmp_name"], $target_file)) {
            // Use prepared statement to insert into DB
            $stmt = $conn->prepare("INSERT INTO hotels (name, location, description, rating, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssds", $hotelName, $hotelLocation, $hotelDescription, $hotelRating, $target_file);

            if ($stmt->execute()) {
                echo "✅ New hotel added successfully!";
            } else {
                echo "❌ Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "❌ Sorry, there was an error uploading your file.";
        }
    } else {
        echo "❌ No file uploaded or an error occurred during upload.";
    }
}

$conn->close();
?>
