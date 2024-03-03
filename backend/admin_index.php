<?php
// Check if the form has been submitted
if(isset($_POST['search'])) {
    // Retrieve the search value
    $valueToSearch = $_POST['valueToSearch'];
    
    // Construct the SQL query
    $query = "SELECT * FROM `incidentform` WHERE CONCAT(`FormID`, `Status`, `ComplainantName`, `StudentNum`, `ComplaintEmailAdd`, `Department`, `CourseYear`, `ComplainDate`, `SubjectComplaint`, `CreatedDate` ) LIKE '%$valueToSearch%'";
    
    // Execute the query to filter the results
    $search_result = filterTable($query);
} else {
    // If the form has not been submitted, retrieve all records
    $query = "SELECT * FROM `incidentform` WHERE REJECTIONDATE IS NULL AND STATUS = 'Under review' ";
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
    <title>Complaints Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Include Font Awesome CSS file -->
    <link rel="stylesheet" href="admin_index.css">
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
                    <input type="text" class="form-control" name="valueToSearch" placeholder="Search">
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
                    <th>ComplainantEmailAdd</th>
                    <th>Department</th>
                    <th>CourseYear</th>
                    <th>ComplaintDate</th>
                    <th>SubjectComplaint</th>
                    <th>CreatedDate</th>
                      
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_array($search_result)): ?>
                <tr>
                    <td><?php echo $row['FormID']; ?></td>
                    <td><?php echo $row['Status']; ?></td>
                    <td><?php echo $row['ComplainantName']; ?></td>
                    <td><?php echo $row['StudentNum']; ?></td>
                    <td><?php echo $row['ComplaintEmailAdd']; ?></td>
                    <td><?php echo $row['Department']; ?></td> <
                    <td><?php echo $row['CourseYear']; ?></td>
                    <td><?php echo $row['ComplainDate']; ?></td>
                    <td><?php echo $row['SubjectComplaint']; ?></td>
                    <td><?php echo $row['CreatedDate']; ?></td>
                    <td><button onclick='showRowDetails("<?php echo $row['FormID']; ?>", "<?php echo $row['Status']; ?>", "<?php echo $row['ComplainantName']; ?>", "<?php echo $row['StudentNum']; ?>", "<?php echo $row['ComplaintEmailAdd']; ?>", "<?php echo $row['Department']; ?>", "<?php echo $row['CourseYear']; ?>", "<?php echo $row['ComplainDate']; ?>", "<?php echo $row['SubjectComplaint']; ?>", "<?php echo $row['ComplainDetails']; ?>", "<?php echo $row['CreatedDate']; ?>", "<?php echo $row['Classification']; ?>", "<?php echo $row['ApproverUserID']; ?>", "<?php echo $row['ApproveDate']; ?>", "<?php echo $row['RejectionReason']; ?>", "<?php echo $row['RejectionDate']; ?>", "<?php echo $row['HearingProcess']; ?>")'>View</button></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
function showRowDetails(FormID, Status, ComplainantName, StudentNum, ComplaintEmailAdd, CourseYear, ComplainDate, SubjectComplaint, ComplainDetails, CreatedDate, Classification, ApproverUserID, ApproveDate, RejectionReason, RejectionDate, HearingProcess) {
    var modal = document.getElementById("myModal");
    var modalTable = document.getElementById("modalTable");
    modalTable.innerHTML = `
        <thead>
            <tr>
                <th>Status</th>
                <th>Complaint Details</th>
                <th>Violation Type</th>
                <th>Classification</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select id="statusSelect_${FormID}">
                        <option value="">--Select--</option>
                        <option value="Under review" ${Status === 'Under review' ? 'selected' : ''}>Under review</option>
                        <option value="Ongoing" ${Status === 'Ongoing' ? 'selected' : ''}>Ongoing</option>
                        <option value="Closed" ${Status === 'Closed' ? 'selected' : ''}>Closed</option>
                    </select>
                </td>
                <td>${ComplainDetails}</td>
                <td>
                    <input type="text" id="violationType_${FormID}" placeholder="Enter Violation Type">
                </td>
                <td>
                    <select id="classificationSelect_${FormID}">
                        <option value="">--Select--</option>
                        <option value="MINOR" ${Classification === 'MINOR' ? 'selected' : ''}>MINOR</option>
                        <option value="MAJOR" ${Classification === 'MAJOR' ? 'selected' : ''}>MAJOR</option>
                    </select>
                </td>
                <td>
                    <button onclick="submitForm('${FormID}')">Submit</button>
                </td>
            </tr>
        </tbody>
    `;

    // Adding approve and reject buttons directly without a table
    modalTable.innerHTML += `
        <div class="button-container">
            <button onclick="approveForm('${FormID}')">Approve</button>
            <button onclick="rejectForm('${FormID}')">Reject</button>
        </div>
    `;

    modal.style.display = "block";
}

function toggleModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
}

 
</script>


