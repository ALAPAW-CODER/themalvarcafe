<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['isLoggedIn']) && !isset($_SESSION['isAdmin'])) {
    header('Location: login.php');
    exit();
}

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Initialize bookings storage if not exists
if (!isset($_SESSION['bookings'])) {
    $_SESSION['bookings'] = [];
}

// Handle booking submission
$booking_success = false;
$reservation_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {
    // Generate reservation ID
    $reservation_id = 'TMBC-' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT) . '-' . str_pad(count($_SESSION['bookings']) + 1, 3, '0', STR_PAD_LEFT);

    $booking = [
        'id' => $reservation_id,
        'name' => $_POST['fullName'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'type' => $_POST['bookingType'],
        'date' => $_POST['date'],
        'time' => $_POST['time'],
        'persons' => $_POST['guests'],
        'requests' => $_POST['specialRequests'] ?? '',
        'status' => 'pending',
        'payment' => 'GCash - Pending',
        'created_at' => date('Y-m-d H:i:s')
    ];

    $_SESSION['bookings'][] = $booking;
    $booking_success = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - The Malvar Bat Cave Cafe</title>
    <link rel="icon" type="image/png" href="./images/logoo.png">
    <link rel="stylesheet" href="coffee-landing.css?v=<?php echo time(); ?>">
    <script>
        // Dark Mode - Inline to ensure it loads first
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled');
            updateDarkModeIcon();
            console.log('Dark mode toggled:', document.body.classList.contains('dark-mode'));
        }

        function updateDarkModeIcon() {
            const icon = document.getElementById('darkModeIcon');
            if (icon) {
                const isDark = document.body.classList.contains('dark-mode');
                icon.src = isDark ? 'images/lightmode.png' : 'images/darkmode.png';
                icon.style.width = '24px';
                icon.style.height = '24px';
                icon.style.objectFit = 'contain';
                icon.style.filter = 'brightness(0.9)';
                console.log('Icon updated to:', icon.src);
            } else {
                console.error('Dark mode icon not found!');
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
            console.log('Page loaded, dark mode active:', document.body.classList.contains('dark-mode'));
        });
    </script>
    <script src="common.js" defer></script>
    <style>
        /* Include dropdown styles */
        .profile-dropdown,
        .cart-dropdown {
            position: relative;
        }

        .profile-btn,
        .cart-btn {
            position: relative;
            cursor: pointer;
        }

        .dropdown-menu,
        .cart-menu {
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

        .dropdown-menu.active,
        .cart-menu.active {
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
            min-width: 350px;
            max-width: 400px;
            max-height: 500px;
            overflow-y: auto;
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

        /* Success Modal */
        .success-modal {
            display: <?php echo $booking_success ? 'flex' : 'none'; ?>;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .success-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            max-width: 500px;
            text-align: center;
        }

        .success-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .success-content h2 {
            color: #10b981;
            margin-bottom: 15px;
        }

        .reservation-id-box {
            background: #f0fdf4;
            border: 2px dashed #10b981;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .reservation-id-box strong {
            font-size: 24px;
            color: #10b981;
        }

        .success-btn {
            padding: 12px 30px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 20px;
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
        }

        /* Booking Page Background */
        .booking-section {
            background-image: url('images/interior2-malvar.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            min-height: 100vh;
            padding: 80px 0;
        }

        .booking-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .booking-section .container {
            position: relative;
            z-index: 2;
        }

        .booking-header h1 {
            color: white;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
        }

        .booking-header p {
            color: white;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.8);
        }

        /* Two Column Layout */
        .booking-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 40px;
        }

        .booking-form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .map-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
        }

        .map-container h3 {
            margin: 0 0 20px 0;
            color: #2c1810;
            font-size: 24px;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 15px;
            min-height: 600px;
        }

        @media (max-width: 1024px) {
            .booking-content {
                grid-template-columns: 1fr;
            }

            .map-container iframe {
                min-height: 400px;
            }
        }
    </style>
</head>

