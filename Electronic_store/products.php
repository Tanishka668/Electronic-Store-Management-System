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

// Handle Add Product form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $product_name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $company_name = trim($_POST['company_name']);

    if (!empty($product_name) && !empty($category) && !empty($price) && !empty($quantity) && !empty($company_name)) {
        $query = "INSERT INTO Product (product_name, category, price, quantity, company_name, created_at)
                  VALUES (?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdii", $product_name, $category, $price, $quantity, $company_name);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'>✅ Product added successfully!</p>";
        } else {
            $message = "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        $message = "<p style='color:red;'>⚠️ Please fill all required fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #111827;
            margin: 0;
            padding: 0;
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
        header h1 {
            margin: 0;
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
            transition: background 0.3s ease;
        }
        .back-btn:hover {
            background-color: #1d4ed8;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            background: #f9fafb;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form {
            margin-bottom: 30px;
        }
        input, textarea, select {
            width: 97%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #d1d5db;
            background-color: white;
        }
        button {
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #9ba7c7ff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #d1d5db;
        }
        th {
            background-color: #f3f4f6;
        }

        /* Button section improved */
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .edit-btn, .delete-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        /* Edit Button */
        .edit-btn {
            background-color: #22c55e;
            color: white;
            box-shadow: 0 2px 4px rgba(34, 197, 94, 0.3);
        }

        .edit-btn:hover {
            background-color: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.5);
        }

        /* Delete Button */
        .delete-btn {
            background-color: #ef4444;
            color: white;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        .delete-btn:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.5);
        }
    </style>
</head>
<body>

<header>
    <a href="dashboard.php" class="back-btn">← Back</a>
    <h1>🛍️ Product Management</h1>
</header>

<div class="container">
    <h2>Add New Product</h2>
    <?php echo $message; ?>
    <form method="POST" action="" autocomplete="off">
        <label>Product Name:</label>
        <input type="text" name="product_name" required>

        <label>Category:</label>
        <select name="category" required>
            <option value="">-- Select Category --</option>
            <option value="Phone">Phone</option>
            <option value="Television">Television</option>
            <option value="Refrigerator">Refrigerator</option>
            <option value="AC">AC</option>
        </select>

        <label>Price (₹):</label>
        <input type="number" name="price" step="0.01" required>

        <label>Quantity:</label>
        <input type="number" name="quantity" required>

        <label>Company Name:</label>
        <input type="text" name="company_name" required>

        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h2>Product List</h2>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Company</th>
            <th>Actions</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM Product ORDER BY product_id DESC");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['product_id']}</td>
                        <td>{$row['product_name']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['company_name']}</td>
                        <td class='actions'>
                            <form method='POST' action='edit_product.php' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$row['product_id']}'>
                                <button type='submit' class='edit-btn'>Edit</button>
                            </form>
                            <form method='POST' action='delete_product.php' style='display:inline;'>
                                <input type='hidden' name='product_id' value='{$row['product_id']}'>
                                <button type='submit' class='delete-btn'>Delete</button>
                            </form>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No products found</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
