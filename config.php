<?php
// Configuration File for The Malvar Bat Cave Cafe

// Site Information
define('SITE_NAME', 'The Malvar Bat Cave Cafe');
define('SITE_TAGLINE', 'The premier late-night study, social, and coffee spot near BatStateU Malvar Campus');
define('SITE_EMAIL', 'info@malvarbatcavecafe.com');
define('SITE_PHONE', '09636996688');
define('SITE_ADDRESS', 'Malvar, Batangas State University Area');

// Business Hours
define('WEEKDAY_HOURS', 'Mon-Fri: 8:00 AM - 9:00 PM');
define('WEEKEND_HOURS', 'Sat-Sun: 9:00 AM - 10:00 PM');

// Admin Credentials (Change these for security!)
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

// Payment Information
define('GCASH_NUMBER', '09636996688');
define('GCASH_NAME', 'The Malvar Bat Cave Cafe');

// Session Configuration
ini_set('session.gc_maxlifetime', 3600); // 1 hour
session_set_cookie_params(3600);

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Manila');

// Helper Functions
function formatPrice($price)
{
    return '₱' . number_format($price, 0);
}

function formatDate($date)
{
    return date('M d, Y', strtotime($date));
}

function formatTime($time)
{
    return date('h:i A', strtotime($time));
}

function generateReservationId($count = 0)
{
    return 'TMBC-' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT) . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
}

// Security Functions
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isLoggedIn()
{
    return isset($_SESSION['isLoggedIn']) || isset($_SESSION['isAdmin']);
}

function isAdmin()
{
    return isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] === true;
}

function redirectIfNotLoggedIn()
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function redirectIfNotAdmin()
{
    if (!isAdmin()) {
        header('Location: login.php');
        exit();
    }
}
