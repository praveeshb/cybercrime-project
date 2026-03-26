<?php
session_start();
include "../config.php";

if($_SESSION['role']!="police"){
    header("Location: ../index.php");
}

$complaints=mysqli_query($conn,"SELECT * FROM complaints");
?>

<!DOCTYPE html>
<html>
<head>
<title>Police Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; }
.container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.nav { background: #343a40; padding: 10px; text-align: right; }
.nav a { color: white; text-decoration: none; margin: 0 10px; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
th { background: #f8f9fa; }
.btn { padding: 8px 15px; background: #ffc107; color: black; text-decoration: none; border-radius: 4px; }
.btn:hover { background: #e0a800; }
</style>
</head>

<body>

<div class="nav">
<a href="../logout.php">Logout</a>
</div>

<div class="container">

<h2>Police Dashboard</h2>

<table>
<tr>
<th>ID</th>
<th>Tracking ID</th>
<th>Description</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($c=mysqli_fetch_assoc($complaints)){ ?>
<tr>
<td><?php echo $c['id']; ?></td>
<td><?php echo $c['tracking_id']; ?></td>
<td><?php echo substr($c['description'],0,50).'...'; ?></td>
<td><?php echo $c['status']; ?></td>
<td>
<a href="update.php?id=<?php echo $c['id']; ?>" class="btn">Investigate</a>
</td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>
