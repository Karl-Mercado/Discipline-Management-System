// update_approve_date.php
<?php
$formId = $_POST['form_id'];
$approveDate = date("Y-m-d"); // Get current date

$servername = "localhost";
$username = "root";
$password = "";
$database = "studentrecord";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error){
    die("Connection failed: " . $connection->connect_error);
}

$sql = "UPDATE incidentform SET ApproveDate = '$approveDate' WHERE FormID = '$formId'";

if ($connection->query($sql) === TRUE) {
    echo "ApproveDate updated successfully";
} else {
    echo "Error updating ApproveDate: " . $connection->error;
}

$connection->close();
?>