<body>
    <!-- Success Modal -->
    <div class="success-modal" id="successModal">
        <div class="success-content">
            <div class="success-icon">✅</div>
            <h2>Booking Confirmed!</h2>
            <p>Your reservation has been successfully created.</p>
            <div class="reservation-id-box">
                <p>Your Reservation ID</p>
                <strong><?php echo $reservation_id; ?></strong>
            </div>
            <p><small>Please save this ID for your records</small></p>
            <button class="success-btn" onclick="closeModal()">Continue</button>
        </div>
    </div>

    <!-- Header Section -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="images/logo.png" alt="The Malvar Bat Cave Cafe Logo">
                <span>The Malvar Bat <span class="tique">Cave Cafe</span></span>
            </div>
            <ul class="nav-links">
                <li><a href="coffee-landing.php">Home</a></li>
                <li><a href="booking.php" class="active">Booking</a></li>
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

    <!-- Booking Section -->
    <section class="booking-section">
        <div class="container">
            <div class="booking-header">
                <h1>Reserve Your Table</h1>
                <p>Book a table at The Malvar Bat Cave Cafe and enjoy our cozy atmosphere</p>
            </div>

            <div class="booking-content">
                <div class="booking-form-container">
                    <form class="booking-form" method="POST">
                        <!-- Booking Type First -->
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="bookingType">Booking Type *</label>
                                <select id="bookingType" name="bookingType" required>
                                    <option value="">Select booking type</option>
                                    <option value="studying">For Studying</option>
                                    <option value="event">For Event (Room Rental)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fullName">Full Name *</label>
                                <input type="text" id="fullName" name="fullName" required placeholder="Enter your full name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required placeholder="your.email@example.com">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required placeholder="+63 XXX XXX XXXX">
                            </div>
                            <div class="form-group">
                                <label for="guests">Number of Persons *</label>
                                <select id="guests" name="guests" required>
                                    <option value="">Select number of persons</option>
                                    <?php for ($i = 1; $i <= 20; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> Person<?php echo $i > 1 ? 's' : ''; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date">Date *</label>
                                <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="time">Time *</label>
                                <select id="time" name="time" required>
                                    <option value="">Select time</option>
                                    <option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option>
                                    <option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option>
                                    <option value="17:00">5:00 PM</option>
                                    <option value="18:00">6:00 PM</option>
                                    <option value="19:00">7:00 PM</option>
                                    <option value="20:00">8:00 PM</option>
                                    <option value="21:00">9:00 PM</option>
                                    <option value="22:00">10:00 PM</option>
                                    <option value="23:00">11:00 PM</option>
                                    <option value="00:00">12:00 AM</option>
                                    <option value="01:00">1:00 AM</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="specialRequests">Special Requests (Optional)</label>
                            <textarea id="specialRequests" name="specialRequests" rows="3" placeholder="Any dietary restrictions, seating preferences, or special requirements..."></textarea>
                        </div>

                        <!-- Payment Section -->
                        <div class="payment-section">
                            <h3>Payment Method</h3>
                            <div class="payment-gcash">
                                <div class="gcash-info">
                                    <img src="images/gcash-logo.png" alt="GCash" style="width: 120px; margin-bottom: 15px;" onerror="this.style.display='none'">
                                    <p><strong>GCash Number:</strong></p>
                                    <p class="gcash-number">09636996688</p>
                                    <button type="button" onclick="copyGcashNumber()" class="copy-btn">Copy Number</button>
                                </div>
                                <div class="qr-code">
                                    <p><strong>Scan QR Code:</strong></p>
                                    <img src="images/qrcode.png" alt="GCash QR Code" style="max-width: 250px; width: 80%;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="proofOfPayment">Upload Proof of Payment *</label>
                                <input type="file" id="proofOfPayment" name="proofOfPayment" accept="image/*" onchange="previewImage(this)">
                                <div id="imagePreview" style="margin-top: 15px; display: none;">
                                    <img id="preview" style="max-width: 100%; max-height: 300px; border-radius: 10px;">
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="submit_booking" class="submit-booking-btn">Submit Reservation</button>
                    </form>
                </div>

                <!-- Google Maps Container -->
                <div class="map-container">
                    <h3>Find Us</h3>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d964.9424934653596!2d121.16027016955353!3d14.042953599270142!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd5ce2fa04db89%3A0xa8e1e82d19c75c2e!2sBatangas%20State%20University%20-%20The%20National%20Engineering%20University%20Alangilan!5e0!3m2!1sen!2sph!4v1734836997371!5m2!1sen!2sph" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
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

            if (profileDropdown && !profileDropdown.contains(event.target)) {
                profileMenu.classList.remove('active');
            }

            if (cartDropdown && !cartDropdown.contains(event.target)) {
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

        function copyGcashNumber() {
            const number = '09636996688';
            navigator.clipboard.writeText(number).then(() => {
                alert('GCash number copied to clipboard!');
            });
        }

        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
        }
    </script>
</body>

</html>