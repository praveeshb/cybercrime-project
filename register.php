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
    } elseif (strlen($aadhaar) !== 12 || !ctype_digit($aadhaar)) {
        $message = "Aadhaar must be exactly 12 digits (numbers only—no letters, spaces, or symbols).";
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
.password-wrap {
    position: relative;
    width: 100%;
    margin: 0 0 16px;
}
.password-wrap input {
    margin-bottom: 0;
    padding-right: 48px;
}
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
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.password-wrap .password-toggle:hover {
    color: #111827;
    background: rgba(15, 23, 42, 0.06);
}
.password-wrap .password-toggle:focus {
    outline: 2px solid #1991ff;
    outline-offset: 2px;
}
.password-wrap .password-toggle .icon-eye-off {
    display: none;
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

<div class="password-wrap">
<input type="password" id="password" name="password" placeholder="Password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).+" title="Use uppercase, lowercase, number, and special symbol (!@#$%^&*)" required autocomplete="new-password">
<button type="button" class="password-toggle" aria-label="Show password">
<span class="icon-eye-open" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
<span class="icon-eye-off" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></span>
</button>
</div>
<p class="hint" id="passwordHint">Use uppercase, lowercase, numbers, and special symbols (!@#$%^&*).</p>

<input type="text" id="aadhaar" name="aadhaar" placeholder="Aadhaar (12 digits)" maxlength="12" minlength="12" inputmode="numeric" autocomplete="off" pattern="[0-9]{12}" title="Exactly 12 digits, numbers only" required>

<input type="text" name="phone" placeholder="Phone Number (10 digits)" pattern="\d{10,15}" title="Enter valid phone number" required>

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
