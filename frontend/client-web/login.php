<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $host = 'localhost';
    $dbusername = 'root';
    $dbpassword = '';
    $database = 'studentrecord';

    $conn = new mysqli($host, $dbusername, $dbpassword, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";

    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        header("Location: http://localhost/OSA_DS/backend/adminlogin.html");
        exit();
    } else {
        echo "<script>alert('Login failed. Please check your username and password.'); window.location.href = 'login.html';</script>";
        exit();
    }

    $conn->close();
}

?>
