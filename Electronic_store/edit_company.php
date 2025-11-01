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

// Check if a company ID is stored in session
if (!isset($_SESSION['edit_company_id']) || empty($_SESSION['edit_company_id'])) {
    die("❌ No company selected for editing.");
}

$company_id = intval($_SESSION['edit_company_id']);
$message = "";

// Fetch existing company details
$query = "SELECT * FROM Company WHERE company_id = $company_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    die("❌ Company not found!");
}

$company = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_company'])) {
    $company_name = trim($_POST['company_name']);
    $company_contact = trim($_POST['company_number']);
    $company_email = trim($_POST['email']);
    $company_address = trim($_POST['address']);

    if ($company_name && $company_contact && $company_email && $company_address) {
        $update_query = "
            UPDATE company 
            SET company_name = '$company_name', 
                contact_number = '$company_contact', 
                email = '$company_email', 
                address = '$company_address'
            WHERE company_id = $company_id
        ";
        if (mysqli_query($conn, $update_query)) {
            $message = "✅ Company updated successfully!";
            // Refresh company data
            $result = mysqli_query($conn, "SELECT * FROM company WHERE company_id = $company_id");
            $company = mysqli_fetch_assoc($result);
        } else {
            $message = "❌ Error updating company: " . mysqli_error($conn);
        }
    } else {
        $message = "⚠️ Please fill all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Company - Electronic Store</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #ffffff;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #007bff;
        color: white;
        padding: 10px 40px;
        position: relative;
        text-align: center;
    }

    header h1 {
        font-size: 22px;
        margin: 0;
    }

    .back-btn {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #007bff;
        background-color: white;
        padding: 6px 12px;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
        font-size: 14px;
        transition: 0.3s ease;
    }

    .back-btn:hover {
        background-color: #e9f2ff;
    }

    .container {
        max-width: 700px;
        margin: 40px auto;
        background-color: #f9f9f9;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
        color: #007bff;
        text-align: center;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    input, textarea {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        width: 97%;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .message {
        text-align: center;
        margin-bottom: 15px;
        font-weight: bold;
        color: green;
    }
</style>
</head>
<body>
    <header>
        <a href="company.php" class="back-btn">⬅ Back</a>
        <h1>✏ Edit Company</h1>
    </header>

    <div class="container">
        <h2>Edit Company Details</h2>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <form method="POST" action="" autocomplete="off">
            <input type="text" name="company_name" value="<?php echo htmlspecialchars($company['company_name'] ?? ''); ?>" placeholder="Company Name" required>
            <input type="text" name="company_number" value="<?php echo htmlspecialchars($company['contact_number'] ?? ''); ?>" placeholder="Contact Number" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($company['email'] ?? ''); ?>" placeholder="Email Address" required>
            <textarea name="address" placeholder="Company Address" required><?php echo htmlspecialchars($company['address'] ?? ''); ?></textarea>
            <input type="submit" name="update_company" value="Update Company">
        </form>
    </div>
</body>
</html>