<script>
function submitForm(FormID) {
    // Prompt the user for confirmation
    if (confirm("Are you sure you want to submit this form?")) {
        var status = document.getElementById("statusSelect_" + FormID).value;
        var violationType = document.getElementById("violationType_" + FormID).value;
        var classification = document.getElementById("classificationSelect_" + FormID).value;
        
        // Create an AJAX request
        var xhr = new XMLHttpRequest();
        
        // Define the PHP script URL
        var url = "process_form.php";
        
        // Prepare the data to be sent
        var data = "FormID=" + encodeURIComponent(FormID) + "&status=" + encodeURIComponent(status) + "&violationType=" + encodeURIComponent(violationType) + "&classification=" + encodeURIComponent(classification);
        
        // Configure the AJAX request
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        // Define what happens on successful AJAX submission
        xhr.onreadystatechange = function() {
            if(xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response from the server if needed
                console.log(xhr.responseText);
                // Optionally, you can handle the response here, e.g., display a message to the user
            }
        };
        
        // Send the AJAX request with the form data
        xhr.send(data);
    }
}function submitForm(FormID) {
    // Prompt the user for confirmation
    if (confirm("Are you sure you want to submit this form?")) {
        var status = document.getElementById("statusSelect_" + FormID).value;
        var violationType = document.getElementById("violationType_" + FormID).value;
        var classification = document.getElementById("classificationSelect_" + FormID).value;
        
        // Create an AJAX request
        var xhr = new XMLHttpRequest();
        
        // Define the PHP script URL
        var url = "process_form.php";
        
        // Prepare the data to be sent
        var data = "FormID=" + encodeURIComponent(FormID) + "&status=" + encodeURIComponent(status) + "&violationType=" + encodeURIComponent(violationType) + "&classification=" + encodeURIComponent(classification);
        
        // Configure the AJAX request
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        // Define what happens on successful AJAX submission
        xhr.onreadystatechange = function() {
            if(xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response from the server if needed
                console.log(xhr.responseText);
                // Optionally, you can handle the response here, e.g., display a message to the user
            }
        };
        
        // Send the AJAX request with the form data
        xhr.send(data);
    }
}

/*
function submitViolationType(FormID) {
    var violationType = document.getElementById(`violationType_${FormID}`).value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            // Optionally, you can handle the response here
        }
    };
    xhttp.open("POST", "update_violationType.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("FormID=" + encodeURIComponent(FormID) + "&ViolationType=" + encodeURIComponent(violationType));
}

function submitClassification(formId) {
    const classificationSelect = document.getElementById('classificationSelect_' + formId);
    const newClassification = classificationSelect.value;

    // Prompt user for confirmation
    if (confirm("Are you sure you want to submit this classification?")) {
        // Perform AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_classification.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Handle success
                console.log('Classification updated successfully');
            } else {
                // Handle error
                console.error('Error updating classification');
            }
        };
        xhr.onerror = function() {
            console.error('Error updating classification');
        };
        xhr.send('form_id=' + formId + '&classification=' + newClassification);
    }
}


function statusSelect(formId) {
    const statusSelect = document.getElementById('statusSelect_' + formId);
    const newStatus = statusSelect.value;

    // Prompt user for confirmation
    if (confirm("Are you sure you want to submit this status?")) {
        // Perform AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_status.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Handle success
                console.log('Status updated successfully');
            } else {
                // Handle error
                console.error('Error updating status');
            }
        };
        xhr.onerror = function() {
            console.error('Error updating status');
        };
        xhr.send('form_id=' + formId + '&status=' + newStatus);
    }
}
*/

function approveForm(formId) {
    // Prompt user for confirmation
    if (confirm("Are you sure you want to approve this form?")) {
        // Update ApproveDate in the database
        const xhrApproveDate = new XMLHttpRequest();
        xhrApproveDate.open('POST', 'approve_date.php', true);
        xhrApproveDate.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhrApproveDate.onload = function() {
            if (xhrApproveDate.status === 200) {
                console.log('ApproveDate updated successfully');
                // After updating ApproveDate, update ApproverUserID
                updateApprover(formId, '1'); // Update ApproverUserID for user 1
                // Update StatusID in incidentform table
                updateStatus(formId, 'Ongoing Case'); // Update status to 'Ongoing Case'
            } else {
                console.error('Error updating ApproveDate');
            }
        };
        xhrApproveDate.onerror = function() {
            console.error('Error updating ApproveDate');
        };
        xhrApproveDate.send('form_id=' + formId);
    }
}

/*
function updateStatus(formId, status) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_status.php', true); // Assuming 'update_status.php' is the correct endpoint
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Status updated successfully');
        } else {
            console.error('Error updating status');
        }
    };
    xhr.onerror = function() {
        console.error('Error updating status');
    };
    xhr.send('form_id=' + formId + '&status=' + encodeURIComponent(status));
}*/


function updateApprover(formId, userId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_approver.php', true); // Assuming 'update_approver.php' is the correct endpoint
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Approver updated successfully');
        } else {
            console.error('Error updating approver');
        }
    };
    xhr.onerror = function() {
        console.error('Error updating approver');
    };
    xhr.send('form_id=' + formId + '&user_id=' + userId);
}


// Inside approveForm function

</script>

<script>
    function rejectForm(formId) {
    // Prompt user for rejection reason
    var rejectionReason = prompt("Please provide a reason for rejection:");

    if (rejectionReason) {
        // If the user provided a reason, update the rejection reason in the database
        updateRejectionReason(formId, rejectionReason);
    } else {
        console.log("User canceled rejection.");
    }
}

function updateRejectionReason(formId, rejectionReason) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'rejection_reason.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Rejection reason updated successfully');
        } else {
            console.error('Error updating rejection reason');
        }
    };
    xhr.onerror = function() {
        console.error('Error updating rejection reason');
    };
    xhr.send('form_id=' + formId + '&rejection_reason=' + encodeURIComponent(rejectionReason));
}
</script>

</body>
</html>