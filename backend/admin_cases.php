<?php
// Check if the form has been submitted
if(isset($_POST['search'])) {
    // Retrieve the search value
    $valueToSearch = $_POST['valueToSearch'];
    
    // Construct the SQL query
    $query = "SELECT * FROM `incidentform` WHERE CONCAT(`FormID`, `Status`, `ComplainantName`, `StudentNum`, `ComplaintEmailAdd`, `CourseYear`, `ComplainDate`, `SubjectComplaint`, `CreatedDate`) LIKE '%$valueToSearch%'";
    
    // Execute the query to filter the results
    $search_result = filterTable($query);
} else {
    // If the form has not been submitted, retrieve all records
    $query = "SELECT * FROM `incidentform` WHERE STATUS != 'Under review'";
    $search_result = filterTable($query);
}


// Function to execute the SQL query and return the results
function filterTable($query) {
    $connect = mysqli_connect("localhost", "root", "", "studentrecord");
    $filter_Result = mysqli_query($connect, $query);
    return $filter_Result;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Violations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Include Font Awesome CSS file -->
    <link rel="stylesheet" href="admin_cases.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="header">
        <div class="user-icon">
            <i class="fas fa-user-circle"></i>
        </div>
        <h1>Admin</h1>
    
        <nav class="navigation">
            <ul>
                <li class="active"><i class="fas fa-chart-bar"></i><a href="http://localhost/OSA_DS/backend/admin_statistics.php">Statistics</a></li>
                <li><i class="fas fa-chart-bar"></i><a href="http://localhost/OSA_DS/backend/admin_notice.php">Notice forms</a></li>
                <li><i class="fas fa-exclamation-triangle"></i><a href="http://localhost/OSA_DS/backend/admin_index.php">Complaints</a></li>
                <li><i class="fas fa-exclamation-triangle"></i><a href="http://localhost/OSA_DS/backend/admin_cases.php">Cases</a></li>
                <li><i class="fas fa-balance-scale"></i><a href="http://localhost/OSA_DS/backend/admin_hearing.php">Hearing</a></li>
                <li><i class="fas fa-sign-out-alt"></i><a href="http://localhost/OSA_DS/frontend/client-web/home.html">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="container my-5">
    <div class="row justify-content-center"> <!-- Center the form -->
        <div class="col-md-6">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <!-- Add the search icon -->
                    <input type="text" class="form-control" name="valueToSearch" placeholder="Search Names">
                    <!-- Replace the non-breaking space with a space -->
                    <button class="btn btn-primary" type="submit" name="search">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- Main content -->
    <div class="container my-5">
        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="toggleModal()">&times;</span>
                <table class="table" id="modalTable">
                    <!-- This table will be dynamically populated -->
                </table>
            </div>
        </div>
        
        <table class="table">
    <thead>
        <tr>
            <th>FormID</th>
            <th>Status</th>
            <th>ComplainantName</th>
            <th>StudentNum</th>
            <th>CourseYear</th>
            <th>ComplainDate</th>
            <th>SubjectComplaint</th>
            <th>Department</th>
            <th>CreatedDate</th>
            <th>ViolatorName</th>
            <th>ViolationType</th> <!-- Add ViolationType column header -->
        </tr>
    </thead>
    <tbody>
    <?php while($row = mysqli_fetch_array($search_result)): ?>
        <tr data-formid="<?php echo $row['FormID']; ?>">
            <td><?php echo $row['FormID']; ?></td>
            <td id="statusCell_<?php echo $row['FormID']; ?>"><?php echo $row['Status']; ?></td>
            <td><?php echo $row['ComplainantName']; ?></td>
            <td><?php echo $row['StudentNum']; ?></td>
            <td><?php echo $row['CourseYear']; ?></td>
            <td><?php echo $row['ComplainDate']; ?></td>
            <td><?php echo $row['SubjectComplaint']; ?></td>
            <td><?php echo $row['Department']; ?></td>
            <td><?php echo $row['CreatedDate']; ?></td>
             
            <td>
                <input type="text" id="violatorName_<?php echo $row['FormID']; ?>" value="<?php echo $row['ViolatorName']; ?>">
                <button onclick="submitViolatorName('<?php echo $row['FormID']; ?>')">Submit</button>
            </td>
            <td><?php echo $row['ViolationType']; ?></td> <!-- Display ViolationType -->
            <td><button onclick='showRowDetails("<?php echo $row['FormID']; ?>", "<?php echo $row['Status']; ?>", "<?php echo $row['ComplainantName']; ?>", "<?php echo $row['StudentNum']; ?>", "<?php echo $row['ViolatorName']; ?>", "<?php echo $row['CourseYear']; ?>", "<?php echo $row['ComplainDate']; ?>", "<?php echo $row['SubjectComplaint']; ?>", "<?php echo $row['ComplainDetails']; ?>", "<?php echo $row['CreatedDate']; ?>", "<?php echo $row['Classification']; ?>", "<?php echo $row['ApproverUserID']; ?>", "<?php echo $row['ApproveDate']; ?>", "<?php echo $row['RejectionReason']; ?>", "<?php echo $row['RejectionDate']; ?>", "<?php echo $row['HearingProcess']; ?>")'>View</button></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>


            </tbody>
        </table>
    </div>

    <script>
    function showRowDetails(FormID, Status, ComplainantName, StudentNum, ViolatorName, CourseYear, ComplainDate, SubjectComplaint, ComplainDetails, CreatedDate, Classification, ApproverUserID, ApproveDate, RejectionReason, RejectionDate, HearingProcess, HearingDetails) {
        var modal = document.getElementById("myModal");
        var modalTable = document.getElementById("modalTable");

        if (Classification.trim().toLowerCase() === "minor") {
            // If classification is "Minor", only show select dropdown for status and a submit button
            modalTable.innerHTML = `
            <thead>
                <tr>
                    <th>Violator Name</th>
                    <th>Subject of Complaint</th>
                    <th>Complaint Details</th>
                    <th>Classification</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>${ViolatorName}</td>
                    <td>${SubjectComplaint}</td>
                    <td>${ComplainDetails}</td>
                    <td>${Classification}</td>
                    <td>
                        <select id="statusSelect_${FormID}">
                            <option value="Under Review">Under Review</option>
                            <option value="Closed">Closed</option>
                        </select>
                        <button onclick="submitStatus('${FormID}')">Submit</button>
                    </td>
                </tr>
            </tbody>
            `;
        } else {
            // If classification is not "Minor", show all fields
            modalTable.innerHTML = `
            <thead>
                <tr>
                    <th>Violator Name</th>
                    <th>Subject of Complaint</th>
                    <th>Complaint Details</th>
                    <th>Classification</th>
                    <th>Hearing Process</th>
                    <th>Hearing Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>${ViolatorName}</td>
                    <td>${SubjectComplaint}</td>
                    <td>${ComplainDetails}</td>
                    <td>${Classification}</td>
                    <td>
                        <select id="hearingProcessSelect">
                            <option value="Select">Select</option>
                            <option value="Under the Assistant Director Review">Under the Assistant Director Review</option>
                            <option value="Notice to Explain Form Sent to the Violator">Notice to Explain Form Sent to the Violator</option>
                            <option value="Forms will be Evaluated to Determine the Grounds">Forms will be Evaluated to Determine the Grounds</option>
                            <option value="Board of Discipline will be Summoned">Board of Discipline will be Summoned</option>
                            <option value="Meeting with the Board of Discipline">Meeting with the Board of Discipline</option>
                            <option value="Processing of the Complaint">Processing of the Complaint</option>
                            <option value="Final Decision of the Board of Discipline">Final Decision of the Board of Discipline</option>
                            <option value="Approval of the Dean to the Decision of the Board">Approval of the Dean to the Decision of the Board</option>
                        </select>
                        <button onclick="submitHearingProcess('${FormID}')">Submit</button>
                    </td>
                    <td>
                        <textarea id="hearingDetailsTextarea">${HearingDetails || ''}</textarea>
                        <button onclick="submitHearingDetails('${FormID}')">Submit</button>
                    </td>
                </tr>
            </tbody>
            `;
        }

        modal.style.display = "block";
    }

    function submitStatus(FormID) {
    var statusSelect = document.getElementById(`statusSelect_${FormID}`);
    var selectedStatus = statusSelect.value;

    // Save the status to local storage
    localStorage.setItem(`status_${FormID}`, selectedStatus);

    // Update the status in the table
    var statusCell = document.querySelector(`#statusCell_${FormID}`);
    if (statusCell) {
        statusCell.innerHTML = selectedStatus;
    }

    // Check if the selected status is "Close" and the complaint is classified as "Minor"
    if (selectedStatus === "Close") {
        // Perform AJAX request to update the database
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_form.php', true); // Replace 'update_form.php' with the actual URL of your PHP file
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Status updated successfully');
                // Optionally, you can perform additional actions after successful update
                alert('Status updated successfully');
            } else {
                console.error('Error updating status');
                alert('Error updating status');
            }
        };
        xhr.onerror = function() {
            console.error('Error updating status');
            alert('Error updating status');
        };
        xhr.send('submitStatus=true&FormID=' + FormID + '&selectedStatus=' + encodeURIComponent(selectedStatus));
    } else {
        // Perform submission of status for other cases
        // You can replace this with your actual submission logic
        console.log(`FormID: ${FormID}, Selected Status: ${selectedStatus}`);
        // Send the updated status to the server-side PHP script
    xhr.send(`submitStatus=true&FormID=${FormID}&newStatus=${encodeURIComponent(newStatus)}`);
    }
}

