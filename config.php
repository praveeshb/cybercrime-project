<?php
$conn = mysqli_connect("localhost","root","","cybercrime");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

// Test database connection
if(!mysqli_query($conn, "SELECT 1 FROM users LIMIT 1")){
    die("Database tables not found. Please import database.sql file first.");
}
?>
