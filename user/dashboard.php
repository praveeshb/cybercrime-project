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
body {
    margin: 0;
    font-family: "Segoe UI", Arial, sans-serif;
    color: #0f172a;
    background-color: rgba(241, 245, 249, 0.92);
    min-height: 100vh;
}
.nav {
    background: rgba(15, 23, 42, 0.88);
    backdrop-filter: blur(6px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    padding: 14px 20px;
    text-align: right;
}
.nav a {
    color: #f8fafc;
    text-decoration: none;
    margin-left: 16px;
    font-weight: 600;
    transition: color 0.2s ease;
}
.nav a:hover { color: #93c5fd; }
.container {
    max-width: 1100px;
    margin: 32px auto;
    padding: 26px;
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 16px;
    box-shadow: 0 18px 35px rgba(15, 23, 42, 0.22);
    backdrop-filter: blur(4px);
}
h2 {
    margin: 0 0 8px;
    font-size: 28px;
}
h3 {
    margin: 28px 0 14px;
    font-size: 20px;
}
p { margin: 0; color: #334155; }
.btn {
    display: inline-block;
    margin-top: 16px;
    padding: 10px 20px;
    border-radius: 999px;
    font-weight: 600;
    color: #ffffff;
    text-decoration: none;
    background: linear-gradient(90deg, #2563eb, #1d4ed8);
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.35);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(37, 99, 235, 0.4);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin: 18px 0 4px;
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
}
th, td {
    padding: 13px 12px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}
th {
    background: #f1f5f9;
    color: #1e293b;
    font-size: 14px;
    letter-spacing: 0.2px;
}
tr:last-child td { border-bottom: none; }
.status-pill {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.25px;
    text-transform: capitalize;
    color: #1e293b;
    background: #dbeafe;
}
.muted { color: #64748b; }
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

<h3>My Complaints</h3>
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
<td><span class="status-pill"><?php echo $row['status']; ?></span></td>
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
