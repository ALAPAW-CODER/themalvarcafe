<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['isAdmin'])) {
    header('Location: login.php');
    exit();
}

// Initialize bookings if not exists
if (!isset($_SESSION['bookings'])) {
    $_SESSION['bookings'] = [
        [
            'id' => 'TMBC-12345678-001',
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@email.com',
            'phone' => '09123456789',
            'type' => 'studying',
            'date' => '2025-11-15',
            'time' => '14:00',
            'persons' => 4,
            'status' => 'pending',
            'requests' => 'Need quiet area for group study',
            'payment' => 'GCash - Paid'
        ],
        [
            'id' => 'TMBC-12345678-002',
            'name' => 'Maria Santos',
            'email' => 'maria@email.com',
            'phone' => '09234567890',
            'type' => 'event',
            'date' => '2025-11-16',
            'time' => '18:00',
            'persons' => 15,
            'status' => 'confirmed',
            'requests' => 'Birthday celebration, need decorations',
            'payment' => 'GCash - Paid'
        ],
    ];
}

// Handle booking actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['booking_id'])) {
        $booking_id = $_POST['booking_id'];
        $action = $_POST['action'];

        foreach ($_SESSION['bookings'] as &$booking) {
            if ($booking['id'] === $booking_id) {
                if ($action === 'confirm') {
                    $booking['status'] = 'confirmed';
                } elseif ($action === 'cancel') {
                    $booking['status'] = 'cancelled';
                }
                break;
            }
        }

        header('Location: admin-dashboard.php');
        exit();
    }
}

$bookings = $_SESSION['bookings'];
$total = count($bookings);
$confirmed = count(array_filter($bookings, fn($b) => $b['status'] === 'confirmed'));
$pending = count(array_filter($bookings, fn($b) => $b['status'] === 'pending'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Malvar Bat Cave Cafe</title>
    <link rel="icon" type="image/png" href="./images/logoo.png">
    <link rel="stylesheet" href="coffee-landing.css">
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .admin-header {
            background: linear-gradient(135deg, #2c1810 0%, #4a3728 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .admin-header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .admin-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-title img {
            width: 50px;
            height: 50px;
        }

        .admin-title h1 {
            font-size: 24px;
            color: #d4b896;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: #d4b896;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #c9964c;
            transform: translateY(-2px);
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .stat-icon.blue {
            background: #dbeafe;
        }

        .stat-icon.green {
            background: #d1fae5;
        }

        .stat-icon.orange {
            background: #d4b896;
        }

        .stat-info h3 {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
        }

        .reservations-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-header h2 {
            font-size: 24px;
            color: #2c1810;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f9fafb;
        }

        th {
            text-align: left;
            padding: 15px;
            color: #6b7280;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: #d4b896;
            color: #92400e;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-confirm {
            background: #10b981;
            color: white;
        }

        .btn-confirm:hover {
            background: #059669;
        }

        .btn-cancel {
            background: #ef4444;
            color: white;
        }

        .btn-cancel:hover {
            background: #dc2626;
        }

        @media (max-width: 768px) {
            .admin-header {
                padding: 15px 20px;
            }

            .admin-title h1 {
                font-size: 18px;
            }

            .admin-title img {
                width: 40px;
                height: 40px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .reservations-section {
                padding: 20px;
            }

            table {
                font-size: 14px;
            }

            th,
            td {
                padding: 10px 8px;
            }
        }

        @media (max-width: 480px) {
            .dashboard-container {
                padding: 20px 10px;
            }

            .admin-header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }

            .stat-info p {
                font-size: 24px;
            }

            th,
            td {
                font-size: 12px;
                padding: 8px 5px;
            }
        }
    </style>
</head>

<body>
    <header class="admin-header">
        <div class="admin-header-content">
            <div class="admin-title">
                <img src="images/logo.png" alt="Logo">
                <h1>Admin Dashboard</h1>
            </div>
            <div class="admin-user">
                <!-- Dark Mode Toggle -->
                <button type="button" class="dark-mode-btn" id="darkModeBtn" onclick="toggleDarkMode(); return false;" title="Switch to Dark Mode">
                    <img id="darkModeIcon" src="images/darkmode.png" alt="Dark Mode" style="width: 24px; height: 24px; object-fit: contain;">
                </button>
                <span>👤 <strong><?php echo htmlspecialchars($_SESSION['adminUsername']); ?></strong></span>
                <a href="login.php?logout=1"><button class="logout-btn">Logout</button></a>
            </div>
        </div>
    </header>

    <div class="dashboard-container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">📅</div>
                <div class="stat-info">
                    <h3>Total Bookings</h3>
                    <p><?php echo $total; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">✅</div>
                <div class="stat-info">
                    <h3>Confirmed</h3>
                    <p><?php echo $confirmed; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">⏳</div>
                <div class="stat-info">
                    <h3>Pending</h3>
                    <p><?php echo $pending; ?></p>
                </div>
            </div>
        </div>

        <div class="reservations-section">
            <div class="section-header">
                <h2>Bookings & Reservations</h2>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Persons</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($booking['id']); ?></strong></td>
                                <td><?php echo htmlspecialchars($booking['name']); ?></td>
                                <td><?php echo $booking['type'] === 'studying' ? '📚 Studying' : '🎉 Event'; ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking['date'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($booking['time'])); ?></td>
                                <td><?php echo $booking['persons']; ?></td>
                                <td><span class="status-badge status-<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($booking['status'] === 'pending'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <input type="hidden" name="action" value="confirm">
                                                <button type="submit" class="action-btn btn-confirm">Confirm</button>
                                            </form>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <input type="hidden" name="action" value="cancel">
                                                <button type="submit" class="action-btn btn-cancel">Cancel</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>