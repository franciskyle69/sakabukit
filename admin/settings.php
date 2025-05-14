<?php include '../includes/auth_check.php'; ?>
<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>

    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    

    <!-- google font -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
	<!-- fontawesome -->
	<link rel="stylesheet" href="../assets/css/all.min.css">
	<!-- bootstrap -->
	<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
	<!-- owl carousel -->
	<link rel="stylesheet" href="../assets/css/owl.carousel.css">
	<!-- magnific popup -->
	<link rel="stylesheet" href="../assets/css/magnific-popup.css">
	<!-- animate css -->
	<link rel="stylesheet" href="../assets/css/animate.css">
	<!-- mean menu css -->
	<link rel="stylesheet" href="../assets/css/meanmenu.min.css">
	<!-- main style -->
	<link rel="stylesheet" href="../assets/css/main.css">
	<!-- responsive -->
	<link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="icon" type="image/png" href="../assets/images/logo.png">

</head>

<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="dashboard">


        <div class="main-content">

            <div class="content">
                <h1>Welcome to the Settings</h1>
                <p>Update your profile information below:</p>
                <div class="container mt-4" style="width: 50%;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">

                                <div class="card-body">
                                    <h4>Update Profile Info</h4>
                                    <img src="../assets/images/updatepf.png" style="width: 95%;">
                                    <!-- Profile Settings Form -->
                                    <form action="update_profile.php" method="POST">
                                        <!-- Username Field -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Username</span>
                                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                                        </div>
                                        <!-- First Name Field -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">First Name</span>
                                            <input type="text" name="firstname" class="form-control" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>" required>
                                        </div>
                                        <!-- Last Name Field -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Last Name</span>
                                            <input type="text" name="lastname" class="form-control" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>" required>
                                        </div>
                                        <!-- Password Field -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">New Password</span>
                                            <input type="password" name="password" class="form-control" placeholder="Enter new password (leave blank to keep current)">
                                        </div>
                                        <!-- Confirm Password Field -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Confirm Password</span>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
                                        </div>
                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>