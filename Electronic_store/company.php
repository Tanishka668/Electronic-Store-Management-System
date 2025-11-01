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

// Handle Add Company
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_company'])) {
    $company_name = trim($_POST['company_name']);
    $company_contact = trim($_POST['company_contact']);
    $company_email = trim($_POST['company_email']);
    $company_address = trim($_POST['company_address']);

    if ($company_name && $company_contact && $company_email && $company_address) {
        $query = "INSERT INTO company (company_name, company_contact, company_email, company_address)
                  VALUES ('$company_name', '$company_contact', '$company_email', '$company_address')";
        if (mysqli_query($conn, $query)) {
            $message = "✅ Company added successfully!";
        } else {
            $message = "❌ Error adding company: " . mysqli_error($conn);
        }
    } else {
        $message = "⚠️ Please fill all fields.";
    }
}

// Fetch Companies
$companies = mysqli_query($conn, "SELECT * FROM company ORDER BY company_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Companies - Electronic Store</title>
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
        max-width: 900px;
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
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    input, textarea {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        width: 100%;
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

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:hover {
        background-color: #f0f4ff;
    }

    .btn {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-size: 13px;
        font-weight: bold;
        transition: 0.3s ease;
        white-space: nowrap;
    }

    .edit-btn {
        background-color: #ffc107;
    }

    .edit-btn:hover {
        background-color: #e0a800;
    }

    .delete-btn {
        background-color: #dc3545;
    }

    .delete-btn:hover {
        background-color: #b02a37;
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
        <a href="dashboard.php" class="back-btn">⬅ Back</a>
        <h1>🏭 Manage Companies</h1>
    </header>

    <div class="container">
        <h2>Add New Company</h2>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <form method="POST" action="">
            <input type="text" name="company_name" placeholder="Company Name" required>
            <input type="text" name="company_contact" placeholder="Contact Number" required>
            <input type="email" name="company_email" placeholder="Email Address" required>
            <textarea name="company_address" placeholder="Company Address" required></textarea>
            <input type="submit" name="add_company" value="Add Company">
        </form>

        <h2>Company List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Company Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Address</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($companies)) { ?>
                <tr>
                    <td><?php echo $row['company_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td>
                        <a href="edit_company.php<?php $_SESSION['edit_company_id'] = $row['company_id']; ?>" class="btn edit-btn">✏ Edit</a>
                    </td>
                    <td>
                        <a href="delete_company.php<?php $_SESSION['edit_company_id'] = $row['company_id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this company?');">🗑 Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
