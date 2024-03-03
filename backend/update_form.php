<?php
// Check if the form has been submitted
if(isset($_POST['search'])) {
    // Retrieve the search value
    $valueToSearch = $_POST['valueToSearch'];
    
    // Construct the SQL query with prepared statement
    $connect = mysqli_connect("localhost", "root", "", "studentrecord");
    $query = "SELECT * FROM `incidentform` WHERE CONCAT(`FormID`, `StatusID`, `ComplainantName`, `StudentNum`, `ComplaintEmailAdd`, `CourseYear`, `ComplainDate`, `SubjectComplaint`, `CreatedDate`) LIKE ?";
    $stmt = mysqli_prepare($connect, $query);
    $search_param = "%$valueToSearch%";
    mysqli_stmt_bind_param($stmt, "s", $search_param);
    mysqli_stmt_execute($stmt);
    $search_result = mysqli_stmt_get_result($stmt);
} else {
    // If the form has not been submitted, retrieve all records
    $query = "SELECT * FROM `incidentform`";
    $connect = mysqli_connect("localhost", "root", "", "studentrecord");
    $search_result = mysqli_query($connect, $query);
}

// Function to update the hearing process in the database
function updateHearingProcess($formId, $hearingProcess) {
    $connect = mysqli_connect("localhost", "root", "", "studentrecord");
    $hearingProcess = mysqli_real_escape_string($connect, $hearingProcess);
    $query = "UPDATE `incidentform` SET `HearingProcess` = ? WHERE `FormID` = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "si", $hearingProcess, $formId);
    mysqli_stmt_execute($stmt);
    echo "Hearing process updated successfully."; // Debugging statement
}

// Function to update the hearing details in the database
function updateHearingDetails($formId, $hearingDetails) {
    $connect = mysqli_connect("localhost", "root", "", "studentrecord");
    $hearingDetails = mysqli_real_escape_string($connect, $hearingDetails);
    $query = "UPDATE `incidentform` SET `HearingDetails` = ? WHERE `FormID` = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "si", $hearingDetails, $formId);
    mysqli_stmt_execute($stmt);
    echo "Hearing details updated successfully."; // Debugging statement
}

// Function to update the violator name in the database
function updateViolatorName($formId, $violatorName) {
    $connect = mysqli_connect("localhost", "root", "", "studentrecord");
    $violatorName = mysqli_real_escape_string($connect, $violatorName);
    $query = "UPDATE `incidentform` SET `ViolatorName` = ? WHERE `FormID` = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "si", $violatorName, $formId);
    mysqli_stmt_execute($stmt);
    echo "Violator name updated successfully."; // Debugging statement
}

// Check if the hearing process form is submitted
if(isset($_POST['submitHearingProcess'])) {
    $formId = $_POST['formId'];
    $hearingProcess = $_POST['hearingProcess'];
    updateHearingProcess($formId, $hearingProcess);
}

// Check if the hearing details form is submitted
if(isset($_POST['submitHearingDetails'])) {
    $formId = $_POST['formId'];
    $hearingDetails = $_POST['hearingDetails'];
    updateHearingDetails($formId, $hearingDetails);
}

// Check if the violator name form is submitted
if(isset($_POST['submitViolatorName'])) {
    $formId = $_POST['formId'];
    $violatorName = $_POST['violatorName'];
    updateViolatorName($formId, $violatorName);
}


 
// Check if the status update form is submitted
if(isset($_POST['submitStatus'])) {
    $formId = $_POST['FormID'];
    $newStatus = $_POST['newStatus'];
    updateStatus($formId, $newStatus);
    
}

// Function to update the status in the database
function updateStatus($formId, $newStatus) {
    $connect = mysqli_connect("localhost", "root", "", "studentrecord");
    $newStatus = mysqli_real_escape_string($connect, $newStatus);
    $query = "UPDATE `incidentform` SET `Status` = '$newStatus' WHERE `FormID` = '$formId'";
    mysqli_query($connect, $query);
    // Check if the update was successful
    if(mysqli_affected_rows($connect) > 0) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status.";
    }
}

// Close the database connection
// mysqli_close($connect);
?>