// Function to retrieve and update status from local storage on page load
function updateStatusFromLocalStorage() {
    // Loop through all status in local storage
    for (var i = 0; i < localStorage.length; i++) {
        var key = localStorage.key(i);
        // Check if the key starts with "status_"
        if (key.startsWith("status_")) {
            var FormID = key.split("_")[1];
            var status = localStorage.getItem(key);
            // Update status in the table
            var statusCell = document.querySelector(`#statusCell_${FormID}`);
            if (statusCell) {
                statusCell.innerHTML = status;
            }
        }
    }
}

// Call the function to update status from local storage on page load
updateStatusFromLocalStorage();



    function toggleModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }
</script>

    <script>
        function submitViolatorName(formId) {
    const violatorNameInput = document.getElementById('violatorName_' + formId);
    const violatorName = violatorNameInput.value;

    // Display a confirmation popup
    if (confirm('Are you sure you want to submit the violator name?')) {
        // Perform AJAX request to update the violator name in the database
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_form.php', true); // Replace 'update_form.php' with the actual URL of your PHP file
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Violator name updated successfully');
                // Optionally, you can perform additional actions after successful update
                alert('Violator name updated successfully');
            } else {
                console.error('Error updating violator name');
                alert('Error updating violator name');
            }
        };
        xhr.onerror = function() {
            console.error('Error updating violator name');
            alert('Error updating violator name');
        };
        xhr.send('submitViolatorName=true&formId=' + formId + '&violatorName=' + encodeURIComponent(violatorName));
    }
}

