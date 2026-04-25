<?php
session_start();
include "../config.php";

if($_SESSION['role']!="police"){
    header("Location: ../index.php");
}

$complaints=mysqli_query($conn,"SELECT complaints.*, users.name AS user_name, users.aadhaar, users.phone, users.address FROM complaints LEFT JOIN users ON users.id=complaints.user_id");
?>

<!DOCTYPE html>
<html>
<head>
<title>Police Dashboard</title>
<style>
* { box-sizing: border-box; }
body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; }
.container { max-width: 1250px; margin: 24px auto; padding: 24px; background: white; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
.nav { background: #1f2937; padding: 12px 18px; text-align: right; }
.nav a { color: white; text-decoration: none; margin-left: 14px; font-weight: 600; }
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; margin: 16px 0; }
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; vertical-align: middle; }
th { background: #f8f9fa; }
.action-form { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin: 0; }
.action-form select { padding: 7px 9px; border: 1px solid #ccc; border-radius: 4px; }
.btn { padding: 8px 15px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; }
.btn:hover { background: #0b5ed7; }
</style>
</head>

<body>

<div class="nav">
<a href="../logout.php">Logout</a>
</div>

<div class="container">

<h2>Police Dashboard</h2>

<div class="table-wrap">
<table>
<tr>
<th>ID</th>
<th>Tracking ID</th>
<th>User</th>
<th>Aadhaar</th>
<th>Phone</th>
<th>Address</th>
<th>Description</th>
<th>Evidence</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($c=mysqli_fetch_assoc($complaints)){ ?>
<tr>
<td><?php echo $c['id']; ?></td>
<td><?php echo htmlspecialchars($c['tracking_id']); ?></td>
<td><?php echo htmlspecialchars($c['user_name'] ?? 'Unknown'); ?></td>
<td><?php echo htmlspecialchars($c['aadhaar'] ?? ''); ?></td>
<td><?php echo htmlspecialchars($c['phone'] ?? ''); ?></td>
<td><?php echo htmlspecialchars($c['address'] ?? ''); ?></td>
<td><?php echo htmlspecialchars(substr($c['description'],0,50)).'...'; ?></td>
<td>
<?php if(!empty($c['evidence'])){ ?>
<a href="../uploads/<?php echo urlencode($c['evidence']); ?>" target="_blank">View File</a>
<?php } else { ?>
<span style="color:#6c757d;">No file</span>
<?php } ?>
</td>
<td><?php echo htmlspecialchars($c['status']); ?></td>
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

</div>

</body>
</html>
