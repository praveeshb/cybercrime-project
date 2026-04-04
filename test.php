<?php
include "config.php";

// Test database connection
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='admin@cybercrime.gov' AND password='admin123'");

if(!$query) {
    die("SQL Error: " . mysqli_error($conn));
}

if(mysqli_num_rows($query) > 0){
    $user = mysqli_fetch_assoc($query);
    echo "SUCCESS: Database connected and working! User role: " . $user['role'];
} else {
    echo "ERROR: No user found - check database import";
}
?>
