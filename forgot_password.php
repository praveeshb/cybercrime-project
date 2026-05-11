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
    } elseif(strlen($aadhaar) !== 12 || !ctype_digit($aadhaar)){
        $message = "Aadhaar must be exactly 12 digits (numbers only—no letters, spaces, or symbols).";
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
    <input type="text" id="aadhaar" name="aadhaar" placeholder="Aadhaar (12 digits)" maxlength="12" minlength="12" inputmode="numeric" autocomplete="off" pattern="[0-9]{12}" title="Exactly 12 digits, numbers only" required>
    <input type="text" name="phone" placeholder="Phone Number (10-15 digits)" pattern="\d{10,15}" title="Enter valid phone number" required>
    <div class="password-wrap">
    <input type="password" name="new_password" placeholder="New Password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).+" title="Use uppercase, lowercase, number, and special symbol (!@#$%^&*)" required autocomplete="new-password">
    <button type="button" class="password-toggle" aria-label="Show password">
    <span class="icon-eye-open" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
    <span class="icon-eye-off" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></span>
    </button>
    </div>
    <div class="password-wrap">
    <input type="password" name="confirm_password" placeholder="Confirm New Password" required autocomplete="new-password">
    <button type="button" class="password-toggle" aria-label="Show password">
    <span class="icon-eye-open" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
    <span class="icon-eye-off" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></span>
    </button>
    </div>
    <button name="reset_password">Reset Password</button>
</form>

<a href="index.php">Back to Login</a>
</div>

<script>
(function () {
    var aadhaarInput = document.getElementById('aadhaar');
    if (!aadhaarInput) return;
    aadhaarInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 12);
    });
    aadhaarInput.addEventListener('paste', function (e) {
        e.preventDefault();
        var t = (e.clipboardData || window.clipboardData).getData('text') || '';
        this.value = t.replace(/\D/g, '').slice(0, 12);
    });
})();

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
