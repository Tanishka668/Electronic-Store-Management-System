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

// Get Product ID
if (!isset($_POST['product_id'])) {
    header("Location: product.php");
    exit;
}

$product_id = $_POST['product_id'];

// Handle Delete Confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    $stmt = $conn->prepare("DELETE FROM Product WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Product deleted successfully!'); window.location='product.php';</script>";
        exit;
    } else {
        echo "<p style='color:red;'>❌ Error deleting product: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Fetch product details for confirmation
$stmt = $conn->prepare("SELECT product_name FROM Product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<p style='color:red;'>❌ Product not found!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Delete Product</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #ffffff;
        text-align: center;
        margin-top: 100px;
        color: #111827;
    }
    .box {
        background: #f9fafb;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        width: 40%;
        margin: auto;
    }
    h2 {
        color: #ef4444;
    }
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    .yes {
        background-color: #ef4444;
        color: white;
    }
    .yes:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(239,68,68,0.4);
    }
    .no {
        background-color: #6b7280;
        color: white;
    }
    .no:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
    }
</style>
</head>
<body>

<div class="box">
    <h2>⚠️ Confirm Deletion</h2>
    <p>Are you sure you want to delete <b><?php echo htmlspecialchars($product['product_name']); ?></b>?</p>

    <form method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <button type="submit" name="confirm_delete" class="btn yes">Yes, Delete</button>
        <a href="products.php" class="btn no" style="text-decoration:none;">Cancel</a>
    </form>
</div>

</body>
</html>
