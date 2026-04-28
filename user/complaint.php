<?php
session_start();
include "../config.php";
$success_message = "";
$error_message = "";
$desc = "";

if($_SESSION['role']!="user"){
    header("Location: ../index.php");
}

if(isset($_POST['submit'])){

    $desc = isset($_POST['description']) ? trim($_POST['description']) : "";
    $accepted_terms = isset($_POST['accept_terms']) ? 1 : 0;
    $user_id=$_SESSION['user_id'];

    if(!$accepted_terms){
        $error_message = "Please read and accept Terms & Conditions before submitting your complaint.";
    } else {
        $tracking_id=substr(md5(time()),0,8);

        $file=$_FILES['file']['name'];
        if($file){
            if(!is_dir("../uploads")){
                mkdir("../uploads", 0777, true);
            }
            $originalName = basename($file);
            $safeName = preg_replace("/[^A-Za-z0-9._-]/", "_", $originalName);
            $file = time() . "_" . $safeName;
            move_uploaded_file($_FILES['file']['tmp_name'],"../uploads/".$file);
        } else {
            $file = "";
        }

        $saved = mysqli_query($conn,"INSERT INTO complaints(tracking_id,user_id,description,evidence,status) VALUES('$tracking_id','$user_id','$desc','$file','Pending')");
        if($saved){
            $success_message = "Complaint submitted successfully. Your Tracking ID is: ".$tracking_id;
            $desc = "";
        } else {
            $error_message = "Unable to submit complaint right now. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Submit Complaint</title>
<style>
* { box-sizing: border-box; }
body { font-family: Arial, sans-serif; margin: 0; background: #f5f7fb; }
.container { max-width: 800px; margin: 36px auto; padding: 24px; background: white; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
textarea { width: 100%; height: 120px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin: 10px 0; resize: vertical; }
input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin: 10px 0; }
button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 4px; }
button:hover { background: #218838; }
.success { margin: 12px 0; padding: 12px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724; }
.error { margin: 12px 0; padding: 12px; background: #f8d7da; border: 1px solid #f1aeb5; border-radius: 4px; color: #842029; }
.terms-box { margin-top: 12px; border: 1px solid #dbe2ea; border-radius: 8px; background: #f8fafc; padding: 14px; }
.terms-box h3 { margin: 0 0 8px; font-size: 16px; }
.terms-box ul { margin: 0; padding-left: 18px; color: #334155; }
.terms-box li { margin-bottom: 6px; line-height: 1.4; }
.terms-check { display: flex; align-items: flex-start; gap: 8px; margin-top: 12px; color: #1e293b; font-size: 14px; }
.terms-check input { margin-top: 2px; }
a { color: #0d6efd; text-decoration: none; font-weight: 600; }
a:hover { text-decoration: underline; }
</style>
</head>

<body>

<div class="container">

<h2>Submit Cyber Crime Complaint</h2>

<?php if($success_message){ ?>
<div class="success"><?php echo $success_message; ?></div>
<?php } ?>
<?php if($error_message){ ?>
<div class="error"><?php echo $error_message; ?></div>
<?php } ?>

<form method="POST" enctype="multipart/form-data">

<textarea name="description" placeholder="Describe the cyber crime incident..." required><?php echo htmlspecialchars($desc); ?></textarea>

<input type="file" name="file" accept="image/*,application/pdf">

<div class="terms-box">
<h3>Terms & Conditions</h3>
<ul>
<li>Submit only truthful and relevant complaint details. False complaints may lead to legal action.</li>
<li>Do not upload unrelated, harmful, or illegal files as evidence.</li>
<li>Your submitted information may be reviewed by authorized officials for investigation purposes.</li>
<li>By submitting, you confirm the details provided are accurate to the best of your knowledge.</li>
</ul>
</div>

<label class="terms-check">
<input type="checkbox" name="accept_terms" value="1" <?php echo isset($_POST['accept_terms']) ? "checked" : ""; ?> required>
<span>I have read and agree to the Terms & Conditions.</span>
</label>

<button name="submit">Submit Complaint</button>

</form>

<p><a href="dashboard.php">Back to Dashboard</a></p>

</div>

</body>
</html>
