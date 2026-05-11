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
.password-wrap { position: relative; width: 100%; margin: 10px 0; }
.password-wrap input { margin: 0; padding-right: 44px; }
.password-wrap .password-toggle {
    position: absolute;
    right: 8px;
    top: 50%;
    left: auto;
    transform: translateY(-50%);
    width: auto;
    min-width: 0;
    max-width: none;
    margin: 0;
    padding: 6px;
    border: none;
    background: transparent;
    cursor: pointer;
    color: #64748b;
    line-height: 0;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.password-wrap .password-toggle:hover { color: #1d1d1d; background: rgba(0,0,0,0.05); }
.password-wrap .password-toggle:focus { outline: 2px solid #007bff; outline-offset: 2px; }
.password-wrap .password-toggle .icon-eye-off { display: none; }
button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
button:hover { background: #0056b3; }
a { display: block; text-align: center; margin: 0; color: #007bff; text-decoration: none; }
a:hover { text-decoration: underline; }
.links { margin-top: 14px; display: grid; gap: 8px; }
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

<div class="password-wrap">
<input type="password" id="loginPassword" name="password" placeholder="Password" required autocomplete="current-password">
<button type="button" class="password-toggle" aria-label="Show password">
<span class="icon-eye-open" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
<span class="icon-eye-off" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></span>
</button>
</div>

<button name="login">Login</button>

</form>

<div class="links">
<a href="forgot_password.php">Forgot Password?</a>
<a href="register.php">Register</a>
</div>

</div>

<div class="credit">Created by VIGNESH, ILMAN, PRAVEESH</div>

<script>
(function () {
    function bindPasswordToggle(wrap) {
        var input = wrap.querySelector('input');
        var btn = wrap.querySelector('.password-toggle');
        if (!input || !btn) return;
        var openIcon = btn.querySelector('.icon-eye-open');
        var offIcon = btn.querySelector('.icon-eye-off');
        btn.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            if (openIcon) openIcon.style.display = show ? 'none' : '';
            if (offIcon) offIcon.style.display = show ? '' : 'none';
        });
    }
    document.querySelectorAll('.password-wrap').forEach(bindPasswordToggle);
})();
</script>

</body>
</html>
