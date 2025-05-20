<?php
include '../includes/navbar.php';
include '../includes/db.php';

// Fetch dynamic data for dashboard cards
$totalSales = 0;
$totalUsers = 0;
$totalRevenue = 0;

try {
    // Fetch Total Sales (sum of total_amount from orders)
    $stmtSales = $pdo->query("SELECT SUM(total_amount) AS total_sales FROM orders");
    $salesData = $stmtSales->fetch(PDO::FETCH_ASSOC);
    $totalSales = $salesData['total_sales'] ?? 0;

    // Fetch Total Users (count of rows in users table)
    $stmtUsers = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
    $usersData = $stmtUsers->fetch(PDO::FETCH_ASSOC);
    $totalUsers = $usersData['total_users'] ?? 0;

    // Fetch Total Revenue (assuming same as Total Sales for now)
    // If revenue calculation is different, this query would need adjustment
    $totalRevenue = $totalSales; // Using Total Sales for Revenue as per image

} catch (PDOException $e) {
    // Log or handle database errors
    error_log("Database error fetching card data: " . $e->getMessage());
    // Optionally set default values or show an error message on the dashboard
    $totalSales = "Error";
    $totalUsers = "Error";
    $totalRevenue = "Error";
}

// Fetch monthly sales data for chart
$monthlySalesData = [];
$monthLabels = [];

try {
    $stmtMonthlySales = $pdo->query("
        SELECT SUM(total_amount) as monthly_sales, DATE_FORMAT(order_date, '%Y-%m') as order_month
        FROM orders
        WHERE order_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY order_month
        ORDER BY order_month ASC
    ");
    $monthlyResults = $stmtMonthlySales->fetchAll(PDO::FETCH_ASSOC);

    foreach ($monthlyResults as $row) {
        $monthLabels[] = date('M Y', strtotime($row['order_month'])); // Format month for label
        $monthlySalesData[] = $row['monthly_sales'];
    }

} catch (PDOException $e) {
    error_log("Database error fetching monthly sales data: " . $e->getMessage());
    // Handle error, maybe set data to empty arrays
    $monthlySalesData = [];
    $monthLabels = [];
}

// Fetch monthly user signup data for chart
$monthlyUserData = [];
// Reuse monthLabels from sales data, assuming both cover the same period

try {
    $stmtMonthlyUsers = $pdo->query("
        SELECT COUNT(*) as monthly_users, DATE_FORMAT(created_at, '%Y-%m') as signup_month
        FROM users
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY signup_month
        ORDER BY signup_month ASC
    ");
    $monthlyUserResults = $stmtMonthlyUsers->fetchAll(PDO::FETCH_ASSOC);

    // Create a map for quick lookup of user counts by month
    $monthlyUserMap = [];
    foreach($monthlyUserResults as $row) {
        $monthlyUserMap[$row['signup_month']] = $row['monthly_users'];
    }

    // Populate monthlyUserData ensuring all months from monthLabels are covered
    foreach($monthLabels as $month) {
        $formattedMonth = date('Y-m', strtotime($month)); // Convert label back to YYYY-MM for map key
        $monthlyUserData[] = $monthlyUserMap[$formattedMonth] ?? 0;
    }

} catch (PDOException $e) {
    error_log("Database error fetching monthly user data: " . $e->getMessage());
    // Handle error
    $monthlyUserData = array_fill(0, count($monthLabels), 0); // Fill with zeros if error
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];
    if ($action === 'accept') {
        $new_status = 'confirmed';
    } elseif ($action === 'reject') {
        $new_status = 'cancelled';
    } else {
        // Invalid action, do not update
        header('Location: index.php');
        exit();
    }
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $booking_id]);
    header('Location: index.php');
    exit();
}

