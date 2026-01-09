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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToyShop Dashboard</title>
    <link rel="stylesheet" href="../css/profilemodal.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/modaldashboard.css">

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

            <section id="availableToys">
                <h2>Transformers</h2>
                <div class="toy-grid">
                    <div class="toy-card" data-name="Optimus Prime" data-price="₱1,200">
                        <img src="../img/op.jpg" alt="Optimus Prime">
                        <h3>Optimus Prime</h3>
                        <p class="price">₱1,200</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Bumblebee" data-price="₱999">
                        <img src="../img/bum.avif" alt="Bumblebee">
                        <h3>Bumblebee</h3>
                        <p class="price">₱999</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>


                    </div>

                    <div class="toy-card" data-name="Megatron" data-price="₱1,400">
                        <img src="../img/me.avif" alt="Megatron">
                        <h3>Megatron</h3>
                        <p class="price">₱1,400</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Starscream" data-price="₱1,100">
                        <img src="../img/star.jpg" alt="Starscream">
                        <h3>Starscream</h3>
                        <p class="price">₱1,100</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Grimlock" data-price="₱1,350">
                        <img src="../img/Grimlock.jpg" alt="Grimlock">
                        <h3>Grimlock</h3>
                        <p class="price">₱1,350</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Mini Optimus" data-price="₱799">
                        <img src="../img/Mini Optimus.jpg" alt="Mini Optimus">
                        <h3>Mini Optimus</h3>
                        <p class="price">₱799</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>
                </div>
            </section>

            <section id="availableToys">
                <h2>Cars</h2>
                <div class="toy-grid">
                    <div class="toy-card" data-name="Racing Car" data-price="₱499">
                        <div class="discount-badge">-10%</div>
                        <img src="../img/cars.jpg" alt="Racing Car">
                        <h3>Racing Car</h3>
                        <p class="price">₱499</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Monster Truck" data-price="₱799">
                        <img src="../img/Monster Truck.jpg" alt="Monster Truck">
                        <h3>Monster Truck</h3>
                        <p class="price">₱799</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Sports Car" data-price="₱599">
                        <img src="../img/Sports Car.jpg" alt="Sports Car">
                        <h3>Sports Car</h3>
                        <p class="price">₱599</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Fire Truck" data-price="₱650">
                        <img src="../img/Fire Truck.jpg" alt="Fire Truck">
                        <h3>Fire Truck</h3>
                        <p class="price">₱650</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Police Car" data-price="₱550">
                        <div class="discount-badge">-5%</div>
                        <img src="../img/Police Car.jpg" alt="Police Car">
                        <h3>Police Car</h3>
                        <p class="price">₱550</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Taxi Car" data-price="₱480">
                        <img src="../img/Taxi Car.jpg" alt="Taxi Car">
                        <h3>Taxi Car</h3>
                        <p class="price">₱480</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>
                </div>
            </section>

            <section id="availableToys">
                <h2>Dinosaurs</h2>
                <div class="toy-grid">
                    <div class="toy-card" data-name="T-Rex" data-price="₱1,200">
                        <img src="../img/T-Rex.jpg" alt="T-Rex">
                        <h3>T-Rex</h3>
                        <p class="price">₱1,200</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Triceratops" data-price="₱1,100">
                        <div class="discount-badge">-15%</div>
                        <img src="../img/Triceratops.jpg" alt="Triceratops">
                        <h3>Triceratops</h3>
                        <p class="price">₱1,100</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Stegosaurus" data-price="₱950">
                        <img src="../img/Stegosaurus.jpg" alt="Stegosaurus">
                        <h3>Stegosaurus</h3>
                        <p class="price">₱950</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Velociraptor" data-price="₱870">
                        <div class="discount-badge">-10%</div>
                        <img src="../img/Velociraptor.jpg" alt="Velociraptor">
                        <h3>Velociraptor</h3>
                        <p class="price">₱870</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Spinosaurus" data-price="₱1,000">
                        <img src="../img/Spinosaurus.jpg" alt="Spinosaurus">
                        <h3>Spinosaurus</h3>
                        <p class="price">₱1,000</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>

                    <div class="toy-card" data-name="Ankylosaurus" data-price="₱980">
                        <img src="../img/Ankylosaurus.avif" alt="Ankylosaurus">
                        <h3>Ankylosaurus</h3>
                        <p class="price">₱980</p>
                        <div class="btn-group">
                            <button class="buy-now-btn">Buy Now</button>
                            <button class="add-cart-btn">Add to Cart</button>
                        </div>

                    </div>
                </div>
            </section>
        </main>

        <div id="cartModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-body">
                    <img id="cartToyImage" src="../img/1.avif" alt="Toy Image">
                    <div class="modal-left">
                        <h2>Add to Cart</h2>
                        <form id="cartForm">
                            <label>Toy Name:</label>
                            <input type="text" id="cartToyName" name="cartToyName" readonly>

                            <label>Price:</label>
                            <input type="text" id="cartToyPrice" name="cartToyPrice" readonly>

                            <label>Quantity:</label>
                            <div class="quantity-control">
                                <button type="button" id="cartMinusQty">−</button>
                                <input type="number" id="cartQuantity" name="cartQuantity" value="1" min="1">
                                <button type="button" id="cartPlusQty">+</button>
                            </div>
                            <button type="submit" class="confirm-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- Buy Modal -->
        <div id="buyModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-body">
                    <img id="modalImage" src="../img/Ankylosaurus.avif" alt="Product Image">
                    <h2 id="modalToyName">Toy Name</h2>
                    <p id="modalToyPrice">₱499</p>
                    <div class="quantity-control">
                        <button id="minusQty">−</button>
                        <input type="number" id="quantity" value="1" min="1">
                        <button id="plusQty">+</button>
                    </div>
                    <div class="address-field">
                        <label>Shipping Address</label>
                        <textarea id="address" rows="3" placeholder="Enter your complete address"></textarea>
                    </div>
                    <div class="payment-method">
                        <label>Payment Method</label>
                        <div class="payment-options">
                            <label class="payment-option">
                                <input type="radio" name="payment" value="cod" checked>
                                <span class="custom-radio"></span>
                                <span class="payment-text">Cash on Delivery</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment" value="gcash">
                                <span class="custom-radio"></span>
                                <span class="payment-text">GCash</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment" value="paymaya">
                                <span class="custom-radio"></span>
                                <span class="payment-text">PayMaya</span>
                            </label>
                        </div>
                    </div>
                    <button id="confirmBuy" class="confirm-btn">Confirm Purchase</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const USER_ID = <?php echo json_encode($userId); ?>;
        const USER_NAME = <?php echo json_encode($userName); ?>;
    </script>

    <script src="../js/dashboard.js"></script>
    <script src="../js/addtocart.js"></script>
    <script src="../js/profilemodal.js"></script>
</body>

</html>