<?php
// search.php

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "activities"); // change credentials if needed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$activityName = $category = $minPrice = $maxPrice = "";
$where = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["activityName"])) {
        $activityName = $conn->real_escape_string($_POST["activityName"]);
        $where[] = "a.activityName LIKE '%$activityName%'";
    }

    if (!empty($_POST["category"])) {
        $category = $conn->real_escape_string($_POST["category"]);
        $where[] = "c.companyName LIKE '%$category%'";
    }

    if (!empty($_POST["minPrice"])) {
        $min = intval($_POST["minPrice"]);
        $where[] = "CAST(REPLACE(a.price, ' OMR', '') AS UNSIGNED) >= $min";
    }

    if (!empty($_POST["maxPrice"])) {
        $max = intval($_POST["maxPrice"]);
        $where[] = "CAST(REPLACE(a.price, ' OMR', '') AS UNSIGNED) <= $max";
    }

    // Build query
    $query = "
        SELECT ai.id, a.activityName, a.price, c.companyName, ai.activityDescription, ai.activityIMG
        FROM activityInfo ai
        JOIN activity a ON ai.activityID = a.activityID
        JOIN company c ON ai.companyID = c.companyID
    ";

    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }

    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Search Activities</title>
    <meta charset="utf-8" />
</head>
<body>
    <h2>Search for Activities</h2>
    <form method="post" action="search.php">
        <p>Activity Name:</p>
        <input type="text" name="activityName" value="<?php echo htmlspecialchars($activityName); ?>" /><br />

        <p>Company Name:</p>
        <input type="text" name="category" value="<?php echo htmlspecialchars($category); ?>" /><br />

        <p>Min Price (OMR):</p>
        <input type="number" name="minPrice" value="<?php echo htmlspecialchars($minPrice); ?>" /><br />

        <p>Max Price (OMR):</p>
        <input type="number" name="maxPrice" value="<?php echo htmlspecialchars($maxPrice); ?>" /><br />
        <br>
        <input type="submit" value="Search" />
    </form>

    <?php if (isset($result)): ?>
        <h3>Search Results:</h3>
        <?php if ($result->num_rows > 0): ?>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Activity Name</th>
                    <th>Price</th>
                    <th>Company</th>
                    <th>Description</th>
                    <th>Image</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo htmlspecialchars($row["activityName"]); ?></td>
                        <td><?php echo htmlspecialchars($row["price"]); ?></td>
                        <td><?php echo htmlspecialchars($row["companyName"]); ?></td>
                        <td><?php echo htmlspecialchars($row["activityDescription"]); ?></td>
                        <td><img src="<?php echo htmlspecialchars($row["activityIMG"]); ?>" width="100" alt="Activity Image" /></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