// Fetch bookings by status
function getBookingsByStatus($pdo, $status)
{
    $stmt = $pdo->prepare("SELECT b.*, u.firstname, u.lastname FROM bookings b LEFT JOIN users u ON b.user_id = u.id WHERE b.status = ? ORDER BY b.created_at DESC");
    $stmt->execute([$status]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$pendingBookings = getBookingsByStatus($pdo, 'pending');
$acceptedBookings = getBookingsByStatus($pdo, 'confirmed');
$rejectedBookings = getBookingsByStatus($pdo, 'cancelled');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .dashboard-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="content p-4">
            <h1 class="mb-4">Welcome to the Dashboard</h1>
            <p class="text-muted">Here's an overview of your data:</p>

            <!-- Dashboard Cards -->
            <div class="row g-4 mt-4">
                <!-- Sales Card -->
                <div class="col-md-4">
                    <div class="card text-center dashboard-card h-100">
                        <img src="/images/sales.png" class="card-img-top img-fluid p-4" alt="Sales">
                        <div class="card-body">
                            <h5 class="card-title">Sales</h5>
                            <p class="card-text fw-bold">₱<?= number_format($totalSales, 2) ?></p>
                            <p class="card-text"><small class="text-muted">Updated just now</small></p>
                        </div>
                    </div>
                </div>

                <!-- Total Users Card -->
                <div class="col-md-4">
                    <div class="card text-center dashboard-card h-100">
                        <img src="/images/users.png" class="card-img-top img-fluid p-4" alt="Total Users">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text fw-bold"><?= number_format($totalUsers) ?> Users</p>
                            <p class="card-text"><small class="text-muted">Updated just now</small></p>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="col-md-4">
                    <div class="card text-center dashboard-card h-100">
                        <img src="/images/revenue.png" class="card-img-top img-fluid p-4" alt="Revenue">
                        <div class="card-body">
                            <h5 class="card-title">Revenue</h5>
                            <p class="card-text fw-bold">₱<?= number_format($totalRevenue, 2) ?></p>
                            <p class="card-text"><small class="text-muted">Updated just now</small></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Dashboard Cards -->

            <!-- Sales Chart -->
            <div class="mt-5">
                <h2>Monthly Sales Last 12 Months</h2>
                <div class="card p-3">
                    <canvas id="monthlySalesChart"></canvas>
                </div>
            </div>
            <!-- End of Sales Chart -->

            <!-- User Signups Chart -->
            <div class="mt-5">
                <h2>Monthly User Signups Last 12 Months</h2>
                <div class="card p-3">
                    <canvas id="monthlyUserChart"></canvas>
                </div>
            </div>
            <!-- End of User Signups Chart -->

            <!-- Bookings Tables -->
            <div class="mt-5">
                <h2>Pending Bookings</h2>
                <table id="pendingTable" class="table table-bordered table-hover">
                    <thead class="table-warning">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Destination</th>
                            <th>Date</th>
                            <th>Participants</th>
                            <th>Package</th>
                            <th>Special Requests</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingBookings as $b): ?>
                            <tr>
                                <td><?= $b['id'] ?></td>
                                <td><?= htmlspecialchars(($b['firstname'] ?? '') . ' ' . ($b['lastname'] ?? '')) ?></td>
                                <td><?= htmlspecialchars($b['destination']) ?></td>
                                <td><?= htmlspecialchars($b['date']) ?></td>
                                <td><?= htmlspecialchars($b['participants']) ?></td>
                                <td><?= htmlspecialchars($b['package']) ?></td>
                                <td><?= htmlspecialchars($b['special_requests']) ?></td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td><?= htmlspecialchars($b['created_at']) ?></td>
                                <td>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                        <button type="submit" name="action" value="accept"
                                            class="btn btn-success btn-sm mb-1">Accept</button>
                                    </form>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                        <button type="submit" name="action" value="reject"
                                            class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                <h2>Accepted Bookings</h2>
                <table id="acceptedTable" class="table table-bordered table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Destination</th>
                            <th>Date</th>
                            <th>Participants</th>
                            <th>Package</th>
                            <th>Special Requests</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($acceptedBookings as $b): ?>
                            <tr>
                                <td><?= $b['id'] ?></td>
                                <td><?= htmlspecialchars(($b['firstname'] ?? '') . ' ' . ($b['lastname'] ?? '')) ?></td>
                                <td><?= htmlspecialchars($b['destination']) ?></td>
                                <td><?= htmlspecialchars($b['date']) ?></td>
                                <td><?= htmlspecialchars($b['participants']) ?></td>
                                <td><?= htmlspecialchars($b['package']) ?></td>
                                <td><?= htmlspecialchars($b['special_requests']) ?></td>
                                <td><span class="badge bg-success">Confirmed</span></td>
                                <td><?= htmlspecialchars($b['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                <h2>Rejected Bookings</h2>
                <table id="rejectedTable" class="table table-bordered table-hover">
                    <thead class="table-danger">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Destination</th>
                            <th>Date</th>
                            <th>Participants</th>
                            <th>Package</th>
                            <th>Special Requests</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rejectedBookings as $b): ?>
                            <tr>
                                <td><?= $b['id'] ?></td>
                                <td><?= htmlspecialchars(($b['firstname'] ?? '') . ' ' . ($b['lastname'] ?? '')) ?></td>
                                <td><?= htmlspecialchars($b['destination']) ?></td>
                                <td><?= htmlspecialchars($b['date']) ?></td>
                                <td><?= htmlspecialchars($b['participants']) ?></td>
                                <td><?= htmlspecialchars($b['package']) ?></td>
                                <td><?= htmlspecialchars($b['special_requests']) ?></td>
                                <td><span class="badge bg-danger">Cancelled</span></td>
                                <td><?= htmlspecialchars($b['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#pendingTable').DataTable();
            $('#acceptedTable').DataTable();
            $('#rejectedTable').DataTable();
        });

        // Chart.js code for Monthly Sales Chart
        const ctx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthlySalesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($monthLabels) ?>,
                datasets: [{
                    label: 'Monthly Sales',
                    data: <?= json_encode($monthlySalesData) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); // Format as currency
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += '₱' + context.raw.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Chart.js code for Monthly User Signups Chart
        const userCtx = document.getElementById('monthlyUserChart').getContext('2d');
        const monthlyUserChart = new Chart(userCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($monthLabels) ?>,
                datasets: [{
                    label: 'New User Signups',
                    data: <?= json_encode($monthlyUserData) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Assuming whole numbers for user counts
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>