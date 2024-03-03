<?php
// Retrieve the form data from the AJAX request
$FormID = $_POST['FormID'];
$status = $_POST['status'];
$violationType = $_POST['violationType'];
$classification = $_POST['classification'];

// Example: Inserting data into a MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "studentrecord";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to insert form data into a table
$sql = "UPDATE incidentform SET Status='$status', ViolationType='$violationType', Classification='$classification' WHERE FormID='$FormID'";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
    echo "Form data updated successfully";
} else {
    echo "Error updating form data: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
