<?php
session_start();
include "config.php";

if(isset($_POST['login'])){

    $email=$_POST['email'];
    $password=$_POST['password'];

    // Simple query with error checking
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    
    if(!$query) {
        die("SQL Error: " . mysqli_error($conn));
    }
    
    if(mysqli_num_rows($query) > 0){
        $user = mysqli_fetch_assoc($query);
        
        $_SESSION['user_id']=$user['id'];
        $_SESSION['role']=$user['role'];

        if($user['role']=="admin"){
            header("Location: admin/dashboard.php");
        }
        elseif($user['role']=="police"){
            header("Location: police/dashboard.php");
        }
        else{
            header("Location: user/dashboard.php");
        }
    }
    else{
        echo "Invalid Login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Cyber Crime Reporting System</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; }
.container { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
button:hover { background: #0056b3; }
a { display: block; text-align: center; margin: 10px 0; color: #007bff; }
</style>
</head>

<body>

<div class="container">

<h2>Cyber Crime Reporting System</h2>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button name="login">Login</button>

</form>

<a href="register.php">Register</a>

</div>

</body>
</html>
