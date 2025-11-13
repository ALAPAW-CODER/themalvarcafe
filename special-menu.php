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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
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

    header('Location: special-menu.php');
    exit();
}

// Menu items data
$menuItems = [
    'frappe' => [
        ['name' => 'Dirty Matcha Latte', 'price' => 140, 'image' => 'images/dirtymatchalattew.png', 'desc' => 'Matcha with espresso shot, topped with whipped cream'],
        ['name' => 'Matcha', 'price' => 120, 'image' => 'images/matcha.jpg', 'desc' => 'Pure matcha latte blended with ice'],
        ['name' => 'Mocha Frappe', 'price' => 185, 'image' => 'images/mocha frappe.jpg', 'desc' => 'A rich blend of coffee, chocolate, and milk blended with ice, topped with whipped cream. Perfect for chocolate and coffee lovers alike.'],
        ['name' => 'Caramel Frappe', 'price' => 180, 'image' => 'images/caramel frappe.jpg', 'desc' => 'Sweet caramel sauce swirled into a creamy coffee blend, finished with whipped cream and a drizzle of caramel on top.'],
        ['name' => 'Java Chip Frappe', 'price' => 190, 'image' => 'images/java chip frappe.jpg', 'desc' => 'A chocolatey mix of coffee, milk, and chocolate chips — blended smooth and topped with whipped cream and mocha drizzle.'],
        ['name' => 'Matcha Green Tea Frappe', 'price' => 195, 'image' => 'images/matcha green tea frappe.jpg', 'desc' => 'A refreshing fusion of earthy matcha green tea and creamy milk, perfectly blended and lightly sweetened.'],
        ['name' => 'Strawberries & Cream Frappe', 'price' => 185, 'image' => 'images/strawberries and cream frappe.jpg', 'desc' => 'Sweet and creamy strawberry goodness in every sip — a refreshing, non-coffee option for a fruity treat.'],
    ],
    'icedcoffee' => [
        ['name' => 'Iced Americano', 'price' => 100, 'image' => 'images/iced americano.jpg', 'desc' => 'Strong and refreshing espresso over ice'],
        ['name' => 'Iced Cappucino', 'price' => 110, 'image' => 'images/iced cappucino.png', 'desc' => 'Cold cappucino with foam'],
        ['name' => 'Iced Bat Brew', 'price' => 150, 'image' => 'images/batbrew.png', 'desc' => 'Our signature blend served over ice'],
        ['name' => 'Iced Salted Spanish Latte', 'price' => 150, 'image' => 'images/iced salted spanish latte.jpg', 'desc' => 'Creamy Spanish latte with a touch of sea salt'],
        ['name' => 'Iced Caramel Latte', 'price' => 130, 'image' => 'images/iced caramel latte.jpg', 'desc' => 'Smooth latte with sweet caramel flavor'],
        ['name' => 'Vanilla Latte', 'price' => 120, 'image' => 'images/vanilla latte.png', 'desc' => 'Classic vanilla-flavored latte'],
    ],
    'pastries' => [
        ['name' => 'Banana Bread Slice', 'price' => 85, 'image' => 'images/banana bread.jpg', 'desc' => 'Moist, flavorful, and baked fresh with ripe bananas for that comforting homemade taste. A timeless favorite that pairs perfectly with coffee or tea.'],
        ['name' => 'Buttered Croissant', 'price' => 95, 'image' => 'images/buttered croissantt.jpg', 'desc' => 'Flaky, golden layers of buttery pastry baked to perfection. Simple, elegant, and best enjoyed warm with your favorite brew.'],
        ['name' => 'Red Velvet mini cake', 'price' => 120, 'image' => 'images/redvelvet.jpg', 'desc' => 'Soft, velvety layers with a hint of cocoa, topped with smooth cream cheese frosting. A sweet, classic indulgence to brighten your day.'],
    ],
    'snacks' => [
        ['name' => 'Nachos', 'price' => 155, 'image' => 'images/nachos.jpg', 'desc' => 'Crispy tortilla chips layered with melted cheese, savory beef, and tangy salsa. Perfect for sharing or pairing with your favorite iced drink.'],
        ['name' => 'Truffle Fries', 'price' => 160, 'image' => 'images/trufflefries.jpg', 'desc' => 'Crispy golden fries tossed in aromatic truffle oil and topped with parmesan cheese. A rich and indulgent twist on a classic favorite.'],
        ['name' => 'Mini Hotdog Bites', 'price' => 135, 'image' => 'images/minihotdogbites.jpg', 'desc' => 'Bite-sized, juicy hotdogs wrapped in soft pastry rolls. A fun, savory snack that\'s great for quick bites or sharing with friends over coffee.'],
        ['name' => 'Potato Wedges', 'price' => 120, 'image' => 'images/potatowedges.jpg', 'desc' => 'Thick-cut and seasoned to perfection, our potato wedges are crispy on the outside and fluffy inside — a satisfying snack for any time of day.'],
        ['name' => 'Mozzarella Sticks', 'price' => 150, 'image' => 'images/mozarellasticks.jpg', 'desc' => 'Crispy on the outside, soft and gooey on the inside. These golden mozzarella sticks are perfect for sharing and pair deliciously with marinara dip.'],
    ],
    'meals' => [
        ['name' => 'Creamy Carbonara', 'price' => 185, 'image' => 'images/creamy carbonara.jpg', 'desc' => 'Rich and savory pasta coated in a creamy white sauce with bacon bits and parmesan.'],
        ['name' => 'Spaghetti', 'price' => 175, 'image' => 'images/spaghetti.jpg', 'desc' => 'Classic Filipino-style sweet and savory spaghetti topped with ground meat and cheese.'],
        ['name' => 'Bulgogi Rice Bowl', 'price' => 195, 'image' => 'images/bulgogi rice bowl.jpg', 'desc' => 'Korean-inspired beef slices marinated in sweet soy-garlic sauce, served over steamed rice.'],
        ['name' => 'Chicken Fillet Rice Bowl', 'price' => 185, 'image' => 'images/chicken fillet.jpg', 'desc' => 'Crispy golden chicken fillet paired with rice and a side of special house gravy.'],
        ['name' => 'Chicken Teriyaki Rice Bowl', 'price' => 195, 'image' => 'images/chicken teriyaki rice.jpg', 'desc' => 'Grilled chicken glazed with teriyaki sauce, served with steamed rice and vegetables.'],
        ['name' => 'Tuna Sandwich', 'price' => 145, 'image' => 'images/Tuna sandwich.jpg', 'desc' => 'Classic tuna spread mixed with mayo, celery, and spices, served on toasted bread.'],
        ['name' => 'Chicken Sandwich', 'price' => 155, 'image' => 'images/chicken sandwich.jpg', 'desc' => 'Tender shredded chicken in creamy dressing with lettuce and tomato on soft bread.'],
        ['name' => 'Caesar Salad', 'price' => 165, 'image' => 'images/caesar salad.jpg', 'desc' => 'Crisp romaine lettuce, parmesan, croutons, and Caesar dressing for a timeless favorite.'],
        ['name' => 'Kani Salad', 'price' => 175, 'image' => 'images/kani salad.jpg', 'desc' => 'Japanese-style salad with crab sticks, cucumber, mango, and creamy sesame dressing.'],
    ],
    'hot-beverages' => [
        ['name' => 'Hot Espresso', 'price' => 75, 'image' => 'images/espresso.jpg', 'desc' => 'Rich and bold single or double shot of pure espresso. Perfect for espresso enthusiasts.'],
        ['name' => 'Hot Americano', 'price' => 85, 'image' => 'images/americano.jpg', 'desc' => 'Bold espresso diluted with hot water for a smooth, full-bodied cup.'],
        ['name' => 'Hot Cappuccino', 'price' => 95, 'image' => 'images/cappuccino.jpg', 'desc' => 'Perfect balance of espresso, steamed milk, and velvety foam topped.'],
        ['name' => 'Hot Latte', 'price' => 100, 'image' => 'images/latte.jpg', 'desc' => 'Smooth and creamy espresso with steamed milk and a touch of foam.'],
        ['name' => 'Hot Macchiato', 'price' => 90, 'image' => 'images/macchiato.jpg', 'desc' => 'Espresso "marked" with a dollop of steamed milk foam for a strong, bold taste.'],
        ['name' => 'Hot Mocha', 'price' => 105, 'image' => 'images/mocha.jpg', 'desc' => 'Rich espresso combined with steamed milk and chocolate, topped with whipped cream.'],
        ['name' => 'Hot Spanish Latte', 'price' => 105, 'image' => 'images/spanish latte.jpg', 'desc' => 'Creamy latte with a touch of sea salt and caramel for a unique flavor.'],
        ['name' => 'Hot Matcha Latte', 'price' => 110, 'image' => 'images/matcha.jpg', 'desc' => 'Smooth and creamy matcha green tea latte, lightly sweetened and beautifully frothy.'],
    ],
    'iced-beverages' => [
        ['name' => 'Iced Espresso', 'price' => 80, 'image' => 'images/iced espresso.jpg', 'desc' => 'Bold espresso shots poured over ice for an intense, refreshing kick.'],
        ['name' => 'Iced Americano', 'price' => 90, 'image' => 'images/iced americano.jpg', 'desc' => 'Strong and refreshing espresso over ice with a smooth finish.'],
        ['name' => 'Iced Cappuccino', 'price' => 100, 'image' => 'images/iced cappuccino.png', 'desc' => 'Cold cappuccino with ice and foam for a refreshing twist.'],
        ['name' => 'Iced Latte', 'price' => 105, 'image' => 'images/iced latte.jpg', 'desc' => 'Smooth and creamy iced latte perfect for hot days.'],
        ['name' => 'Iced Mocha', 'price' => 110, 'image' => 'images/iced mocha.jpg', 'desc' => 'Cold blend of espresso, milk, chocolate, and ice for a delicious treat.'],
        ['name' => 'Iced Bat Brew', 'price' => 150, 'image' => 'images/batbrew.png', 'desc' => 'Our signature bold dark roast served over ice with smooth, rich notes.'],
        ['name' => 'Iced Salted Spanish Latte', 'price' => 150, 'image' => 'images/iced salted spanish latte.jpg', 'desc' => 'Creamy iced Spanish latte with a touch of sea salt and caramel.'],
        ['name' => 'Iced Caramel Latte', 'price' => 130, 'image' => 'images/iced caramel latte.jpg', 'desc' => 'Smooth iced latte with sweet caramel flavor and creamy milk.'],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Menu - The Malvar Bat Cave Cafe</title>
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
                // When dark mode is enabled, show sun icon (lightmode.png)
                // When dark mode is disabled, show moon icon (darkmode.png)
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
        /* Include the same dropdown styles as coffee-landing.php */
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
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="images/logo.png" alt="The Malvar Bat Cave Cafe Logo">
                <span>The Malvar Bat <span class="tique">Cave Cafe</span></span>
            </div>
            <ul class="nav-links">
                <li><a href="coffee-landing.php">Home</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="special-menu.php" class="active">Menu</a></li>
            </ul>
            <div class="nav-actions">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search menu items..." onkeyup="searchMenu()">
                    <button class="search-btn"><img src="images/search.png" alt="Search"></button>
                </div>

                <!-- Cart Dropdown -->
                <div class="cart-dropdown">
                    <button class="cart-btn" onclick="toggleCart()">
                        <img src="images/shoppingcart.png" alt="Cart">
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
                    <button class="profile-btn" onclick="toggleProfile()"><img src="images/user.png" alt="Profile"></button>
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
                    <img id="darkModeIcon" src="images/darkmode.png" alt="Dark Mode" style="width: 24px; height: 24px; object-fit: contain;">
                </button>
            </div>
        </nav>
    </header>

    <!-- Special Menu Section -->
    <section class="special-menu-section">
        <div class="container">
            <h2 class="section-title">Menu</h2>

            <!-- Category Filter Buttons -->
            <div class="category-filters">
                <button class="filter-btn active" onclick="filterCategory('all')">All</button>
                <button class="filter-btn" onclick="filterCategory('coffee')">Coffee</button>
                <button class="filter-btn" onclick="filterCategory('pastries')">Pastries</button>
                <button class="filter-btn" onclick="filterCategory('snacks')">Snacks</button>
                <button class="filter-btn" onclick="filterCategory('meals')">Meals</button>
                <button class="filter-btn" onclick="filterCategory('hot-beverages')">Hot Beverages</button>
                <button class="filter-btn" onclick="filterCategory('iced-beverages')">Iced Beverages</button>
            </div>

            <!-- Frappe Section -->
            <h3 class="category-title" data-category="coffee iced-beverages">Frappe</h3>
            <div class="menu-grid" data-category="coffee iced-beverages">
                <?php foreach ($menuItems['frappe'] as $item): ?>
                    <a href="#" data-category="coffee" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                        <div class="relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                        </div>
                        <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                            <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;"><?php echo htmlspecialchars($item['desc']); ?></p>
                            <div class="mt-4 flex flex-wrap gap-2" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <button class="price-option px-3 py-1 text-xs font-medium bg-[#FAF3E0] rounded-full hover:bg-[#E2D6C2]" style="padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 500; background-color: #FAF3E0; border-radius: 9999px; border: none; cursor: pointer; transition: background 0.3s;">M - ₱<?php echo $item['price']; ?></button>
                            </div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                                <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.9</span>
                                <span>Premium Blend</span>
                            </div>
                            <form method="POST" style="margin-top: 1.5rem;">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                                <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                                    Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Iced Coffee Section -->
            <h3 class="category-title" data-category="coffee iced-beverages">Iced Coffee</h3>
            <div class="menu-grid" data-category="coffee iced-beverages">
                <?php foreach ($menuItems['icedcoffee'] as $item): ?>
                    <a href="#" data-category="coffee" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                        <div class="relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                        </div>
                        <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                            <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;"><?php echo htmlspecialchars($item['desc']); ?></p>
                            <div class="mt-4 flex flex-wrap gap-2" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <button class="price-option px-3 py-1 text-xs font-medium bg-[#FAF3E0] rounded-full hover:bg-[#E2D6C2]" style="padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 500; background-color: #FAF3E0; border-radius: 9999px; border: none; cursor: pointer; transition: background 0.3s;">M - ₱<?php echo $item['price']; ?></button>
                            </div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                                <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.9</span>
                                <span>Rich Aroma</span>
                            </div>
                            <form method="POST" style="margin-top: 1.5rem;">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                                <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                                    Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Pastries Section -->
            <h3 class="category-title" data-category="pastries">Pastries</h3>
            <div class="menu-grid" data-category="pastries">
                <?php foreach ($menuItems['pastries'] as $item): ?>
                    <a href="#" data-category="pastries" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                        <div class="relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                        </div>
                        <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                            <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;"><?php echo htmlspecialchars($item['desc']); ?></p>
                            <div class="mt-4 flex flex-wrap gap-2" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <button class="price-option px-3 py-1 text-xs font-medium bg-[#FAF3E0] rounded-full hover:bg-[#E2D6C2]" style="padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 500; background-color: #FAF3E0; border-radius: 9999px; border: none; cursor: pointer; transition: background 0.3s;">₱<?php echo $item['price']; ?></button>
                            </div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                                <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.9</span>
                                <span>Freshly Baked</span>
                            </div>
                            <form method="POST" style="margin-top: 1.5rem;">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                                <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                                    Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Snacks Section -->
            <h3 class="category-title" data-category="snacks">Snacks</h3>
            <div class="menu-grid" data-category="snacks">
                <?php foreach ($menuItems['snacks'] as $item): ?>
                    <a href="#" data-category="snacks" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                        <div class="relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                        </div>
                        <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                            <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;"><?php echo htmlspecialchars($item['desc']); ?></p>
                            <div class="mt-4 flex flex-wrap gap-2" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <button class="price-option px-3 py-1 text-xs font-medium bg-[#FAF3E0] rounded-full hover:bg-[#E2D6C2]" style="padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 500; background-color: #FAF3E0; border-radius: 9999px; border: none; cursor: pointer; transition: background 0.3s;">₱<?php echo $item['price']; ?></button>
                            </div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                                <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.8</span>
                                <span>Savory Delight</span>
                            </div>
                            <form method="POST" style="margin-top: 1.5rem;">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                                <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                                    Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Meals Section -->
            <h3 class="category-title" data-category="meals">Pasta & Rice Bowls</h3>
            <div class="menu-grid" data-category="meals">
                <?php foreach ($menuItems['meals'] as $item): ?>
                    <a href="#" data-category="meals" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                        <div class="relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                        </div>
                        <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                            <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;"><?php echo htmlspecialchars($item['desc']); ?></p>
                            <div class="mt-4 flex flex-wrap gap-2" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <button class="price-option px-3 py-1 text-xs font-medium bg-[#FAF3E0] rounded-full hover:bg-[#E2D6C2]" style="padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 500; background-color: #FAF3E0; border-radius: 9999px; border: none; cursor: pointer; transition: background 0.3s;">₱<?php echo $item['price']; ?></button>
                            </div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                                <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.9</span>
                                <span>Hearty Meal</span>
                            </div>
                            <form method="POST" style="margin-top: 1.5rem;">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                                <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                                    Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Hot Beverages Section -->
            <h3 class="category-title" data-category="hot-beverages">Hot Beverages</h3>
            <div class="menu-grid" data-category="hot-beverages">
                <?php foreach ($menuItems['hot-beverages'] as $item): ?>
                    <a href="#" data-category="hot-beverages" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                        <div class="relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                        </div>
                        <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                            <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;"><?php echo htmlspecialchars($item['desc']); ?></p>
                            <div class="mt-4 flex flex-wrap gap-2" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <button class="price-option px-3 py-1 text-xs font-medium bg-[#FAF3E0] rounded-full hover:bg-[#E2D6C2]" style="padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 500; background-color: #FAF3E0; border-radius: 9999px; border: none; cursor: pointer; transition: background 0.3s;">₱<?php echo $item['price']; ?></button>
                            </div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                                <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.9</span>
                                <span>Warm Comfort</span>
                            </div>
                            <form method="POST" style="margin-top: 1.5rem;">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                                <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                                    Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Iced Beverages Section -->
            <h3 class="category-title" data-category="iced-beverages">Iced Beverages</h3>
            <div class="menu-grid" data-category="iced-beverages">
                <?php foreach ($menuItems['iced-beverages'] as $item): ?>
                    <a href="#" data-category="iced-beverages" class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white/90 shadow ring-1 ring-[#D7A86E]/20 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:ring-[#C9964C]/30" style="text-decoration: none; color: inherit;">
                        <div class="relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="aspect-4/5 w-full mt-9 object-cover transition duration-500 group-hover:scale-105" style="height: 350px; object-fit: cover;">
                        </div>
                        <div class="flex flex-1 flex-col p-6" style="display: flex; flex-direction: column; flex: 1; padding: 1.5rem;">
                            <h3 class="text-lg font-semibold text-[#2B1A12]" style="font-size: 1.125rem; font-weight: 600; color: #2B1A12;"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <p class="mt-1 text-sm text-[#6F4E37]" style="margin-top: 0.25rem; font-size: 0.875rem; color: #6F4E37; line-height: 1.5;"><?php echo htmlspecialchars($item['desc']); ?></p>
                            <div class="mt-4 flex flex-wrap gap-2" style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <button class="price-option px-3 py-1 text-xs font-medium bg-[#FAF3E0] rounded-full hover:bg-[#E2D6C2]" style="padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 500; background-color: #FAF3E0; border-radius: 9999px; border: none; cursor: pointer; transition: background 0.3s;">₱<?php echo $item['price']; ?></button>
                            </div>
                            <div class="mt-6 flex items-center gap-2 text-xs font-medium uppercase text-[#A37A58]" style="margin-top: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 500; text-transform: uppercase; color: #A37A58;">
                                <span class="flex items-center gap-1 text-[#C9964C]" style="display: flex; align-items: center; gap: 0.25rem; color: #C9964C;">★ 4.9</span>
                                <span>Cool Refreshment</span>
                            </div>
                            <form method="POST" style="margin-top: 1.5rem;">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($item['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $item['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                                <button type="submit" name="add_to_cart" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full bg-[#2B1A12] px-5 py-2 text-sm font-medium text-[#FAF3E0] transition hover:bg-[#1F120B] w-full" style="margin-top: 1.5rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 9999px; background-color: #2B1A12; padding: 0.5rem 1.25rem; font-size: 0.875rem; font-weight: 500; color: #FAF3E0; border: none; cursor: pointer; transition: background 0.3s; width: 100%;">
                                    Add to Cart <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script>
        function toggleProfile() {
            const menu = document.getElementById('profileMenu');
            const cartMenu = document.getElementById('cartMenu');
            menu.classList.toggle('active');
            cartMenu.classList.remove('active');
        }

        function toggleCart() {
            const menu = document.getElementById('cartMenu');
            const profileMenu = document.getElementById('profileMenu');
            menu.classList.toggle('active');
            profileMenu.classList.remove('active');
        }

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

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'login.php?logout=1';
            }
        }

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

        function checkout() {
            alert('Checkout functionality will be implemented soon!');
        }

        // Category Filter Function
        function filterCategory(category) {
            const allItems = document.querySelectorAll('[data-category]');
            const filterBtns = document.querySelectorAll('.filter-btn');

            // Update active button
            filterBtns.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Show/hide items based on category
            allItems.forEach(item => {
                if (category === 'all') {
                    item.style.display = '';
                } else {
                    const itemCategories = item.getAttribute('data-category').split(' ');
                    if (itemCategories.includes(category)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        }
    </script>
</body>

</html>