<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("database.php");

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$message = "";

// Handle stock update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_stock"])) {
    $product_id = $_POST["product_id"];
    $new_quantity = $_POST["new_quantity"];

    if ($new_quantity >= 0) {
        $query = "UPDATE Product SET quantity = ? WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $new_quantity, $product_id);
        if ($stmt->execute()) {
            $message = "✅ Stock updated successfully!";
        } else {
            $message = "❌ Error updating stock.";
        }
    } else {
        $message = "❗ Quantity cannot be negative.";
    }
}

// Check if a company is selected
$selected_company = isset($_GET['company']) ? $_GET['company'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stock Management</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7fa;
    margin: 0;
    padding: 0;
}
header {
    background-color: #007bff;
    color: white;
    padding: 15px;
    text-align: center;
    position: relative;
}
.back-btn {
    position: absolute;
    left: 15px;
    top: 15px;
    background-color: white;
    color: #007bff;
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}
.container {
    margin: 30px auto;
    width: 90%;
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
.company-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}
.company-item {
    background-color: #007bff;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}
.company-item:hover {
    background-color: #0056b3;
}
.message {
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    text-align: center;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}
th {
    background-color: #007bff;
    color: white;
}
.stock-status {
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 5px;
}
.in-stock { background-color: #d4edda; color: #155724; }
.low-stock { background-color: #fff3cd; color: #856404; }
.out-stock { background-color: #f8d7da; color: #721c24; }
.update-input {
    width: 60px;
    padding: 5px;
    text-align: center;
}
.update-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 5px;
    cursor: pointer;
}
.update-btn:hover {
    background-color: #0056b3;
}
.back-to-list {
    display: inline-block;
    background-color: #6c757d;
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    margin-bottom: 15px;
}
.back-to-list:hover {
    background-color: #5a6268;
}
</style>
</head>
<body>
<header>
    <a href="dashboard.php" class="back-btn">← Back</a>
    <h1>Stock Management</h1>
</header>

<div class="container">
    <?php if ($message) echo "<div class='message'>$message</div>"; ?>

    <?php if (!$selected_company): ?>
        <h2 style="text-align:center;">Select a Company</h2>
        <div class="company-list">
            <?php
            $companies = $conn->query("SELECT DISTINCT company_name FROM Product ORDER BY company_name ASC");
            if ($companies->num_rows > 0) {
                while ($row = $companies->fetch_assoc()) {
                    echo "<a class='company-item' href='?company=" . urlencode($row['company_name']) . "'>{$row['company_name']}</a>";
                }
            } else {
                echo "<p>No companies found.</p>";
            }
            ?>
        </div>

    <?php else: ?>
        <a href="stock_management.php" class="back-to-list">← Back</a>
        <h2 style="text-align:center;">Stock for <?= htmlspecialchars($selected_company) ?></h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price (₹)</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Total Value (₹)</th>
                <th>Update Stock</th>
            </tr>

            <?php
            $stmt = $conn->prepare("SELECT * FROM Product WHERE company_name = ? ORDER BY product_name ASC");
            $stmt->bind_param("s", $selected_company);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $status = "";
                    if ($row["quantity"] > 10) {
                        $status = "<span class='stock-status in-stock'>In Stock</span>";
                    } elseif ($row["quantity"] > 0) {
                        $status = "<span class='stock-status low-stock'>Low Stock</span>";
                    } else {
                        $status = "<span class='stock-status out-stock'>Out of Stock</span>";
                    }

                    $total_value = $row["price"] * $row["quantity"];
                    echo "<tr>
                        <td>{$row['product_id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['quantity']}</td>
                        <td>$status</td>
                        <td>{$total_value}</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$row['product_id']}'>
                                <input type='number' name='new_quantity' class='update-input' min='0' value='{$row['quantity']}'>
                                <button type='submit' name='update_stock' class='update-btn'>Update</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No products found for this company.</td></tr>";
            }
            ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
