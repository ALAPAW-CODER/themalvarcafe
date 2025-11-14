<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['isLoggedIn']) && !isset($_SESSION['isAdmin'])) {
    header('Location: login.php');
    exit();
}

// Initialize cart in session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product = [
            'name' => $_POST['product_name'],
            'price' => $_POST['product_price'],
            'image' => $_POST['product_image'],
            'quantity' => 1
        ];

        // Check if product already in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $product['name']) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = $product;
        }

        header('Location: coffee-landing.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Malvar Bat Cave Cafe</title>
    <script src="https://kit.fontawesome.com/8196c78746.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="./images/logoo.png">
    <link rel="stylesheet" href="coffee-landing.css?v=<?php echo time(); ?>">
    <script>
        // Dark Mode - Inline to ensure it loads first
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
            updateDarkModeIcon();
        }

        function updateDarkModeIcon() {
            const icon = document.getElementById('darkModeIcon');
            if (icon) {
                icon.src = document.body.classList.contains('dark-mode') ? 'images/lightmode.png' : 'images/darkmode.png';
                icon.style.width = '24px';
                icon.style.height = '24px';
                icon.style.objectFit = 'contain';
                icon.style.filter = 'brightness(0.9)';
            }
        }

        // Apply saved preference immediately
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.documentElement.classList.add('dark-mode');
            document.body.classList.add('dark-mode');
        }

        // Update icon after page loads
        window.addEventListener('load', function() {
            updateDarkModeIcon();
        });
    </script>
    <script src="common.js" defer></script>
    <style>
        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            position: relative;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            min-width: 200px;
            z-index: 1000;
            overflow: hidden;
        }

        .dropdown-menu.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .dropdown-header p {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
        }

        .dropdown-header strong {
            font-size: 14px;
            color: #2c1810;
        }

        .dropdown-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #2c1810;
            text-decoration: none;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background: #fff7ed;
            color: #d4b896;
        }

        .dropdown-item.logout {
            color: #dc2626;
            border-top: 1px solid #e5e7eb;
        }

        .dropdown-item.logout:hover {
            background: #fee2e2;
        }

        /* Cart Dropdown */
        .cart-dropdown {
            position: relative;
        }

        .cart-btn {
            position: relative;
            cursor: pointer;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc2626;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
        }

        .cart-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            min-width: 350px;
            max-width: 400px;
            z-index: 1000;
            max-height: 500px;
            overflow-y: auto;
        }

        .cart-menu.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .cart-header {
            padding: 20px;
            border-bottom: 2px solid #e5e7eb;
            background: #f9fafb;
        }

        .cart-header h3 {
            margin: 0;
            font-size: 18px;
            color: #2c1810;
        }

        .cart-items {
            padding: 15px;
            max-height: 300px;
            overflow-y: auto;
        }

        .cart-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            align-items: center;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            color: #2c1810;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .cart-item-price {
            color: #d4b896;
            font-weight: 600;
            font-size: 14px;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 5px;
        }

        .qty-btn {
            width: 24px;
            height: 24px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover {
            background: #f9fafb;
        }

        .cart-footer {
            padding: 20px;
            border-top: 2px solid #e5e7eb;
            background: #f9fafb;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 700;
            color: #2c1810;
        }

        .checkout-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #d4b896 0%, #c9964c 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(217, 119, 6, 0.3);
        }

        .empty-cart {
            padding: 40px;
            text-align: center;
            color: #6b7280;
        }

        .empty-cart-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .cart-menu {
                min-width: 300px;
                right: -50px;
            }

            .dropdown-menu {
                right: -20px;
            }
        }

        @media (max-width: 480px) {
            .cart-menu {
                min-width: 280px;
                max-width: 90vw;
                right: -80px;
            }

            .dropdown-menu {
                min-width: 180px;
                right: -30px;
            }

            .cart-item-image {
                width: 50px;
                height: 50px;
            }

            .cart-item-name {
                font-size: 13px;
            }

            .cart-item-price {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="images/logoo.png" alt="The Malvar Bat Cave Cafe Logo">
                <span>The Malvar Bat <span class="tique">Cave Cafe</span></span>
            </div>
            <ul class="nav-links">
                <li><a href="coffee-landing.php" class="active">Home</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="special-menu.php">Menu</a></li>
            </ul>
            <div class="nav-actions">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search menu items..." onkeyup="searchMenu()">
                    <button class="search-btn"><img src="images/search.png" alt="Search"></button>
                </div>

                <!-- Cart Dropdown -->
                <div class="cart-dropdown">
                    <button class="cart-btn" onclick="toggleCart()">
                        <i class="fa-solid fa-cart-shopping fa-2xl"></i>
                        <?php if (count($_SESSION['cart']) > 0): ?>
                            <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="cart-menu" id="cartMenu">
                        <div class="cart-header">
                            <h3>Shopping Cart</h3>
                        </div>
                        <?php if (empty($_SESSION['cart'])): ?>
                            <div class="empty-cart">
                                <div class="empty-cart-icon">🛒</div>
                                <p>Your cart is empty</p>
                            </div>
                        <?php else: ?>
                            <div class="cart-items">
                                <?php
                                $total = 0;
                                foreach ($_SESSION['cart'] as $index => $item):
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                    <div class="cart-item">
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-image">
                                        <div class="cart-item-details">
                                            <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <div class="cart-item-price">₱<?php echo number_format($item['price'], 0); ?></div>
                                            <div class="cart-item-quantity">
                                                <button class="qty-btn" onclick="updateQuantity(<?php echo $index; ?>, -1)">-</button>
                                                <span><?php echo $item['quantity']; ?></span>
                                                <button class="qty-btn" onclick="updateQuantity(<?php echo $index; ?>, 1)">+</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="cart-footer">
                                <div class="cart-total">
                                    <span>Total:</span>
                                    <span>₱<?php echo number_format($total, 0); ?></span>
                                </div>
                                <button class="checkout-btn" onclick="checkout()">Proceed to Checkout</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown">
                    <button class="profile-btn" onclick="toggleProfile()"><i class="fa-solid fa-user fa-2xl"></i></button>
                    <div class="dropdown-menu" id="profileMenu">
                        <div class="dropdown-header">
                            <strong><?php echo isset($_SESSION['adminUsername']) ? 'Admin' : 'User'; ?></strong>
                            <p>Welcome back!</p>
                        </div>
                        <a href="coffee-landing.php" class="dropdown-item">🏠 Home</a>
                        <a href="special-menu.php" class="dropdown-item">📋 Menu</a>
                        <a href="booking.php" class="dropdown-item">📅 Bookings</a>
                        <?php if (isset($_SESSION['isAdmin'])): ?>
                            <a href="admin-dashboard.php" class="dropdown-item">⚙️ Dashboard</a>
                        <?php endif; ?>
                        <button onclick="logout()" class="dropdown-item logout">🚪 Logout</button>
                    </div>
                </div>

                <!-- Dark Mode Toggle -->
                <button type="button" class="dark-mode-btn" id="darkModeBtn" onclick="toggleDarkMode(); return false;" title="Switch to Dark Mode">
                    <i id="darkModeIcon" class="fa-solid fa-moon fa-2xl"></i>
                </button>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="coffee-beans">
            <span class="bean bean1">☕</span>
            <span class="bean bean2">☕</span>
            <span class="bean bean3">☕</span>
            <span class="bean bean4">☕</span>
            <span class="bean bean5">☕</span>
            <span class="bean bean6">☕</span>
        </div>

        <div class="hero-content">
            <div class="hero-text">
                <h1>The Malvar Bat <span class="highlight">Cave Cafe</span></h1>
                <p>The premier late-night study, social, and coffee spot near the Batangas State University Malvar Campus. Your perfect place for studying, socializing, and enjoying premium coffee.</p>
                <div class="hero-buttons">
                    <button class="btn-primary" onclick="window.location.href='booking.php'">Book Now 📅</button>
                    <a href="#bestseller" class="btn-bestseller">Explore Best Sellers ⬇️</a>
                </div>
            </div>

            <div class="hero-image">
                <div class="coffee-cup">
                    <img src="images/commercialcoffee-png.png" alt="Iced Bat Brew">
                    <div class="tag tag-cappuccino">The Bat Brew</div>
                    <div class="tag tag-rating">4.8 ⭐</div>
                    <div class="tag tag-sales">18K</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Now Section -->
    <section class="popular" id="bestseller">
        <h2>Popular Now</h2>
        <div class="coffee-beans-bottom">
            <span class="bean">☕</span>
            <span class="bean">☕</span>
            <span class="bean">☕</span>
        </div>

        <div class="products-grid">
            <!-- Product 1 -->
            <a href="#" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                <div class="relative overflow-hidden">
                    <img src="images/iced salted spanish latte.jpg" alt="Iced Salted Spanish Latte" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                </div>
                <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                    <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;">Iced Salted Spanish Latte</h3>
                    <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;">Rich espresso with smooth cold milk and a hint of salt</p>
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                        <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.8</span>
                        <span>Iced Refreshment</span>
                    </div>
                    <form method="POST" style="margin-top: 1.5rem;">
                        <input type="hidden" name="product_name" value="Iced Salted Spanish Latte">
                        <input type="hidden" name="product_price" value="150">
                        <input type="hidden" name="product_image" value="images/iced salted spanish latte.jpg">
                        <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                            Add to Cart <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </form>
                </div>
            </a>

            <!-- Product 2 -->
            <a href="#" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                <div class="relative overflow-hidden">
                    <img src="images/vanilla latte.png" alt="Vanilla Latte" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                </div>
                <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                    <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;">Vanilla Latte</h3>
                    <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;">Smooth latte with delicate vanilla flavor and creamy texture</p>
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                        <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.8</span>
                        <span>Classic Favorite</span>
                    </div>
                    <form method="POST" style="margin-top: 1.5rem;">
                        <input type="hidden" name="product_name" value="Vanilla Latte">
                        <input type="hidden" name="product_price" value="120">
                        <input type="hidden" name="product_image" value="images/vanilla latte.jpg">
                        <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                            Add to Cart <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </form>
                </div>
            </a>

            <!-- Product 3 -->
            <a href="#" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                <div class="relative overflow-hidden">
                    <img src="images/iced caramel latte.jpg" alt="Iced Caramel Latte" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                </div>
                <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                    <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;">Iced Caramel Latte</h3>
                    <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;">Cool and sweet caramel latte perfect for hot days</p>
                    <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                        <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.8</span>
                        <span>Sweet Caramel</span>
                    </div>
                    <form method="POST" style="margin-top: 1.5rem;">
                        <input type="hidden" name="product_name" value="Iced Caramel Latte">
                        <input type="hidden" name="product_price" value="130">
                        <input type="hidden" name="product_image" value="images/iced caramel latte.jpg">
                        <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                            Add to Cart <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </form>
                </div>
            </a>
        </div>
    </section>

    <!-- Professional Footer Section -->
    <footer class="footer-main">
        <div class="footer-container">
            <!-- Main Footer Content -->
            <div class="footer-grid">
                <!-- Brand Section -->
                <div class="footer-brand">
                    <div class="footer-logo-group">
                        <img src="images/logo.png" alt="The Malvar Bat Cave Cafe Logo">
                        <span class="footer-brand-name">The Malvar Bat<br>Cave Cafe</span>
                    </div>
                    <p class="footer-description">
                        The premier late-night study, social, and coffee spot near BatStateU Malvar Campus. Your sanctuary for exceptional brews and warm connections.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h3 class="footer-heading">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="coffee-landing.php">Home</a></li>
                        <li><a href="special-menu.php">Menu</a></li>
                        <li><a href="booking.php">Booking</a></li>
                    </ul>
                </div>

                <!-- Information -->
                <div class="footer-column">
                    <h3 class="footer-heading">Information</h3>
                    <ul class="footer-links">
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#careers">Careers</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#privacy">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="footer-column">
                    <h3 class="footer-heading">Contact & Hours</h3>
                    <p class="footer-contact">📍 Malvar, Batangas State University Area</p>
                    <p class="footer-contact">📞 09636996688</p>
                    <p class="footer-contact">📧 info@malvarbatcavecafe.com</p>
                    <p class="footer-contact">⏰ Mon-Fri: 8:00 AM - 9:00 PM</p>
                    <p class="footer-contact">⏰ Sat-Sun: 9:00 AM - 10:00 PM</p>
                </div>
            </div>

            <!-- Footer Divider -->
            <div class="footer-divider"></div>

            <!-- Bottom Section -->
            <div class="footer-bottom">
                <p class="footer-copyright">
                    © 2025 The Malvar Bat Cave Cafe. All rights reserved.
                </p>

                <!-- Social Links -->
                <div class="footer-social">
                    <a href="#" aria-label="Facebook">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="Twitter">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                    <a href="#" aria-label="TikTok">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Toggle Profile Dropdown
        function toggleProfile() {
            const menu = document.getElementById('profileMenu');
            const cartMenu = document.getElementById('cartMenu');
            menu.classList.toggle('active');
            cartMenu.classList.remove('active');
        }

        // Toggle Cart Dropdown
        function toggleCart() {
            const menu = document.getElementById('cartMenu');
            const profileMenu = document.getElementById('profileMenu');
            menu.classList.toggle('active');
            profileMenu.classList.remove('active');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.querySelector('.profile-dropdown');
            const cartDropdown = document.querySelector('.cart-dropdown');
            const profileMenu = document.getElementById('profileMenu');
            const cartMenu = document.getElementById('cartMenu');

            if (!profileDropdown.contains(event.target)) {
                profileMenu.classList.remove('active');
            }

            if (!cartDropdown.contains(event.target)) {
                cartMenu.classList.remove('active');
            }
        });

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'login.php?logout=1';
            }
        }

        // Update cart quantity
        function updateQuantity(index, change) {
            fetch('update-cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `index=${index}&change=${change}`
                })
                .then(() => {
                    location.reload();
                });
        }

        // Checkout
        function checkout() {
            alert('Checkout functionality will be implemented soon!');
            // window.location.href = 'checkout.php';
        }
    </script>
</body>

</html>