<?php
include "config.php";
$message = "";
$messageClass = "";

if(isset($_POST['reset_password'])){
    $email = trim($_POST['email']);
    $aadhaar = trim($_POST['aadhaar']);
    $phone = trim($_POST['phone']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).+$/';

    if($email === "" || $aadhaar === "" || $phone === "" || $newPassword === "" || $confirmPassword === ""){
        $message = "All fields are required.";
        $messageClass = "error";
    } elseif(!preg_match('/^\d{12}$/', $aadhaar)){
        $message = "Aadhaar must be exactly 12 digits.";
        $messageClass = "error";
    } elseif(!preg_match('/^\d{10,15}$/', $phone)){
        $message = "Phone number must be 10 to 15 digits.";
        $messageClass = "error";
    } elseif($newPassword !== $confirmPassword){
        $message = "New password and confirm password do not match.";
        $messageClass = "error";
    } elseif(!preg_match($passwordPattern, $newPassword)){
        $message = "Password must include uppercase, lowercase, number, and special symbol (!@#$%^&*).";
        $messageClass = "error";
    } else {
        $escapedEmail = mysqli_real_escape_string($conn, $email);
        $escapedAadhaar = mysqli_real_escape_string($conn, $aadhaar);
        $escapedPhone = mysqli_real_escape_string($conn, $phone);
        $escapedPassword = mysqli_real_escape_string($conn, $newPassword);

        $userQuery = mysqli_query($conn, "SELECT id FROM users WHERE email='$escapedEmail' AND aadhaar='$escapedAadhaar' AND phone='$escapedPhone' LIMIT 1");

        if($userQuery && mysqli_num_rows($userQuery) > 0){
            $user = mysqli_fetch_assoc($userQuery);
            $userId = (int)$user['id'];
            $updated = mysqli_query($conn, "UPDATE users SET password='$escapedPassword' WHERE id=$userId");

            if($updated){
                $message = "Password reset successful. You can login now.";
                $messageClass = "success";
            } else {
                $message = "Password reset failed: " . mysqli_error($conn);
                $messageClass = "error";
            }
        } else {
            $message = "User verification failed. Check email, Aadhaar and phone.";
            $messageClass = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
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
    background-image: linear-gradient(rgba(6, 20, 40, 0.6), rgba(6, 20, 40, 0.6)), url('assets/login-bg.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.container {
    width: 100%;
    max-width: 560px;
    padding: 30px;
    background: rgba(255, 255, 255, 0.96);
    border-radius: 10px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
}
h2 { color: #1d1d1d; margin-bottom: 10px; }
.subtitle { margin: 0 0 16px; color: #4b5563; }
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}
button {
    width: 100%;
    padding: 12px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 4px;
}
button:hover { background: #0056b3; }
a {
    display: block;
    text-align: center;
    margin: 14px 0 0;
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
}
a:hover { text-decoration: underline; }
.msg {
    margin: 0 0 12px;
    padding: 12px 14px;
    font-size: 14px;
    border-radius: 8px;
}
.msg.error { color: #b42318; background: #fef3f2; }
.msg.success { color: #166534; background: #f0fdf4; }
</style>
</head>

<body>

<div class="container">
<h2>Forgot Password</h2>
<p class="subtitle">Verify your identity and set a new password.</p>

<?php if($message !== ""): ?>
<p class="msg <?php echo $messageClass; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Registered Email" required>
    <input type="text" name="aadhaar" placeholder="Aadhaar (12 digits)" pattern="\d{12}" title="Enter 12 digit Aadhaar number" required>
    <input type="text" name="phone" placeholder="Phone Number (10-15 digits)" pattern="\d{10,15}" title="Enter valid phone number" required>
    <input type="password" name="new_password" placeholder="New Password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).+" title="Use uppercase, lowercase, number, and special symbol (!@#$%^&*)" required>
    <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
    <button name="reset_password">Reset Password</button>
</form>

<a href="index.php">Back to Login</a>
</div>

</body>
</html>
