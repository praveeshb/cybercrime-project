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
* { box-sizing: border-box; }
body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; }
.container { max-width: 1100px; margin: 24px auto; padding: 24px; background: white; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
.nav { background: #1f2937; padding: 12px 18px; text-align: right; }
.nav a { color: white; text-decoration: none; margin-left: 14px; font-weight: 600; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
th { background: #f8f9fa; }
.btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-top: 6px; }
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
