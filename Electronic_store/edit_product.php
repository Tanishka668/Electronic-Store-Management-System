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

// Get Product ID
if (!isset($_POST['product_id'])) {
    header("Location: product.php");
    exit;
}

$product_id = $_POST['product_id'];
$message = "";

// Fetch product details
$query = "SELECT * FROM Product WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<p style='color:red;'>❌ Product not found!</p>";
    exit;
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $product_name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $company_name = trim($_POST['company_name']);

    if (!empty($product_name) && !empty($category) && !empty($price) && !empty($quantity) && !empty($company_name)) {
        $update_query = "UPDATE Product 
                         SET product_name=?, category=?, price=?, quantity=?, company_name=? 
                         WHERE product_id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssdiii", $product_name, $category, $price, $quantity, $company_name, $product_id);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'>✅ Product updated successfully!</p>";
            // Refresh data
            $product['product_name'] = $product_name;
            $product['category'] = $category;
            $product['price'] = $price;
            $product['quantity'] = $quantity;
            $product['company_name'] = $company_name;
        } else {
            $message = "<p style='color:red;'>❌ Update failed: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        $message = "<p style='color:red;'>⚠️ Please fill all fields.</p>";
    }
    header("Location: products.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #111827;
            margin: 0;
        }
        header {
            background-color: #1f2937;
            color: #facc15;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .back-btn {
            position: absolute;
            left: 20px;
            top: 20px;
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .back-btn:hover {
            background-color: #1d4ed8;
        }
        .container {
            width: 50%;
            margin: 40px auto;
            background: #f9fafb;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, select {
            width: 96%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #d1d5db;
            background-color: white;
        }
        button {
            background-color: #22c55e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.5);
        }
    </style>
</head>
<body>

<header>
    <a href="products.php" class="back-btn">← Back</a>
    <h1>✏️ Edit Product</h1>
</header>

<div class="container">
    <h2>Edit Product Details</h2>
    <?php echo $message; ?>
    <form method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

        <label>Product Name:</label>
        <input type="text" name="product_name" value="<?php echo $product['product_name']; ?>" required>

        <label>Category:</label>
        <select name="category" required>
            <option value="Phone" <?php if ($product['category']=='Phone') echo 'selected'; ?>>Phone</option>
            <option value="Television" <?php if ($product['category']=='Television') echo 'selected'; ?>>Television</option>
            <option value="Refrigerator" <?php if ($product['category']=='Refrigerator') echo 'selected'; ?>>Refrigerator</option>
            <option value="AC" <?php if ($product['category']=='AC') echo 'selected'; ?>>AC</option>
        </select>

        <label>Price (₹):</label>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" required>

        <label>Company Name:</label>
        <input type="text" name="company_name" value="<?php echo $product['company_name']; ?>" required>

        <button type="submit" name="update_product">Update Product</button>
    </form>
</div>

</body>
</html>
