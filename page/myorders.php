<?php
session_start();
include '../backend/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/orders.css">
    <link rel="stylesheet" href="../css/profilemodal.css">

</head>

<body>
    <div class="dashboard-container">

        <aside class="sidebar">
            <div class="profile">
                <img src="../img/profile.png">
                <h3 id="userName"><?php echo htmlspecialchars($userName); ?></h3>
            </div>

            <nav>
                <ul>
                    <li><a href="dashboard.php">Available Toys</a></li>
                    <li><a href="myorders.php">My Orders</a></li>
                    <li><a href="cart.php">Cart 🛒</a></li>
                </ul>
            </nav>
            <form method="post" action="../backend/logout.php"
                onsubmit="return confirm('Are you sure you want to logout?');">
                <button type="submit" id="logoutBtn">Logout</button>
            </form>
        </aside>

        <main class="main-content">

            <header class="top-navbar">
                <div class="nav-left">
                    <h1>Dashboard</h1>
                </div>
                <div class="nav-right">
                    <span class="welcome-text">
                        Welcome, <strong id="navUserName"><?php echo htmlspecialchars($userName); ?></strong>
                    </span>

                    <img src="../uploads/profile/<?= $_SESSION['profile_img'] ?? 'default.png' ?>" class="nav-profile"
                        id="profileImg" alt="Profile">

                    <div id="profileModal" class="profile-modal">
                        <div class="profile-modal-content">
                            <span class="close-profile">&times;</span>
                            <h3>Change Profile Picture</h3>
                            <img src="../img/profile.png" id="previewImg" class="profile-preview">
                            <input type="file" id="profileInput" accept="image/*">

                            <div class="profile-actions">
                                <button id="saveProfileBtn">Save</button>
                                <button id="cancelProfileBtn">Cancel</button>
                            </div>

                        </div>
                    </div>

                </div>
            </header>
            <section class="">

                <?php if (empty($orders)): ?>
                    <p class="no-orders">No orders yet.</p>
                <?php else: ?>
                    <div class="orders-grid">
                        <?php foreach ($orders as $order): ?>
                            <div class="order-card">

                                <img src="<?php echo htmlspecialchars($order['image']); ?>">

                                <div class="order-details">
                                    <h3><?php echo htmlspecialchars($order['name']); ?></h3>
                                    <p>Price: <?php echo htmlspecialchars($order['price']); ?></p>
                                    <p>Qty: <?php echo $order['quantity']; ?></p>
                                    <p>Status:
                                        <span class="status <?php echo strtolower($order['status']); ?>">
                                            <?php echo $order['status']; ?>
                                        </span>
                                    </p>

                                    <div class="order-actions">
                                        <?php if ($order['status'] === 'Pending'): ?>
                                            <button class="cancel-btn" data-id="<?php echo $order['id']; ?>">
                                                Cancel
                                            </button>
                                        <?php else: ?>
                                            <button class="delete-btn" data-id="<?php echo $order['id']; ?>">
                                                Delete
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </section>
        </main>
    </div>

    <script src="../js/myorders.js"></script>
    <script src="../js/profilemodal.js"></script>
</body>

</html>