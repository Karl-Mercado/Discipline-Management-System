<?php
$ComplainantName = $_POST['ComplainantName'];
$StudentNum = $_POST['StudentNum'];
$ComplaintEmailAdd = $_POST['ComplaintEmailAdd'];
$CourseYear = $_POST['CourseYear'];
$ComplainDate = $_POST['ComplainDate'];
$SubjectComplaint = $_POST['SubjectComplaint'];
$ComplainDetails = $_POST['ComplainDetails'];
$Department = $_POST['Department']; // Adding the Department field

// Database connection
$host = 'localhost';
$username = 'root'; 
$password = ''; 
$database = 'studentrecord';

$connect = new mysqli($host, $username, $password, $database);
if ($connect->connect_error) {
    die('Connection Failed: ' . $connect->connect_error);
} else {
    $stmt = $connect->prepare("INSERT INTO incidentform (ComplainantName, StudentNum, ComplaintEmailAdd, CourseYear, ComplainDate, SubjectComplaint, ComplainDetails, Department) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . $connect->error);
    }

    $stmt->bind_param("ssssssss", $ComplainantName, $StudentNum, $ComplaintEmailAdd, $CourseYear, $ComplainDate, $SubjectComplaint, $ComplainDetails, $Department);
    if ($stmt->execute() === false) {
        die('Execute failed: ' . $stmt->error);
    }

    $stmt->close();
    $connect->close();

    // Redirect with success parameter
    header("Location: complaintform.html?success=true");
    exit();
}
?>
