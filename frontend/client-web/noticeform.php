<?php
$ViolatorName = $_POST['ViolatorName'];
$Department = $_POST['Department'];
$Contact = $_POST['Contact'];
$CourseYear = $_POST['CourseYear'];
$StudentNum = $_POST['StudentNum'];
$Date = $_POST['Date'];
$ExplanationDetails = $_POST['ExplanationDetails'];

//Database connection
$host = 'localhost';
$username = 'root'; 
$password = ''; 
$database = 'studentrecord';

$connect = new mysqli($host, $username, $password, $database);
if ($connect->connect_error) {
    die('Connection Failed: ' . $connect->connect_error);
} else {
    $stmt = $connect->prepare("INSERT INTO noticeform (ViolatorName, Department, Contact, CourseYear, StudentNum, Date, ExplanationDetails) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('Prepare failed: ' . $connect->error);
    }

    $stmt->bind_param("sssssss", $ViolatorName, $Department, $Contact, $CourseYear, $StudentNum, $Date, $ExplanationDetails);
    if ($stmt->execute() === false) {
        die('Execute failed: ' . $stmt->error);
    }

    $stmt->close();
    $connect->close();

    // Redirect with success parameter
    header("Location: noticeform.html?success=true");
    exit();
}
?>
