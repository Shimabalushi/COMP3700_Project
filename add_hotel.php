<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "activities"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form inputs and sanitize them
    $hotelName = mysqli_real_escape_string($conn, $_POST['hotelName']);
    $hotelLocation = mysqli_real_escape_string($conn, $_POST['hotelLocation']);
    $hotelDescription = mysqli_real_escape_string($conn, $_POST['hotelDescription']);
    $hotelRating = mysqli_real_escape_string($conn, $_POST['hotelRating']);

    // Handle image upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["hotelImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["hotelImage"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (5MB max)
    if ($_FILES["hotelImage"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (JPG, JPEG, PNG, GIF)
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if the file can be uploaded
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Upload the file
        if (move_uploaded_file($_FILES["hotelImage"]["tmp_name"], $target_file)) {
            // Insert hotel details into the database
            $sql = "INSERT INTO hotels (name, location, description, rating, image) 
                    VALUES ('$hotelName', '$hotelLocation', '$hotelDescription', '$hotelRating', '$target_file')";

            if ($conn->query($sql) === TRUE) {
                echo "New hotel added successfully!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
