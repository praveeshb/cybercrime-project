<?php
include "config.php";

if(isset($_POST['register'])){

    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];

    mysqli_query($conn,"INSERT INTO users(name,email,password,role) VALUES('$name','$email','$password','user')");

    echo "Registered Successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; }
.container { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
button:hover { background: #218838; }
a { display: block; text-align: center; margin: 10px 0; color: #007bff; }
</style>
</head>

<body>

<div class="container">

<h2>Register</h2>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button name="register">Register</button>

</form>

<a href="index.php">Back to Login</a>

</div>

</body>
</html>
