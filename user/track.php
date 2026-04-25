<?php
include "../config.php";
$track_message = "";

if(isset($_POST['track'])){

    $id = trim($_POST['tracking']);

    $q = mysqli_query($conn,"SELECT * FROM complaints WHERE tracking_id='$id'");
    $c = $q ? mysqli_fetch_assoc($q) : null;
    if($c){
        $track_message = "Tracking ID: ".$c['tracking_id']." | Status: ".$c['status'];
    } else {
        $track_message = "No complaint found for this Tracking ID.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Track Complaint</title>
<style>
* { box-sizing: border-box; }
body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; }
.container { max-width: 540px; margin: 36px auto; padding: 24px; background: white; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 4px; }
button:hover { background: #0056b3; }
.result { padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
a { color: #0d6efd; text-decoration: none; font-weight: 600; }
a:hover { text-decoration: underline; }
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
<?php echo $track_message; ?>
</div>
<?php } ?>

<p><a href="dashboard.php">Back to Dashboard</a></p>

</div>

</body>
</html>
