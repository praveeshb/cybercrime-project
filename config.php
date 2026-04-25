<?php
$conn = mysqli_connect("localhost", "root", "", "cybercrime");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Test database connection
if (!mysqli_query($conn, "SELECT 1 FROM users LIMIT 1")) {
    die("Database tables not found. Please import database.sql file first.");
}

// Ensure profile fields exist for user registration details.
mysqli_query($conn, "ALTER TABLE users ADD COLUMN IF NOT EXISTS aadhaar VARCHAR(20) DEFAULT ''");
mysqli_query($conn, "ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(20) DEFAULT ''");
mysqli_query($conn, "ALTER TABLE users ADD COLUMN IF NOT EXISTS address TEXT");
?>
