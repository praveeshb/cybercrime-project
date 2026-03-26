<?php
include "../config.php";

$id=$_GET['id'];

mysqli_query($conn,"UPDATE complaints SET status='Investigating' WHERE id='$id'");

header("Location: dashboard.php");
?>
