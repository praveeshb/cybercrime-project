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
.action-form { display: flex; gap: 8px; align-items: center; }
.action-form select { padding: 7px 9px; border: 1px solid #ccc; border-radius: 4px; }
.btn { padding: 8px 15px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer; }
.btn:hover { background: #0b5ed7; }
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
<form method="POST" action="update.php" class="action-form">
    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
    <select name="status" required>
        <option value="Pending" <?php if($c['status']=="Pending") echo "selected"; ?>>Pending</option>
        <option value="Investigating" <?php if($c['status']=="Investigating") echo "selected"; ?>>Investigating</option>
        <option value="Completed" <?php if($c['status']=="Completed") echo "selected"; ?>>Completed</option>
    </select>
    <button type="submit" class="btn">Update</button>
</form>
</td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>
