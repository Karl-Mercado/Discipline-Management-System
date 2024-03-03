<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="complaints.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .complaint {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            width: 70%;
            float: right;
        }
        .delete-btn {
            background-color: #ff6666;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="user-icon">
            <i class="fas fa-user-circle"></i>
        </div>
        <h1>Admin</h1>
    
        <nav class="navigation">
            <ul>
                <li><i class="fas fa-chart-bar"></i><a href="#">Statistics</a></li>
                <li><i class="fas fa-exclamation-triangle"></i><a href="http://localhost/OSA_DS/frontend/client-web/admin_create.php">Input Violations</a></li>
                <li class="active"><i class="fas fa-clipboard-list"></i><a href="http://localhost/OSA_DS/frontend/client-web/complaints.php">Complaints</a></li>
                <li><i class="fas fa-sign-out-alt"></i><a href="home.html">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="complaints">
        <?php
        // Database connection
        $host = 'localhost';
        $username = 'root'; 
        $password = ''; 
        $database = 'studentrecord';

        // Create connection
        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Select data from the database
        $query = "SELECT * FROM incidentform";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<div class='complaint'>";
                echo "<p>Complaint Last Name: " . $row["ComplaintLastName"]. "</p>";
                echo "<p>Complaint First Name: " . $row["ComplaintFirstName"]. "</p>";
                echo "<p>Student Number: " . $row["StudentNum"]. "</p>";
                echo "<p>Course Year: " . $row["CourseYear"]. "</p>";
                echo "<p>Complaint Date: " . $row["ComplainDate"]. "</p>";
                echo "<p>Subject of Complaint: " . $row["SubjectComplaint"]. "</p>";
                echo "<p>Complaint Details: " . $row["ComplainDetails"]. "</p>";
                echo "<button class='delete-btn' data-id='" . $row["id"] . "'>Delete</button>";
                echo "</div>";
            }
        } else {
            echo "0 results";
        }

        // Close the connection
        $conn->close();
        ?>
    </div>

    <script>
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // You can send an AJAX request to delete the record with the id
                // For demonstration, let's assume an alert
                alert("Delete record with ID: " + id);
            });
        });
    </script>

</body>
</html>
