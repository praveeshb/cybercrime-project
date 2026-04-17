<?php
session_start();
include "config.php";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Execute query with error checking
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
        exit();
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
body {
    font-family: Arial, sans-serif;
    margin: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    box-sizing: border-box;
    background-image: linear-gradient(rgba(6, 20, 40, 0.55), rgba(6, 20, 40, 0.55)), url('assets/login-bg.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.container {
    width: 100%;
    max-width: 500px;
    padding: 30px;
    background: rgba(255, 255, 255, 0.96);
    border-radius: 10px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
}
h2 { color: #1d1d1d; margin-bottom: 20px; }
input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
button:hover { background: #0056b3; }
a { display: block; text-align: center; margin: 15px 0; color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
.credit {
    position: fixed;
    right: 24px;
    bottom: 24px;
    background: rgba(8, 18, 36, 0.75);
    color: #ffffff;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 13px;
    letter-spacing: 0.4px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}
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

<div class="credit">Created by VIGNESH, ILMAN, PRAVEESH</div>

</body>
</html>
