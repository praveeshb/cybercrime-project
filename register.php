<?php
include "config.php";
$message = "";
$messageClass = "";

if(isset($_POST['register'])){

    $name=trim($_POST['name']);
    $email=trim($_POST['email']);
    $password=$_POST['password'];
    $aadhaar=trim($_POST['aadhaar']);
    $phone=trim($_POST['phone']);
    $address=trim($_POST['address']);

    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).+$/';
    if ($name === "" || $email === "" || $password === "" || $aadhaar === "" || $phone === "" || $address === "") {
        $message = "All fields are mandatory for registration.";
        $messageClass = "error";
    } elseif (!preg_match($passwordPattern, $password)) {
        $message = "Password must include uppercase, lowercase, number, and special symbol (!@#$%^&*).";
        $messageClass = "error";
    } elseif (!preg_match('/^\d{12}$/', $aadhaar)) {
        $message = "Aadhaar must be exactly 12 digits.";
        $messageClass = "error";
    } elseif (!preg_match('/^\d{10,15}$/', $phone)) {
        $message = "Phone number must be 10 to 15 digits.";
        $messageClass = "error";
    } elseif ($address === "") {
        $message = "Address is required.";
        $messageClass = "error";
    } else {
        $escapedName = mysqli_real_escape_string($conn, $name);
        $escapedEmail = mysqli_real_escape_string($conn, $email);
        $escapedPassword = mysqli_real_escape_string($conn, $password);
        $escapedAadhaar = mysqli_real_escape_string($conn, $aadhaar);
        $escapedPhone = mysqli_real_escape_string($conn, $phone);
        $escapedAddress = mysqli_real_escape_string($conn, $address);

        $saved = mysqli_query(
            $conn,
            "INSERT INTO users(name,email,password,role,aadhaar,phone,address) VALUES('$escapedName','$escapedEmail','$escapedPassword','user','$escapedAadhaar','$escapedPhone','$escapedAddress')"
        );

        if($saved){
            $message = "Registered Successfully";
            $messageClass = "success";
        } else {
            $message = "Registration failed: " . mysqli_error($conn);
            $messageClass = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    box-sizing: border-box;
    background-image: linear-gradient(rgba(6, 20, 40, 0.6), rgba(6, 20, 40, 0.6)), url('assets/login-bg.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.container {
    width: 100%;
    max-width: 540px;
    padding: 34px;
    background: rgba(255, 255, 255, 0.97);
    border-radius: 14px;
    box-shadow: 0 10px 35px rgba(0,0,0,0.35);
}
h2 {
    margin: 0 0 8px;
    color: #111827;
    font-size: 36px;
}
.subtitle {
    margin: 0 0 24px;
    color: #5b6472;
    font-size: 15px;
}
input {
    width: 100%;
    padding: 14px 16px;
    margin: 0 0 16px;
    border: 1px solid #d7dce3;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 16px;
    transition: border-color 0.2s, box-shadow 0.2s;
}
input:focus {
    outline: none;
    border-color: #1991ff;
    box-shadow: 0 0 0 3px rgba(25, 145, 255, 0.15);
}
button {
    width: 100%;
    padding: 14px;
    margin-top: 6px;
    background: linear-gradient(90deg, #16a34a, #22c55e);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
}
button:hover { filter: brightness(0.95); }
a {
    display: block;
    text-align: center;
    margin-top: 14px;
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
}
a:hover { text-decoration: underline; }
.hint {
    margin: -4px 0 16px;
    padding: 12px 14px;
    color: #415269;
    font-size: 13px;
    line-height: 1.5;
    background: #eef6ff;
    border-left: 4px solid #1991ff;
    border-radius: 8px;
    box-sizing: border-box;
    display: none;
}
.msg {
    margin: 0 0 16px;
    padding: 12px 14px;
    font-size: 14px;
    border-radius: 8px;
}
.msg.error {
    color: #b42318;
    background: #fef3f2;
}
.msg.success {
    color: #166534;
    background: #f0fdf4;
}
</style>
</head>

<body>

<div class="container">

<h2>Register</h2>
<p class="subtitle">Create your account to access the cyber crime reporting system.</p>

<?php if($message !== ""): ?>
<p class="msg <?php echo $messageClass; ?>"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" id="password" name="password" placeholder="Password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).+" title="Use uppercase, lowercase, number, and special symbol (!@#$%^&*)" required>
<p class="hint" id="passwordHint">Use uppercase, lowercase, numbers, and special symbols (!@#$%^&*).</p>

<input type="text" name="aadhaar" placeholder="Aadhaar (12 digits)" pattern="\d{12}" title="Enter 12 digit Aadhaar number" required>

<input type="text" name="phone" placeholder="Phone Number (10-15 digits)" pattern="\d{10,15}" title="Enter valid phone number" required>

<input type="text" name="address" placeholder="Address" required>

<button name="register">Register</button>

</form>

<a href="index.php">Back to Login</a>

</div>

<script>
const passwordInput = document.getElementById('password');
const passwordHint = document.getElementById('passwordHint');

passwordInput.addEventListener('focus', function () {
    passwordHint.style.display = 'block';
});

passwordInput.addEventListener('blur', function () {
    passwordHint.style.display = 'none';
});

passwordInput.addEventListener('input', function () {
    passwordHint.style.display = 'block';
});
</script>

</body>
</html>
