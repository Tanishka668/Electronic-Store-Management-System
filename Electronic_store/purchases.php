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

/**
 * Helper: fetch product price by id
 */
function getProductPrice($conn, $product_id) {
    $q = "SELECT price FROM Product WHERE product_id = ?";
    if ($stmt = $conn->prepare($q)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($price);
        if ($stmt->fetch()) {
            $stmt->close();
            return $price;
        }
        $stmt->close();
    }
    return null;
}

// Handle Add Purchase
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_purchase'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $company_name = trim($_POST['company_name']);

    $unit_price = getProductPrice($conn, $product_id);
    if ($unit_price === null) {
        $message = "Selected product not found.";
    } else {
        $total_price = round($unit_price * $quantity, 2);

        $query = "INSERT INTO Purchase (product_id, quantity, total_price, purchase_date, company_name)
                  VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iids", $product_id, $quantity, $total_price, $company_name);

        if ($stmt->execute()) {
            $message = "Purchase added successfully!";
        } else {
            $message = "Error adding purchase: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch Purchases with Product names
$query = "SELECT p.purchase_id, pr.product_name, p.quantity, p.total_price, p.purchase_date, p.company_name, p.product_id
          FROM Purchase p
          JOIN Product pr ON p.product_id = pr.product_id
          ORDER BY p.purchase_date DESC";
$result = $conn->query($query);

// Fetch products for dropdown
$productQuery = "SELECT product_id, product_name, price, company_name FROM Product";
$productResult = $conn->query($productQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchases</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 0; }
        header { background-color: #2d3436; color: white; padding: 15px; position: relative; }
        h1 { margin: 0; text-align: center; }
        .back-btn {
            position: absolute; left: 20px; top: 50%; transform: translateY(-50%);
            background-color: #636e72; color: white; border: none; border-radius: 6px;
            padding: 8px 14px; font-weight: bold; cursor: pointer; text-decoration: none;
            transition: background 0.2s ease;
        }
        .back-btn:hover { background-color: #b2bec3; color: black; }
        .container { width: 90%; margin: 30px auto; background: #fff; border-radius: 10px;
                     box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; }
        form { margin-bottom: 30px; }
        label { display:inline-block; width: 90px; }
        input, select { padding: 8px; margin: 5px; border-radius: 6px; border: 1px solid #ccc; }
        button { padding: 8px 14px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .add-btn { background-color: #0984e3; color: white; }
        table { width: 100%; border-collapse: collapse; text-align: center; }
        th, td { border-bottom: 1px solid #ccc; padding: 10px; }
        th { background-color: #0984e3; color: white; }
        .message { color: green; font-weight: bold; margin-bottom: 10px; }
        input[readonly] { background:#f0f0f0; }
    </style>
</head>
<body>
    <header>
        <a href="dashboard.php" class="back-btn">← Back</a>
        <h1>Purchases</h1>
    </header>

    <div class="container">
        <?php if ($message != "") echo "<p class='message'>" . htmlspecialchars($message) . "</p>"; ?>

        <form method="POST" action="" id="addPurchaseForm" autocomplete="off">
            <h3>Add New Purchase</h3>

            <label for="product_select">Product:</label>
            <select id="product_select" name="product_id" required>
                <option value="">Select Product</option>
                <?php while($prod = $productResult->fetch_assoc()) {
                    $pid = (int)$prod['product_id'];
                    $pname = htmlspecialchars($prod['product_name']);
                    $pprice = htmlspecialchars($prod['price']);
                    $pcompany = htmlspecialchars($prod['company_name']);
                ?>
                    <option 
                        value="<?= $pid ?>" 
                        data-price="<?= $pprice ?>" 
                        data-company="<?= $pcompany ?>">
                        <?= $pname ?> (₹<?= $pprice ?>)
                    </option>
                <?php } ?>
            </select>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" placeholder="Quantity" min="1" value="1" required>

            <label for="total_price">Total:</label>
            <input type="number" step="0.01" id="total_price" name="total_price" placeholder="Total Price" readonly required>

            <br>

            <label for="company_name">Company:</label>
            <input type="text" id="company_name" name="company_name" placeholder="Company Name" readonly>

            <button type="submit" name="add_purchase" class="add-btn">Add Purchase</button>
        </form>

        <h3>All Purchases</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Purchase Date</th>
                <th>Company</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= (int)$row['purchase_id']; ?></td>
                    <td><?= htmlspecialchars($row['product_name']); ?></td>
                    <td><?= (int)$row['quantity']; ?></td>
                    <td>₹<?= number_format((float)$row['total_price'], 2); ?></td>
                    <td><?= htmlspecialchars($row['purchase_date']); ?></td>
                    <td><?= htmlspecialchars($row['company_name']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

<script>
(function(){
    const productSelect = document.getElementById('product_select');
    const quantityInput = document.getElementById('quantity');
    const totalInput = document.getElementById('total_price');
    const companyInput = document.getElementById('company_name');

    function getSelectedProductData() {
        const opt = productSelect.options[productSelect.selectedIndex];
        if (!opt) return { price: 0, company: '' };
        const price = parseFloat(opt.getAttribute('data-price')) || 0;
        const company = opt.getAttribute('data-company') || '';
        return { price, company };
    }

    function recalcTotalAndCompany() {
        const { price, company } = getSelectedProductData();
        const qty = parseInt(quantityInput.value) || 0;
        const total = price * qty;
        totalInput.value = total ? total.toFixed(2) : '';
        companyInput.value = company;
    }

    productSelect.addEventListener('change', recalcTotalAndCompany);
    quantityInput.addEventListener('input', recalcTotalAndCompany);
    recalcTotalAndCompany();
})();
</script>
</body>
</html>
