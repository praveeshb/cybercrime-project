<?php
include "../config.php";

if(isset($_POST['track'])){

    $id=$_POST['tracking'];

    $q=mysqli_query($conn,"SELECT * FROM complaints WHERE tracking_id='$id'");
    $c=mysqli_fetch_assoc($q);

    echo "Status: ".$c['status'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Track Complaint</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; }
.container { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
.result { padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
</style>
</head>

<body>

<div class="container">

<h2>Track Complaint</h2>

<form method="POST">
<input type="text" name="tracking" placeholder="Tracking ID" required>
<button name="track">Track</button>
</form>

<?php if(isset($_POST['track'])){ ?>
<div class="result">
<?php echo "Status: ".$c['status']; ?>
</div>
<?php } ?>

<p><a href="dashboard.php">Back to Dashboard</a></p>

</div>

</body>
</html>
