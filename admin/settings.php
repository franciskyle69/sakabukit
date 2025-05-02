<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    
    <link rel="stylesheet" href="styles/styles.css">

    <link rel="icon" type="image/png" href="../assets/images/logo.png">

    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    
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
                                            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                                        </div>

                                        <!-- Email Field -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Email</span>
                                            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                                        </div>

                                        <!-- Password Field -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Password</span>
                                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                        </div>

                                        <!-- Confirm Password Field -->
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Confirm Password</span>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
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
