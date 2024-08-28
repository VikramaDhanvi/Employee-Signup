<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "job_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set in the URL
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Fetch user details based on ID
    $sql = "SELECT name, email, phone, resume FROM job_seekers WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Output user details
        $row = $result->fetch_assoc();
        
        // Store the data in variables for later use in HTML
        $name = $row["name"];
        $email = $row["email"];
        $phone = $row["phone"];
        $resume = $row["resume"];
    } else {
        $error_message = "No user found with this ID.";
    }
} else {
    $error_message = "No user ID specified.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="../CSS/user_details.css">
</head>
<body>

<div class="container">
    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php else: ?>
        <h1>User Details</h1>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        
        <?php if (!empty($resume)): ?>
            <p><strong>Resume:</strong> <a href="<?php echo htmlspecialchars($resume); ?>" download>Download Resume</a></p>
        <?php else: ?>
            <p>No resume uploaded.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
