<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("database.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query to check user credentials
    $query = "SELECT * FROM User WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid Username or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic Store Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            padding: 40px 30px;
            width: 370px;
            text-align: center;
            color: #fff;
            animation: fadeIn 1.2s ease;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        h2 {
            font-size: 26px;
            margin-bottom: 25px;
            color: #fff;
            letter-spacing: 1px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        .input-field {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: none;
            border-radius: 8px;
            outline: none;
            background: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            color: #333;
        }

        .input-field i {
            position: absolute;
            right: 15px;
            top: 12px;
            color: #007bff;
            font-size: 18px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background: linear-gradient(90deg, #0056b3, #0099ff);
            transform: scale(1.03);
        }

        .error {
            color: #ff4d4d;
            margin-top: 10px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px;
            border-radius: 6px;
        }

        .footer {
            margin-top: 15px;
            font-size: 13px;
            color: #ddd;
        }

        .footer a {
            color: #fff;
            text-decoration: underline;
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="login-container">
        <h2>🔌 Electronic Store Login</h2>
        <form method="POST" action="" autocomplete="off">
            <div class="input-field">
                <input type="text" name="username" placeholder="Enter Username" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-field">
                <input type="password" name="password" placeholder="Enter Password" required>
                <i class="fas fa-lock"></i>
            </div>
            <input type="submit" value="Login">
        </form>
        <?php if ($message) echo "<p class='error'>$message</p>"; ?>
        <div class="footer">
            <p>© 2025 Electronic Store Management</p>
        </div>
    </div>
</body>
</html>
