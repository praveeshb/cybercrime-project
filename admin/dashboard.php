<?php
session_start();
include "../config.php";

if($_SESSION['role']!="admin"){
    header("Location: ../index.php");
}

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$message = "";
$messageClass = "";

if(isset($_POST['update_user'])){
    $userId = (int)$_POST['user_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    if($name === "" || $email === "" || $role === ""){
        $message = "All user fields are required for update.";
        $messageClass = "error";
    } else {
        $escapedName = mysqli_real_escape_string($conn, $name);
        $escapedEmail = mysqli_real_escape_string($conn, $email);
        $escapedRole = mysqli_real_escape_string($conn, $role);
        $query = "UPDATE users SET name='$escapedName', email='$escapedEmail', role='$escapedRole' WHERE id=$userId";

        if(mysqli_query($conn, $query)){
            $message = "User updated successfully.";
            $messageClass = "success";
        } else {
            $message = "Update failed: " . mysqli_error($conn);
            $messageClass = "error";
        }
    }
}

if(isset($_POST['delete_user'])){
    $userId = (int)$_POST['user_id'];

    if($userId === (int)$_SESSION['user_id']){
        $message = "You cannot delete your own admin account.";
        $messageClass = "error";
    } else {
        if(mysqli_query($conn, "DELETE FROM users WHERE id=$userId")){
            $message = "User deleted successfully.";
            $messageClass = "success";
        } else {
            $message = "Delete failed: " . mysqli_error($conn);
            $messageClass = "error";
        }
    }
}

$users=mysqli_query($conn,"SELECT * FROM users");
$complaints=mysqli_query($conn,"SELECT * FROM complaints");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<style>
* { box-sizing: border-box; }
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background: linear-gradient(130deg, #e9f2ff, #f5f7fb);
    color: #1f2937;
}
.nav {
    background: linear-gradient(90deg, #0f172a, #1e293b);
    padding: 16px 24px;
    text-align: right;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
}
.nav a {
    color: #ffffff;
    text-decoration: none;
    font-weight: bold;
    padding: 8px 14px;
    border: 1px solid rgba(255, 255, 255, 0.35);
    border-radius: 20px;
}
.container {
    max-width: 1260px;
    margin: 26px auto;
    padding: 28px;
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 12px 32px rgba(31, 41, 55, 0.12);
}
h2 {
    margin: 0 0 18px;
    font-size: 34px;
    color: #0f172a;
}
h3 {
    color: #1d4ed8;
    margin: 8px 0 14px;
    font-size: 23px;
}
.section {
    background: #f8fbff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 18px 8px;
    margin-bottom: 18px;
}
.table-wrap {
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin: 12px 0 16px;
}
th, td {
    padding: 13px 12px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: middle;
}
th {
    background: #eef4ff;
    color: #334155;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.msg {
    margin: 12px 0 18px;
    padding: 12px 14px;
    border-radius: 8px;
    font-size: 14px;
}
.msg.success { background: #eafaf1; color: #166534; border: 1px solid #bbf7d0; }
.msg.error { background: #fdecec; color: #b42318; border: 1px solid #fecaca; }
.actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
.actions input, .actions select {
    padding: 8px 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    min-width: 160px;
}
.btn {
    border: none;
    color: #fff;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}
.btn-update { background: linear-gradient(90deg, #2563eb, #3b82f6); }
.btn-delete { background: linear-gradient(90deg, #dc2626, #ef4444); }
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}
.badge.admin { background: #fee2e2; color: #991b1b; }
.badge.police { background: #dbeafe; color: #1e3a8a; }
.badge.user { background: #dcfce7; color: #166534; }
</style>
</head>

<body>

<div class="nav">
<a href="../logout.php">Logout</a>
</div>

<div class="container">

<h2>Admin Dashboard</h2>
<?php if($message !== ""){ ?>
<div class="msg <?php echo $messageClass; ?>"><?php echo $message; ?></div>
<?php } ?>

<div class="section">
<h3>Users</h3>
<div class="table-wrap">
<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Actions</th>
</tr>

<?php while($u=mysqli_fetch_assoc($users)){ ?>
<tr>
<td><?php echo $u['id']; ?></td>
<td><?php echo htmlspecialchars($u['name']); ?></td>
<td><?php echo htmlspecialchars($u['email']); ?></td>
<td><span class="badge <?php echo $u['role']; ?>"><?php echo $u['role']; ?></span></td>
<td>
    <div class="actions">
        <form method="POST">
            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
            <input type="text" name="name" value="<?php echo htmlspecialchars($u['name']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" required>
            <select name="role" required>
                <option value="user" <?php if($u['role']=="user") echo "selected"; ?>>user</option>
                <option value="police" <?php if($u['role']=="police") echo "selected"; ?>>police</option>
                <option value="admin" <?php if($u['role']=="admin") echo "selected"; ?>>admin</option>
            </select>
            <button type="submit" class="btn btn-update" name="update_user">Update</button>
        </form>

        <form method="POST" onsubmit="return confirm('Delete this user?');">
            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
            <button type="submit" class="btn btn-delete" name="delete_user">Delete</button>
        </form>
    </div>
</td>
</tr>
<?php } ?>

</table>
</div>
</div>

<div class="section">
<h3>Complaints</h3>
<div class="table-wrap">
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
</div>

</div>

</body>
</html>
