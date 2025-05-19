<?php include '../includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Custom CSS -->

</head>

<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-4">


        <h1>Welcome to the Accounts</h1>
        <p>Accounts section</p>

        <div class="row mt-4">
            <!-- User Table -->
            <div class="col-md-12">
                <div id="liveAlert" class="mb-3"></div>
                <div class="card">
                    <div class="card-body">
                        <input type="text" class="form-control mb-3" id="search" placeholder="Search ...">
                        <table id="userTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID Number</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                            <?php
                            include '../includes/db.php';
                            $users = $pdo->query("SELECT * FROM users ORDER BY id DESC");
                            while ($user = $users->fetch()) {
                                echo "<tr>
                                    <td>{$user['id']}</td>
                                    <td>".htmlspecialchars($user['firstname'])."</td>
                                    <td>".htmlspecialchars($user['lastname'])."</td>
                                    <td>".htmlspecialchars($user['email'])."</td>
                                    <td>
                                        <button class='btn btn-danger btn-sm deleteBtn'><i class='mdi mdi-delete'></i></button>
                                    </td>
                                </tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>