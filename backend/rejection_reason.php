<?php
// Check if form_id and rejection_reason are provided
if (isset($_POST['form_id'], $_POST['rejection_reason'])) {
    // Sanitize and validate input
    $form_id = $_POST['form_id'];
    $rejection_reason = $_POST['rejection_reason'];
    $rejection_date = date("Y-m-d H:i:s"); // Get the current date and time

    // Perform database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "studentrecord";

    $connection = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Prepare SQL statement to update rejection reason and date
    $sql = "UPDATE incidentform SET RejectionReason = ?, RejectionDate = ? WHERE FormID = ?";
    $statement = $connection->prepare($sql);

    // Check if the SQL statement was prepared successfully
    if ($statement === false) {
        die("Prepare failed: " . $connection->error);
    }

    // Bind parameters and execute the statement
    $statement->bind_param("ssi", $rejection_reason, $rejection_date, $form_id);
    $result = $statement->execute();

    if ($result === false) {
        die("Execution failed: " . $statement->error);
    } else {
        echo "Rejection reason and date updated successfully";
    }

    // Close statement and connection
    $statement->close();
    $connection->close();
} else {
    echo "Error: Missing form_id or rejection_reason";
}
?>
