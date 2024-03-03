<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "studentrecord";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

if (isset($_POST['form_id'], $_POST['status'])) {
    $form_id = $_POST['form_id'];
    $status = $_POST['status'];

    // Prepare SQL statement based on whether classification is provided or not
    if ($status !== '') {
        $sql = "UPDATE incidentform SET Status = '$status' WHERE FormID = '$form_id'";
    } else {
        $sql = "UPDATE incidentform SET Status = NULL WHERE FormID = '$form_id'";
    }
    
    // Execute SQL statement
    if ($connection->query($sql) === TRUE) {
        // Return success response
        http_response_code(200);
        echo "Status updated successfully";
    } else {
        // Return error response
        http_response_code(500);
        echo "Error updating Status: " . $connection->error;
    }
} else {
    // Return error response if required parameters are not provided
    http_response_code(400);
    echo "Bad request: Missing parameters";
}
?>
