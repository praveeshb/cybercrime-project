<?php
session_start();
include "../config.php";

if($_SESSION['role']!="user"){
    header("Location: ../index.php");
}

if(isset($_POST['submit'])){

    $desc=$_POST['description'];
    $user_id=$_SESSION['user_id'];

    $tracking_id=substr(md5(time()),0,8);

    $file=$_FILES['file']['name'];
    if($file){
        move_uploaded_file($_FILES['file']['tmp_name'],"../uploads/".$file);
    } else {
        $file = "";
    }

    mysqli_query($conn,"INSERT INTO complaints(tracking_id,user_id,description,evidence,status) VALUES('$tracking_id','$user_id','$desc','$file','Pending')");

    echo "Complaint Submitted. Tracking ID: ".$tracking_id;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Submit Complaint</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; }
.container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
textarea { width: 100%; height: 120px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin: 10px 0; }
input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin: 10px 0; }
button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
button:hover { background: #218838; }
</style>
</head>

<body>

<div class="container">

<h2>Submit Cyber Crime Complaint</h2>

<form method="POST" enctype="multipart/form-data">

<textarea name="description" placeholder="Describe the cyber crime incident..." required></textarea>

<input type="file" name="file" accept="image/*,application/pdf">

<button name="submit">Submit Complaint</button>

</form>

<p><a href="dashboard.php">Back to Dashboard</a></p>

</div>

</body>
</html>
