<?php
session_start();
include "../config.php";

if($_SESSION['role']!="admin"){
    header("Location: ../index.php");
}

$users=mysqli_query($conn,"SELECT * FROM users");
$complaints=mysqli_query($conn,"SELECT * FROM complaints");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; }
.container { max-width: 1200px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.nav { background: #343a40; padding: 10px; text-align: right; }
.nav a { color: white; text-decoration: none; margin: 0 10px; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
th { background: #f8f9fa; }
h3 { color: #495057; margin: 20px 0 10px 0; }
</style>
</head>

<body>

<div class="nav">
<a href="../logout.php">Logout</a>
</div>

<div class="container">

<h2>Admin Dashboard</h2>

<h3>Users</h3>
<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
</tr>

<?php while($u=mysqli_fetch_assoc($users)){ ?>
<tr>
<td><?php echo $u['id']; ?></td>
<td><?php echo $u['name']; ?></td>
<td><?php echo $u['email']; ?></td>
<td><?php echo $u['role']; ?></td>
</tr>
<?php } ?>

</table>

<h3>Complaints</h3>
<table>
<tr>
<th>ID</th>
<th>Tracking ID</th>
<th>User ID</th>
<th>Description</th>
<th>Status</th>
</tr>

<?php while($c=mysqli_fetch_assoc($complaints)){ ?>
<tr>
<td><?php echo $c['id']; ?></td>
<td><?php echo $c['tracking_id']; ?></td>
<td><?php echo $c['user_id']; ?></td>
<td><?php echo substr($c['description'],0,50).'...'; ?></td>
<td><?php echo $c['status']; ?></td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>
