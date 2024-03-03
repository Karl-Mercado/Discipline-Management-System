<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "studentrecord";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

if (isset($_POST['form_id'], $_POST['classification'])) {
    $form_id = $_POST['form_id'];
    $classification = $_POST['classification'];

    // Prepare SQL statement based on whether classification is provided or not
    if ($classification !== '') {
        $sql = "UPDATE incidentform SET Classification = '$classification' WHERE FormID = '$form_id'";
    } else {
        $sql = "UPDATE incidentform SET Classification = NULL WHERE FormID = '$form_id'";
    }
    
    // Execute SQL statement
    if ($connection->query($sql) === TRUE) {
        // Return success response
        http_response_code(200);
        echo "Classification updated successfully";
    } else {
        // Return error response
        http_response_code(500);
        echo "Error updating classification: " . $connection->error;
    }
} else {
    // Return error response if required parameters are not provided
    http_response_code(400);
    echo "Bad request: Missing parameters";
}
?>
