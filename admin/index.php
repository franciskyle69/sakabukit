<?php
include '../includes/navbar.php';
include '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/styles.css">

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

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

            <!-- Print Button -->

        </div>
    </div>

</body>

</html>