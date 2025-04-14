<?php
$host = "login-mysql";  // Dit is de containernaam van de mysql-service
$user = "user";
$pass = "userpass";
$dbname = "login_project";

// Maak connectie
$conn = new mysqli($host, $user, $pass, $dbname);

// Check de connectie
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
