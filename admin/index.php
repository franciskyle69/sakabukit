<?php
include '../includes/navbar.php';
include '../includes/db.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];
    $admin_id = $_SESSION['user_id']; // Get the admin's ID

    if ($action === 'accept') {
        $new_status = 'confirmed';
    } elseif ($action === 'reject') {
        $new_status = 'cancelled';
    } else {
        // Invalid action, do not update
        header('Location: index.php');
        exit();
    }
    $stmt = $pdo->prepare("UPDATE bookings SET status = ?, admin_id = ? WHERE id = ?");
    $stmt->execute([$new_status, $admin_id, $booking_id]);
    header('Location: index.php');
    exit();
}

// Fetch bookings by status
function getBookingsByStatus($pdo, $status) {
    $stmt = $pdo->prepare("
        SELECT b.*, u.firstname, u.lastname, 
               a.firstname as admin_firstname, a.lastname as admin_lastname 
        FROM bookings b 
        LEFT JOIN users u ON b.user_id = u.id 
        LEFT JOIN users a ON b.admin_id = a.id 
        WHERE b.status = ? 
        ORDER BY b.created_at DESC
    ");
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
                            <p class="card-text fw-bold">₱500,000,000</p>
                            <p class="card-text"><small class="text-muted">Updated 5 minutes ago</small></p>
                        </div>
                    </div>
                </div>

                <!-- Total Users Card -->
                <div class="col-md-4">
                    <div class="card text-center dashboard-card h-100">
                        <img src="/images/users.png" class="card-img-top img-fluid p-4" alt="Total Users">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text fw-bold">2,000 Users</p>
                            <p class="card-text"><small class="text-muted">Updated 5 minutes ago</small></p>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="col-md-4">
                    <div class="card text-center dashboard-card h-100">
                        <img src="/images/revenue.png" class="card-img-top img-fluid p-4" alt="Revenue">
                        <div class="card-body">
                            <h5 class="card-title">Revenue</h5>
                            <p class="card-text fw-bold">₱300,000,000</p>
                            <p class="card-text"><small class="text-muted">Updated 5 minutes ago</small></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Dashboard Cards -->

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
                                    <button type="submit" name="action" value="accept" class="btn btn-success btn-sm mb-1">Accept</button>
                                </form>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
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
                            <th>Handled By</th>
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
                            <td><?= htmlspecialchars(($b['admin_firstname'] ?? '') . ' ' . ($b['admin_lastname'] ?? '')) ?></td>
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
                            <th>Handled By</th>
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
                            <td><?= htmlspecialchars(($b['admin_firstname'] ?? '') . ' ' . ($b['admin_lastname'] ?? '')) ?></td>
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
    </script>

</body>

</html>