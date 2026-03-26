<?php
session_start();
if($_SESSION['role']!="user"){
    header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; }
.container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.nav { background: #343a40; padding: 10px; text-align: right; }
.nav a { color: white; text-decoration: none; margin: 0 10px; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
th { background: #f8f9fa; }
.btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
</style>
</head>

<body>

<div class="nav">
<a href="complaint.php">Submit Complaint</a>
<a href="track.php">Track Complaint</a>
<a href="../logout.php">Logout</a>
</div>

<div class="container">

<h2>User Dashboard</h2>

<p>Welcome! You can submit new complaints or track existing ones.</p>

<a href="complaint.php" class="btn">Submit New Complaint</a>

</div>

</body>
</html>
