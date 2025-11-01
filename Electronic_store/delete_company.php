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

// If user clicked "Delete" confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    if (isset($_SESSION['edit_company_id']) && !empty($_SESSION['edit_company_id'])) {
        $company_id = $_SESSION['edit_company_id'];

        $delete_query = "DELETE FROM company WHERE company_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $company_id);

        if ($stmt->execute()) {
            $message = "✅ Company deleted successfully!";
            // Clear session variable after delete
            unset($_SESSION['edit_company_id']);
        } else {
            $message = "❌ Error deleting company: " . $conn->error;
        }

        $stmt->close();
    } else {
        $message = "❌ Invalid request — no company selected.";
    }
}

// Fetch company details from session company_id
$company = null;
if (isset($_SESSION['edit_company_id']) && !empty($_SESSION['edit_company_id'])) {
    $company_id = $_SESSION['edit_company_id'];

    $query = "SELECT * FROM company WHERE company_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $company = $result->fetch_assoc();
    } else {
        $message = "Company not found.";
    }

    $stmt->close();
} elseif (empty($message)) {
    $message = "Invalid request — no company selected.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delete Company</title>
<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #f5f6fa;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 500px;
        margin: 60px auto;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }
    h2 {
        color: #d63031;
        margin-bottom: 20px;
    }
    .info-box {
        background: #f1f2f6;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 25px;
        text-align: left;
    }
    .info-box p {
        margin: 8px 0;
        font-size: 16px;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    .btn-delete {
        background-color: #d63031;
        color: white;
    }
    .btn-delete:hover {
        background-color: #b71c1c;
    }
    .btn-cancel {
        background-color: #636e72;
        color: white;
        margin-left: 10px;
        text-decoration: none;
    }
    .btn-cancel:hover {
        background-color: #2d3436;
    }
    .message {
        margin-top: 15px;
        font-weight: bold;
        color: #2d3436;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Delete Company</h2>

    <?php if ($company): ?>
        <div class="info-box">
            <p><strong>Company Number:</strong> <?php echo htmlspecialchars($company['contact_number']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($company['email']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($company['address']); ?></p>
        </div>
        <p>Are you sure you want to delete this company?</p>

        <form method="POST">
            <input type="hidden" name="company_number" value="<?php echo htmlspecialchars($company['contact_number']); ?>">
            <button type="submit" name="confirm_delete" class="btn btn-delete">Yes, Delete</button>
            <a href="company.php" class="btn btn-cancel">Cancel</a>
        </form>
    <?php elseif ($message): ?>
        <p class="message"><?php echo $message; ?></p>
        <a href="company_list.php" class="btn btn-cancel">Go Back</a>
    <?php else: ?>
        <p class="message">Invalid request.</p>
        <a href="company_list.php" class="btn btn-cancel">Go Back</a>
    <?php endif; ?>

    <?php if ($message && strpos($message, '✅') !== false): ?>
        <script>
            setTimeout(() => {
                window.location.href = "company_list.php";
            }, 1500);
        </script>
    <?php endif; ?>
</div>
</body>
</html>
