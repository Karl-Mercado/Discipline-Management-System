<?php
// Initialize $search_result variable
$search_result = null;

// Check if the form has been submitted
if(isset($_POST['search'])) {
    // Retrieve the search value
    $valueToSearch = $_POST['valueToSearch'];
    
    // Construct the SQL query
    $query = "SELECT * FROM `noticeform` WHERE CONCAT(`id`, `ViolatorName`, `Department`, `Contact`, `CourseYear`, `StudentNum`, `Date`, `ExplanationDetails`) LIKE '%$valueToSearch%'";
    
    // Execute the query to filter the results
    $search_result = filterTable($query);
} else {
    // If the form is not submitted, fetch all data
    $query = "SELECT * FROM `noticeform`";
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
    <title>Notice Page</title>
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
        <?php if ($search_result !== null && mysqli_num_rows($search_result) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>ViolatorName</th>
                        <th>Department</th>
                        <th>Contact</th>
                        <th>CourseYear</th>
                        <th>StudentNum</th>
                        <th>Date</th>
                        <th>ExplanationDetails</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = mysqli_fetch_array($search_result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['ViolatorName']; ?></td>
                            <td><?php echo $row['Department']; ?></td>
                            <td><?php echo $row['Contact']; ?></td>
                            <td><?php echo $row['CourseYear']; ?></td>
                            <td><?php echo $row['StudentNum']; ?></td>
                            <td><?php echo $row['Date']; ?></td>
                            <td><?php echo $row['ExplanationDetails']; ?></td>
 
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($search_result !== null && mysqli_num_rows($search_result) == 0): ?>
            <p>No results found</p>
        <?php endif; ?>
    </div>

</body>
</html>
