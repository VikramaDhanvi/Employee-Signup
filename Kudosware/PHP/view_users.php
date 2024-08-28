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

// Fetch all users
$sql = "SELECT id, name FROM job_seekers";
$result = $conn->query($sql);

// Start building the user list
$userList = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userList .= "<li><a href='../PHP/user_details.php?id=" . $row["id"] . "'>" . $row["name"] . "</a></li>";
    }
} else {
    $userList .= "<li>No users found.</li>";
}

$conn->close();

// Load the HTML template
$templatePath = "../HTML/user_template.html";
$dom = new DOMDocument();
libxml_use_internal_errors(true); // Ignore HTML warnings
$dom->loadHTMLFile($templatePath);
libxml_clear_errors();

// Find the <ul> element and clear its content
$ulElements = $dom->getElementsByTagName('ul');
if ($ulElements->length > 0) {
    $ul = $ulElements->item(0);

    // Clear existing content
    while ($ul->hasChildNodes()) {
        $ul->removeChild($ul->firstChild);
    }

    // Create a new DOMDocumentFragment to hold the new user list
    $fragment = $dom->createDocumentFragment();
    $fragment->appendXML($userList);

    // Append the fragment to the <ul> element
    $ul->appendChild($fragment);
    
    // Save the updated HTML back to a new file
    $outputPath = "../HTML/user_list.html";
    $dom->saveHTMLFile($outputPath);

    // Redirect to the updated HTML file
    header("Location: " . $outputPath);
    exit;
} else {
    die("Failed to find the <ul> element in the template.");
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

?>