function submitStatus(FormID, newStatus) {
    var statusSelect = document.getElementById(`statusSelect_${FormID}`);
    var selectedStatus = statusSelect.value;

    // Save the status to local storage
    localStorage.setItem(`status_${FormID}`, selectedStatus);

    // Update the status in the table
    var statusCell = document.querySelector(`#statusCell_${FormID}`);
    if (statusCell) {
        statusCell.innerHTML = selectedStatus;
    }

    // Check if the selected status is "Approval of the Dean to the Decision of the Board"
    if (selectedStatus === "Approval of the Dean to the Decision of the Board") {
        // Automatically update the status to "Closed"
        selectedStatus = "Closed";
    }

    // Perform AJAX request to update the database
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_form.php', true); // Replace 'update_form.php' with the actual URL of your PHP file
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Status updated successfully');
            // Optionally, you can perform additional actions after successful update
            alert('Status updated successfully');
        } else {
            console.error('Error updating status');
            alert('Error updating status');
        }
    };
    xhr.onerror = function() {
        console.error('Error updating status');
        alert('Error updating status');
    };
    xhr.send('submitStatus=true&FormID=' + FormID + '&selectedStatus=' + encodeURIComponent(selectedStatus));
}




        function submitHearingDetails(formId) {
            const hearingDetailsTextarea = document.getElementById('hearingDetailsTextarea');
            const hearingDetails = hearingDetailsTextarea.value;

            // Display a confirmation popup
            if (confirm('Are you sure you want to submit the hearing details?')) {
                // Perform AJAX request to update the hearing details in the database
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_form.php', true); // Replace 'update_form.php' with the actual URL of your PHP file
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log('Hearing details updated successfully');
                        // Optionally, you can perform additional actions after successful update
                    } else {
                        console.error('Error updating hearing details');
                    }
                };
                xhr.onerror = function() {
                    console.error('Error updating hearing details');
                };
                xhr.send('submitHearingDetails=true&formId=' + formId + '&hearingDetails=' + encodeURIComponent(hearingDetails));
            }
        }
    
        function submitHearingProcess(formId) {
    const hearingProcessSelect = document.getElementById('hearingProcessSelect');
    const hearingProcess = hearingProcessSelect.value;

    // Display a confirmation popup
    if (confirm('Are you sure you want to submit the hearing process?')) {
        // Perform AJAX request to update the hearing process in the database
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_form.php', true); // Replace 'update_form.php' with the actual URL of your PHP file
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Hearing process updated successfully');
                // Check if the selected hearing process is "Approval of the Dean to the Decision of the Board"
                if (hearingProcess === "Approval of the Dean to the Decision of the Board") {
                    // Automatically update the status to "Closed"
                    submitStatus(formId, "Closed");
                }
            } else {
                console.error('Error updating hearing process');
            }
        };
        xhr.onerror = function() {
            console.error('Error updating hearing process');
        };
        xhr.send('submitHearingProcess=true&formId=' + formId + '&hearingProcess=' + encodeURIComponent(hearingProcess));
    }
}


    </script>
</body>
</html>
