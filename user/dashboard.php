<?php
session_start();
include "../config.php";
if($_SESSION['role']!="user"){
    header("Location: ../index.php");
}

$user_id = $_SESSION['user_id'];
$my_complaints = mysqli_query($conn, "SELECT tracking_id, status, description FROM complaints WHERE user_id='$user_id' ORDER BY id DESC");
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
.muted { color: #6c757d; }
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

<h3 style="margin-top: 30px;">My Complaints</h3>
<table>
<tr>
<th>Tracking ID</th>
<th>Status</th>
<th>Description</th>
</tr>

<?php if($my_complaints && mysqli_num_rows($my_complaints) > 0){ ?>
<?php while($row = mysqli_fetch_assoc($my_complaints)){ ?>
<tr>
<td><strong><?php echo $row['tracking_id']; ?></strong></td>
<td><?php echo $row['status']; ?></td>
<td><?php echo substr($row['description'],0,80).'...'; ?></td>
</tr>
<?php } ?>
<?php } else { ?>
<tr>
<td colspan="3" class="muted">No complaints submitted yet.</td>
</tr>
<?php } ?>
</table>

</div>

</body>
</html>
