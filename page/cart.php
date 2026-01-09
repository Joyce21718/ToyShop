<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
$userName = $_SESSION['user_name'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/mycart.css">
    <link rel="stylesheet" href="../css/profilemodal.css">
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="profile">
                <img src="../img/profile.png" alt="">
                <h3 id="userName"><?php echo htmlspecialchars($userName); ?></h3>

            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Available Toys</a></li>
                    <li><a href="myorders.php">My Orders</a></li>
                    <li><a href="cart.php" class="active">Cart 🛒</a></li>
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
                    <h1>My Cart</h1>
                </div>
                <div class="nav-right">
                    <span class="welcome-text">
                        Welcome, <strong id="navUserName">
                            <?php echo htmlspecialchars($userName); ?>
                        </strong>
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

            <?php if (empty($cart)): ?>
                <p class="no-orders">Your cart is empty.</p>
            <?php else: ?>
                <div class="orders-grid">
                    <?php foreach ($cart as $item): ?>
                        <div class="order-card">
                            <img src="<?php echo htmlspecialchars($item['img']); ?>"
                                alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="order-details">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p>Price: <?php echo htmlspecialchars($item['price']); ?></p>
                                <p>Quantity: <?php echo intval($item['quantity']); ?></p>
                                <div class="order-actions">
                                    <form method="post" action="../backend/removecart.php">
                                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                        <button type="submit" class="delete-btn">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script src="../js/profilemodal.js"></script>
</body>

</html>