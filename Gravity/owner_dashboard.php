<?php
session_start();
include "db_connect.php";

// Check if logged in and is admin
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Fetch summary data
$total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'customer'")->fetch_assoc()['count'];
$total_suppliers = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'supplier'")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$pending_withdrawals = $conn->query("SELECT COUNT(*) as count FROM withdrawals WHERE status = 'Pending'")->fetch_assoc()['count'];

// Fetch admin profit balance
$user_id = $_SESSION["user_id"];
$balance_query = $conn->query("SELECT balance FROM users WHERE id = $user_id AND user_type = 'admin' LIMIT 1");
$balance_data = $balance_query->fetch_assoc();
$admin_balance = $balance_data ? $balance_data['balance'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="owner_dashboard.css">
</head>
<body class="body">
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Admin</h2>
            <ul>
                <li><a href="owner_dashboard.php">Dashboard</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="owner_manage_products.php">Manage Products</a></li>
                <li><a href="owner_manage_orders.php">Manage Orders</a></li>
                <li><a href="owner_withdrawal.php">Withdrawals</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <h2>Welcome, Owner <?php echo $_SESSION["user_name"]; ?>!</h2>
            </header>

            <section class="stats">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Suppliers</h3>
                    <p><?php echo $total_suppliers; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <p><?php echo $total_products; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p><?php echo $total_orders; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Pending Withdrawals</h3>
                    <p><?php echo $pending_withdrawals; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Profit Balance</h3>
                    <p>$<?php echo number_format($admin_balance, 2); ?></p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
