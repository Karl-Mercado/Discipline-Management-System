<?php
// Check if the form has been submitted
if (isset($_POST['formId']) && isset($_FILES['hearingDetailsFile'])) {
    $formId = $_POST['formId'];
    $file = $_FILES['hearingDetailsFile'];

    // Process file upload
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Check for file error
    if ($fileError === 0) {
        // Read file content
        $fileContent = file_get_contents($fileTmpName);

        // Perform database update
        $connect = mysqli_connect("localhost", "root", "", "studentrecord");
        $query = "UPDATE `incidentform` SET `HearingDetailsFile`='$fileContent' WHERE `FormID`='$formId'";
        $result = mysqli_query($connect, $query);

        if ($result) {
            echo "Hearing details updated successfully";
        } else {
            echo "Error updating hearing details";
        }
    } else {
        echo "File upload error: " . $fileError;
    }
} else {
    echo "Invalid request";
}
?>
