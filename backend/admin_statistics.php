<?php
// Connect to your database
$connect = mysqli_connect("localhost", "root", "", "studentrecord");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Query to get total complaints under review
$queryUnderReview = "SELECT COUNT(*) AS totalUnderReview FROM `incidentform` WHERE Status = 'Under review'";
$resultUnderReview = mysqli_query($connect, $queryUnderReview);
$rowUnderReview = mysqli_fetch_assoc($resultUnderReview);
$totalUnderReview = $rowUnderReview['totalUnderReview'];

// Query to get total ongoing complaints
$queryOngoing = "SELECT COUNT(*) AS totalOngoing FROM `incidentform` WHERE Status = 'Ongoing'";
$resultOngoing = mysqli_query($connect, $queryOngoing);
$rowOngoing = mysqli_fetch_assoc($resultOngoing);
$totalOngoing = $rowOngoing['totalOngoing'];

// Query to get total closed complaints
$queryClosed = "SELECT COUNT(*) AS totalClosed FROM `incidentform` WHERE Status = 'Closed'";
$resultClosed = mysqli_query($connect, $queryClosed);
$rowClosed = mysqli_fetch_assoc($resultClosed);
$totalClosed = $rowClosed['totalClosed'];

// Query to get total minor complaints
$queryMinor = "SELECT COUNT(*) AS totalMinor FROM `incidentform` WHERE Classification = 'minor'";
$resultMinor = mysqli_query($connect, $queryMinor);
$rowMinor = mysqli_fetch_assoc($resultMinor);
$totalMinor = $rowMinor['totalMinor'];

// Query to get total major complaints
$queryMajor = "SELECT COUNT(*) AS totalMajor FROM `incidentform` WHERE Classification = 'major'";
$resultMajor = mysqli_query($connect, $queryMajor);
$rowMajor = mysqli_fetch_assoc($resultMajor);
$totalMajor = $rowMajor['totalMajor'];

// Query to get total complaints in each department
$queryTotalByDepartment = "SELECT Department, COUNT(*) AS totalComplaints FROM `incidentform` GROUP BY Department";
$resultTotalByDepartment = mysqli_query($connect, $queryTotalByDepartment);

// Close the connection
mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Include Font Awesome CSS file -->
    <link rel="stylesheet" href="admin_statistics.css">
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
    
    <!-- Main content -->
<!-- Main content -->
<div class="container my-5">
        <div class="row">
            <!-- Complaints Status -->
            <div class="col-md-6">
                <h3>Complaints Status</h3>
                <p>Under Review: <?php echo $totalUnderReview; ?></p>
                <p>Ongoing: <?php echo $totalOngoing; ?></p>
                <p>Closed: <?php echo $totalClosed; ?></p>
            </div>
            <!-- Total Violations -->
            <div class="col-md-6">
                <h3>Total Violations</h3>
                <ul>
                    <p>Minor: <?php echo $totalMinor; ?></p> <!-- Display total minor complaints -->
                    <p>Major: <?php echo $totalMajor; ?></p> 
                </ul>
            </div>
            <!-- Total Complaints by Department -->
            <div class="col-md-6">
                <h3>Total Complaints by Department</h3>
                <ul>
                    <?php while($row = mysqli_fetch_assoc($resultTotalByDepartment)): ?>
                        <li><?php echo $row['Department'] . ": " . $row['totalComplaints']; ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <!-- Canvas elements for charts -->
            <div class="col-md-12">
                <canvas id="complaintsLineChart" width="800" height="400"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="complaintsPieChart" width="400" height="400"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="complaintsBarChart" width="400" height="400"></canvas>
            </div>
 
        </div>
    </div>

    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- JavaScript to render charts -->
    <script>
        // Data for pie chart

              // Data for line chart
              var lineData = {
            labels: [<?php 
                mysqli_data_seek($resultTotalByDepartment, 0); // Reset pointer
                while($row = mysqli_fetch_assoc($resultTotalByDepartment)) { 
                    echo "'" . $row['Department'] . "', ";
                } 
                ?>],
            datasets: [{
                label: 'Total Complaints by Department',
                data: [<?php 
                    mysqli_data_seek($resultTotalByDepartment, 0); // Reset pointer
                    while($row = mysqli_fetch_assoc($resultTotalByDepartment)) { 
                        echo $row['totalComplaints'] . ', ';
                    } 
                ?>],
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        var pieData = {
            labels: ["Under Review", "Ongoing", "Closed"],
            datasets: [{
                data: [<?php echo $totalUnderReview; ?>, <?php echo $totalOngoing; ?>, <?php echo $totalClosed; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Data for bar chart
        var barData = {
            labels: ["Minor", "Major"],
            datasets: [{
                label: 'Total Violations',
                data: [<?php echo $totalMinor; ?>, <?php echo $totalMajor; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        };

                // Render line chart
// Render line chart
var lineCtx = document.getElementById('complaintsLineChart').getContext('2d');
var lineChart = new Chart(lineCtx, {
    type: 'line',
    data: lineData,
    options: {
        layout: {
            padding: {
                left: 20,
                right: 20,
                top: 20,
                bottom: 20
            }
        },
        scales: {
            y: {
                ticks: {
                    font: {
                        size: 20 // Adjust font size for y-axis ticks
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 20 // Adjust font size for x-axis ticks
                    }
                }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Total Complaints by Department',
                font: {
                    size: 20 // Adjust font size for chart title
                }
            }
        }
    }
});

// Render pie chart
var pieCtx = document.getElementById('complaintsPieChart').getContext('2d');
var pieChart = new Chart(pieCtx, {
    type: 'pie',
    data: pieData,
    options: {
        layout: {
            padding: {
                left: 20,
                right: 20,
                top: 20,
                bottom: 20
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Complaints Status',
                font: {
                    size: 20// Adjust font size for chart title
                }
            },
            legend: {
                labels: {
                    font: {
                        size: 20 // Adjust font size for legend labels
                    }
                }
            }
        }
    }
});

// Render bar chart
var barCtx = document.getElementById('complaintsBarChart').getContext('2d');
var barChart = new Chart(barCtx, {
    type: 'bar',
    data: barData,
    options: {
        layout: {
            padding: {
                left: 20,
                right: 30,
                top: 20,
                bottom: 20
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Total Violations',
                font: {
                    size: 20 // Adjust font size for chart title
                }
            },
            legend: {
                labels: {
                    font: {
                        size: 20 // Adjust font size for legend labels
                    }
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    font: {
                        size: 20 // Adjust font size for y-axis ticks
                    }
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 20 // Adjust font size for x-axis ticks
                    }
                }
            }
        }
    }
});


    </script>
</body>
</html>