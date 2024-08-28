<?php
$servername = "localhost:3306";
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$dbname = "job_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Basic validation for required fields
    if (empty($name) || empty($email) || empty($phone) || empty($_FILES['resume']['name'])) {
        echo "Please fill all required fields and upload your resume.";
        exit();
    }

    // File upload handling
    $resume_dir = "../uploads/";
    $resume_file = $resume_dir . basename($_FILES["resume"]["name"]);
    $uploadOk = 1;
    $resumeFileType = strtolower(pathinfo($resume_file, PATHINFO_EXTENSION));

    // Ensure the uploads directory exists
    if (!is_dir($resume_dir)) {
        if (!mkdir($resume_dir, 0755, true)) {
            die("Failed to create directory for uploads.");
        }
    }

    // Check file type
    if ($resumeFileType != "pdf" && $resumeFileType != "doc" && $resumeFileType != "docx") {
        echo "Sorry, only PDF, DOC, and DOCX files are allowed.";
        $uploadOk = 0;
    }

    // Check file size (optional: limit file size, e.g., 2MB)
    if ($_FILES["resume"]["size"] > 2000000) { // 2MB limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $resume_file)) {
            // Insert data into the database
            $sql = "INSERT INTO job_seekers (name, email, phone, resume) VALUES ('$name', '$email', '$phone', '$resume_file')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully. Your resume has been uploaded.";
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
