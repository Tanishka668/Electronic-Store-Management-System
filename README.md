# 💻 Electronic Store Management System

A web-based **Electronic Store Management System** built using **PHP and MySQL** to help manage products, customers, sales, and inventory. It simplifies daily store operations with a clean interface and secure database integration.

---

## 🚀 Features
- 🛒 Manage electronic products (add, edit, delete)
- 👥 Maintain customer details and purchase history
- 💰 Handle billing and payment tracking
- 📦 Manage stock and inventory levels
- 📊 Generate sales reports and insights
- 🔐 Secure login system for admin and staff
- 💡 Simple, modern, and responsive UI

---

## 🛠️ Technologies Used
| Category | Technology |
|-----------|-------------|
| **Frontend** | HTML, CSS, JavaScript |
| **Backend** | PHP |
| **Database** | MySQL |
| **Server** | XAMPP / WAMP |

---

## ⚙️ Installation Guide
1. Download
2. Move to XAMPP’s htdocs Folder Place the project folder inside: C:\xampp\htdocs\
3. Setup Database
4. Open phpMyAdmin at http://localhost/phpmyadmin
5. Create a new database (e.g., electronic_store)
6. Import the provided .sql file located in the project folder
7. Configure Database Connection, In database.php, update your connection details if required: $conn = mysqli_connect("localhost", "root", "", "electronic_store");
8. Run the Project, Start Apache and MySQL in XAMPP, then open in browser: http://localhost/electronic-store-management/login.php
