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
$selected_product = null;

// Auto-fill product details when selected
if (isset($_POST['product_id']) && !empty($_POST['product_id']) && !isset($_POST["distribute"])) {
    $product_id = $_POST['product_id'];
    $query = "SELECT * FROM Product WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $selected_product = $stmt->get_result()->fetch_assoc();
}

// Handle distribution form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["distribute"])) {
    $product_id = $_POST["product_id"];
    $receiver_name = trim($_POST["receiver_name"]);
    $quantity = (int)$_POST["quantity"];
    $date = $_POST["date"];
    $today = date('Y-m-d');

    // Check date validity
    if ($date < $today) {
        $message = "<p style='color: red;'>Date cannot be earlier than today!</p>";
    } else {
        // Fetch product details
        $query = "SELECT product_name, company_name, quantity AS available_qty FROM Product WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            if ($quantity > 0 && $quantity <= $product["available_qty"]) {
                // Insert into Distribution table
                $insert = "INSERT INTO Distribution (product_id, product_name, company_name, receiver_name, quantity, date, created_at)
                           VALUES (?, ?, ?, ?, ?, ?, NOW())";
                $stmt2 = $conn->prepare($insert);
                $stmt2->bind_param("isssis", $product_id, $product["product_name"], $product["company_name"], $receiver_name, $quantity, $date);
                $stmt2->execute();

                // Update Product quantity
                $new_qty = $product["available_qty"] - $quantity;
                $update = "UPDATE Product SET quantity = ? WHERE product_id = ?";
                $stmt3 = $conn->prepare($update);
                $stmt3->bind_param("is", $new_qty, $product_id);
                $stmt3->execute();

                $message = "<p style='color: green;'>Distribution recorded successfully!</p>";
            } else {
                $message = "<p style='color: red;'>Invalid quantity! Only {$product["available_qty"]} available.</p>";
            }
        } else {
            $message = "<p style='color: red;'>Product not found.</p>";
        }
    }
}

// Fetch products for dropdown
$products = $conn->query("SELECT product_id, product_name FROM Product ORDER BY product_name");

// Fetch all distributions
$distributions = $conn->query("SELECT * FROM Distribution ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Distribution</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f4ff;
        margin: 0;
        padding: 20px;
    }
    .container {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 900px;
        margin: auto;
    }
    h1, h2 {
        text-align: center;
        color: #0d47a1;
    }
    form {
        margin-bottom: 30px;
    }
    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #0d47a1;
    }
    input, select {
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
    }
    button:hover {
        background-color: #1565c0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: #0d47a1;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #e3f2fd;
    }
    .back-btn {
        background-color: #1976d2;
        margin-bottom: 20px;
    }
    .back-btn:hover {
        background-color: #1565c0;
    }
</style>
</head>
<body>
<div class="container">
    <button class="back-btn" onclick="window.location.href='dashboard.php'">← Back</button>
    <h1>Product Distribution</h1>

    <?php echo $message; ?>

    <form method="POST" action="" autocomplete="off">
        <label>Select Product:</label>
        <select name="product_id" required onchange="this.form.submit()">
            <option value="">-- Select Product --</option>
            <?php while ($row = $products->fetch_assoc()) { ?>
                <option value="<?php echo $row['product_id']; ?>"
                    <?php if ($selected_product && $selected_product['product_id'] == $row['product_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['product_name']); ?>
                </option>
            <?php } ?>
        </select>

        <label>Company Name:</label>
        <input type="text" id="company_name" name="company_name" readonly
            value="<?php echo $selected_product ? htmlspecialchars($selected_product['company_name']) : ''; ?>">

        <label>Available Quantity:</label>
        <input type="text" id="available_qty" readonly
            value="<?php echo $selected_product ? htmlspecialchars($selected_product['quantity']) : ''; ?>">

        <label>Quantity to Distribute:</label>
        <input type="number" name="quantity" required min="1">

        <label>Receiver Name:</label>
        <input type="text" name="receiver_name" required>

        <label>Date:</label>
        <input type="date" name="date" required id="dateField">

        <button type="submit" name="distribute">Distribute</button>
    </form>

    <h2>All Distributions</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Company</th>
            <th>Receiver</th>
            <th>Quantity</th>
            <th>Date</th>
            <th>Created At</th>
        </tr>
        <?php while ($dist = $distributions->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $dist['distribution_id']; ?></td>
                <td><?php echo htmlspecialchars($dist['product_name']); ?></td>
                <td><?php echo htmlspecialchars($dist['company_name']); ?></td>
                <td><?php echo htmlspecialchars($dist['receiver_name']); ?></td>
                <td><?php echo $dist['quantity']; ?></td>
                <td><?php echo $dist['date']; ?></td>
                <td><?php echo $dist['created_at']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<script>
// ✅ Set today's date as the minimum selectable date
document.addEventListener("DOMContentLoaded", function() {
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("dateField").setAttribute("min", today);

    // ✅ Quantity check
    const qtyInput = document.querySelector('input[name="quantity"]');
    const availInput = document.getElementById('available_qty');

    qtyInput.addEventListener("input", function() {
        const available = parseInt(availInput.value) || 0;
        const entered = parseInt(this.value) || 0;
        if (entered > available) {
            alert("You cannot distribute more than the available quantity (" + available + ").");
            this.value = available;
        }
    });
});
</script>
</body>
</html>
