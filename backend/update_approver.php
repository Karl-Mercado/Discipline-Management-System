<?php
// Check if form_id and user_id are set and not empty
if(isset($_POST['form_id']) && isset($_POST['user_id']) && !empty($_POST['form_id']) && !empty($_POST['user_id'])) {
    // Sanitize input to prevent SQL injection
    $formId = intval($_POST['form_id']);
    $userId = intval($_POST['user_id']);

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "studentrecord";

    $connection = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Prepare and execute SQL statement to update ApproverUserID
    $stmt = $connection->prepare("UPDATE incidentform SET ApproverUserID = ? WHERE FormID = ?");
    $stmt->bind_param("ii", $userId, $formId);

    if ($stmt->execute()) {
        echo "Approver updated successfully";
    } else {
        echo "Error updating approver: " . $connection->error;
    }

    // Close statement and connection
    $stmt->close();
    $connection->close();
} else {
    echo "Invalid form ID or user ID";
}
?>
