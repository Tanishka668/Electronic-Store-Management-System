<?php
session_start();
include("database.php");

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Handle logout button click
if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — Electronic Store</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        background-color: #f5f8ff;
        color: #333;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    header {
        width: 100%;
        background-color: #007bff;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 50px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    header h1 {
        font-size: 24px;
    }

    header form {
        margin: 0;
    }

    header button {
        color: #007bff;
        background-color: white;
        border: none;
        cursor: pointer;
        padding: 8px 15px;
        border-radius: 6px;
        font-weight: bold;
        transition: 0.3s ease;
    }

    header button:hover {
        background-color: #e9f2ff;
    }

    h2 {
        margin-top: 30px;
        font-size: 22px;
        color: #007bff;
    }

    .container {
        margin-top: 40px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        width: 85%;
        max-width: 1200px;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 25px 20px;
        text-align: center;
        transition: 0.3s ease;
        border: 1px solid #e1e5f2;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    .card h3 {
        color: #007bff;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .card p {
        color: #555;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .card a {
        display: inline-block;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        padding: 8px 18px;
        border-radius: 6px;
        font-weight: bold;
        transition: 0.3s ease;
    }

    .card a:hover {
        background-color: #0056b3;
    }

    footer {
        margin-top: 40px;
        text-align: center;
        font-size: 13px;
        color: #777;
        padding-bottom: 20px;
    }

    footer span {
        color: #007bff;
        font-weight: 500;
    }
</style>
</head>
<body>

<header>
    <h1>🔌 Electronic Store Dashboard</h1>

    <!-- Logout button as a POST form -->
    <form method="post" style="display:inline;">
        <button type="submit" name="logout">Logout</button>
    </form>
</header>

<h2>Welcome, <?php echo htmlspecialchars($username); ?> 👋</h2>

<div class="container">
    <div class="card">
        <h3>🏭 Companies</h3>
        <p>Manage supplier and company details.</p> <br>
        <a href="company.php">View</a>
    </div>

    <div class="card">
        <h3>📦 Products</h3>
        <p>Manage all store products and categories.</p>
        <a href="products.php">View</a>
    </div>

    <div class="card">
        <h3>🧾 Purchases</h3>
        <p>Record new stock purchases from companies.</p>
        <a href="purchases.php">View</a>
    </div>

    <div class="card">
        <h3>🚚 Distribution</h3>
        <p>Send products to different store branches.</p>
        <a href="distribution.php">View</a>
    </div>

    <div class="card">
        <h3>📊 Stock Management</h3>
        <p>Track product availability and current inventory.</p>
        <a href="stock_management.php">View</a>
    </div>

    <div class="card">
        <h3>⚙️ Settings</h3>
        <p>Manage your profile and system preferences.</p>
        <a href="setting.php">Open</a>
    </div>
</div>

<footer>
    <p>© 2025 <span>Electronic Store Management</span></p>
</footer>

</body>
</html>
