<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "studentrecord";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the FormID and ViolationType from the POST data
    $FormID = $_POST['FormID'];
    $ViolationType = $_POST['ViolationType'];

    // Perform database update
    $sql = "UPDATE incidentform SET ViolationType = '$ViolationType' WHERE FormID = '$FormID'";
    
    // Check if the query executed successfully
    if (mysqli_query($connection, $sql)) { // Fixing the variable name here
        echo "Violation Type updated successfully";
    } else {
        echo "Error updating Violation Type: " . mysqli_error($connection); // Fixing the variable name here
    }
} else {
    echo "Invalid request";
}

?>

