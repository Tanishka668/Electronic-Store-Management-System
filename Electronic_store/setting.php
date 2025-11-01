<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("database.php");

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["change_password"])) {
    $current_password = trim($_POST["current_password"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Fetch current password directly (no hashing)
    $query = "SELECT password FROM User WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($current_password === $user["password"]) {
            if ($new_password === $confirm_password) {
                // Update password directly (plain text)
                $update = "UPDATE User SET password = ? WHERE username = ?";
                $stmt2 = $conn->prepare($update);
                $stmt2->bind_param("ss", $new_password, $username);
                if ($stmt2->execute()) {
                    $message = "<p style='color: green;'>Password changed successfully!</p>";
                } else {
                    $message = "<p style='color: red;'>Error updating password.</p>";
                }
            } else {
                $message = "<p style='color: red;'>New passwords do not match!</p>";
            }
        } else {
            $message = "<p style='color: red;'>Incorrect current password!</p>";
        }
    } else {
        $message = "<p style='color: red;'>User not found.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Settings - Change Password</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f4ff;
        margin: 0;
        padding: 0;
    }
    .container {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: 80px auto;
        position: relative;
    }
    h1 {
        text-align: center;
        color: #0d47a1;
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #0d47a1;
    }
    input {
        width: 98%;
        padding: 8px;
        margin-top: 5px;
        border-radius: 6px;
        border: 1px solid #90caf9;
        background-color: #e3f2fd;
    }
    button {
        background-color: #0d47a1;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        margin-top: 15px;
        cursor: pointer;
        width: 100%;
    }
    button:hover {
        background-color: #1565c0;
    }
    .back-btn {
        background-color: #1976d2;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 14px;
        position: absolute;
        top: 8px;
        left: 20px;
        cursor: pointer;
        width: 8%;
    }
    .back-btn:hover {
        background-color: #1565c0;
    }
</style>
</head>
<body>
    <button class="back-btn" onclick="window.location.href='dashboard.php'">← Back</button>

    <div class="container">
        <h1>Change Password</h1>
        <?php echo $message; ?>

        <form method="POST" action="" autocomplete="off">
            <label>Current Password:</label>
            <input type="password" name="current_password" required>

            <label>New Password:</label>
            <input type="password" name="new_password" required minlength="3">

            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" required minlength="3">

            <button type="submit" name="change_password">Update Password</button>
        </form>
    </div>
</body>
</html>
