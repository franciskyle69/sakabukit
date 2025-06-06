<?php
include '../includes/navbar.php';
include '../includes/db.php';

// Fetch user's bookings
$stmt = $pdo->prepare("
    SELECT b.*, a.firstname as admin_firstname, a.lastname as admin_lastname 
    FROM bookings b 
    LEFT JOIN users a ON b.admin_id = a.id 
    WHERE b.user_id = ? 
    ORDER BY b.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <!-- External Stylesheets (refactored for consistency) -->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/all.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.css">
    <link rel="stylesheet" href="../assets/css/magnific-popup.css">
    <link rel="stylesheet" href="../assets/css/animate.css">
    <link rel="stylesheet" href="../assets/css/meanmenu.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">
    <style>
        html {
            font-size: 16px;
        }
        body {
            font-family: 'Open Sans', 'Poppins', Arial, sans-serif;
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <div class="container mt-4 animate__animated animate__fadeIn">
        <h2 class="animate__animated animate__fadeInDown">My Bookings</h2>
        <?php if (empty($bookings)): ?>
            <div class="alert alert-info animate__animated animate__fadeInUp">
                You haven't made any bookings yet. <a href="bookings.php" class="alert-link">Make a booking now!</a>
            </div>
        <?php else: ?>
            <table id="bookingsTable" class="table table-bordered table-hover animate__animated animate__fadeInUp" style="transition: box-shadow 0.3s;">
                <thead>
                    <tr>
                        <th>Booking ID</th>
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
                    <?php foreach ($bookings as $booking): ?>
                        <tr class="booking-row" style="transition: background 0.3s;">
                            <td><?= $booking['id'] ?></td>
                            <td><?= htmlspecialchars($booking['destination']) ?></td>
                            <td><?= htmlspecialchars($booking['date']) ?></td>
                            <td><?= htmlspecialchars($booking['participants']) ?></td>
                            <td><?= htmlspecialchars($booking['package']) ?></td>
                            <td><?= htmlspecialchars($booking['special_requests']) ?></td>
                            <td>
                                <?php
                                $statusClass = '';
                                switch ($booking['status']) {
                                    case 'pending':
                                        $statusClass = 'bg-warning text-dark';
                                        break;
                                    case 'confirmed':
                                        $statusClass = 'bg-success';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-danger';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $statusClass ?> animate__animated animate__pulse" style="transition: background 0.3s;">
                                    <?= ucfirst($booking['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($booking['admin_id']): ?>
                                    <?= htmlspecialchars(($booking['admin_firstname'] ?? '') . ' ' . ($booking['admin_lastname'] ?? '')) ?>
                                <?php else: ?>
                                    <span class="text-muted">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($booking['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
    </div>
    <footer style="background-color: #051922; transition: background 0.5s;">
        <div class="footer-area animate__animated animate__fadeInUp">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-box about-widget">
                            <h2 class="widget-title">About us</h2>
                            <p>Saka Bukit is your trusted destination for eCommerce and booking services.
                                We offer a seamless shopping experience and easy reservations for various
                                services. Our platform combines convenience, quality, and reliability to
                                serve individuals and businesses across the region. Shop and book with confidence at
                                Saka Bukit.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-box get-in-touch">
                            <h2 class="widget-title">Get in Touch</h2>
                            <ul>
                                <li> Fortich Street, Barangay 3, Malaybalay City, Bukidnon</li>
                                <li>support@sakabukit.com</li>
                                <li>+00 111 222 3333</li>
                            </ul>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6">
                        <div class="footer-box subscribe">
                            <h2 class="widget-title">Subscribe</h2>
                            <p>Subscribe to our mailing list to get the latest updates.</p>
                            <form action="#">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="email" placeholder="Email"
                                        style="flex: 1; height: 40px; padding: 0 10px; transition: box-shadow 0.3s;">
                                    <button type="submit"
                                        style="height: 40px; display: flex; align-items: center; justify-content: center; padding: 0 15px; transition: background 0.3s;">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="copyright text-center animate__animated animate__fadeInUp">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-12">
                    <p>Copyrights &copy; 2025 - <a href="../user/index.php">SAKA BUKIT</a>, All Rights Reserved.<br>
                        Climb mountains not so the world can see you, but so you can see the world
                    </p>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Animate.css CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#bookingsTable').DataTable();

            // Row hover transition
            $('.booking-row').hover(
                function() {
                    $(this).css('background', '#f5f5f5');
                },
                function() {
                    $(this).css('background', '');
                }
            );
        });
    </script>
    <style>
        /* Smooth transitions for table rows and badges */
        .booking-row {
            transition: background 0.3s;
        }
        .badge {
            transition: background 0.3s, color 0.3s;
        }
        /* Animate table on load */
        #bookingsTable {
            transition: box-shadow 0.3s;
        }
        #bookingsTable:hover {
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        /* Animate footer on hover */
        footer:hover {
            background: #07335c;
        }
    </style>
</body>


</html>