<?php
include "../config.php";

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : "";
$allowedStatuses = array("Pending", "Investigating", "Completed");

if($id > 0 && in_array($status, $allowedStatuses, true)){
    $safeStatus = mysqli_real_escape_string($conn, $status);
    mysqli_query($conn,"UPDATE complaints SET status='$safeStatus' WHERE id='$id'");
}

header("Location: dashboard.php");
?>
