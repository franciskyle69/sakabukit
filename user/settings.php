<?php
session_start();
include '../includes/db.php';
include '../includes/auth_check.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $street = $_POST['street'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $zip = $_POST['zip'] ?? '';
    $about = $_POST['about'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $profile_photo_path = null;

    // Handle profile photo upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../assets/uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $file_name = uniqid() . '_' . basename($_FILES["profile_photo"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
        if ($check !== false && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
                $profile_photo_path = $target_file;
            }
        }
    }

    // Password update logic
    $update_password = false;
    if (!empty($password)) {
        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_password = true;
        } else {
            $_SESSION['message'] = "Passwords do not match.";
            header("Location: settings.php");
            exit;
        }
    }

    // Build SQL update query
    $fields = [];
    $params = [];

    if ($firstname !== '') {
        $fields[] = "firstname = ?";
        $params[] = $firstname;
    }
    if ($lastname !== '') {
        $fields[] = "lastname = ?";
        $params[] = $lastname;
    }
    if ($email !== '') {
        $fields[] = "email = ?";
        $params[] = $email;
    }
    if ($phone !== '') {
        $fields[] = "phone = ?";
        $params[] = $phone;
    }
    if ($street !== '') {
        $fields[] = "street = ?";
        $params[] = $street;
    }
    if ($city !== '') {
        $fields[] = "city = ?";
        $params[] = $city;
    }
    if ($state !== '') {
        $fields[] = "state = ?";
        $params[] = $state;
    }
    if ($zip !== '') {
        $fields[] = "zip = ?";
        $params[] = $zip;
    }
    if ($about !== '') {
        $fields[] = "about = ?";
        $params[] = $about;
    }
    if ($profile_photo_path) {
        $fields[] = "profile_photo = ?";
        $params[] = $profile_photo_path;
    }
    if ($update_password) {
        $fields[] = "password = ?";
        $params[] = $hashed_password;
    }

    if (!empty($fields)) {
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE username = ?";
        $params[] = $username;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $_SESSION['message'] = "Profile updated successfully.";
    } else {
        $_SESSION['message'] = "No changes made.";
    }
    header("Location: settings.php");
    exit;
}

// Get current user info
if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
} else {
    $user = null;
}

// Include navbar after all header operations
include '../includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Settings</title>
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
        body{
color: #bcd0f7;
   
}
.account-settings .user-profile {
    margin: 0 0 1rem 0;
    padding-bottom: 1rem;
    text-align: center;
}
.account-settings .user-profile .user-avatar {

    margin: 0 0 1rem 0;
}
.account-settings .user-profile .user-avatar img {
    width: 90px;
    height: 90px;
    -webkit-border-radius: 100px;
    -moz-border-radius: 100px;
    border-radius: 100px;
}
.account-settings .user-profile h5.user-name {
    color: #fff;
    margin: 0 0 0.5rem 0;
}
.account-settings .user-profile h6.user-email {
    color:  #F28123;
    margin: 0;
    font-size: 0.8rem;
    font-weight: 400;
}
.account-settings .about {
    margin: 1rem 0 0 0;
    font-size: 0.8rem;
    text-align: center;
    

   
}
.card {
    background: #012738;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    border: 0;
    margin-bottom: 1rem;
}
.form-control {
    border: 1px solid #596280;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    font-size: .825rem;
    background: #012738;
    /* color: #bcd0f7; */
     color:  #F28123;
}
    </style>

    <!-- jQuery -->
    <script src="../assets/js/jquery-1.11.3.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mean Menu JS -->
    <script src="../assets/js/jquery.meanmenu.min.js"></script>
    
    <!-- Sticky JS -->
    <script src="../assets/js/sticker.js"></script>
    
    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>
</head>

<body>


    <div class="container mt-5">
        <div class="row gutters">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="account-settings">
                            <div class="user-profile text-center">
                                <div class="user-avatar mb-3">
                                    <img id="profilePreview" src="<?= htmlspecialchars($user['profile_photo'] ?? '/assets/images/default-profile.png') ?>" alt="Profile Photo" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <h5 class="user-name"><?= htmlspecialchars(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) ?></h5>
                                <h6 class="user-email"><?= htmlspecialchars($user['email'] ?? '') ?></h6>
                            </div>
                            <div class="about mt-4">
                                <h5 class="mb-2 text-primary">About</h5>
                                <p style="color: #fff;"><?= htmlspecialchars($user['about'] ?? "No about info yet.") ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-info"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
                        <?php endif; ?>
                        <p class="text-info">Your current information is shown below. Change only what you want to update.</p>
                        <form action="settings.php" method="POST" enctype="multipart/form-data">
                            <div class="row gutters" style="color: #fff;">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-3 text-primary">Personal Details</h6>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="firstname">First Name</label>
                                        <input type="text" name="firstname" class="form-control" id="firstname" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="lastname">Last Name</label>
                                        <input type="text" name="lastname" class="form-control" id="lastname" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control" id="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters" style="color: #fff;">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-3 text-primary">Address</h6>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="street">Street</label>
                                        <input type="text" name="street" class="form-control" id="street" value="<?= htmlspecialchars($user['street'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" name="city" class="form-control" id="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" name="state" class="form-control" id="state" value="<?= htmlspecialchars($user['state'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="zip">Zip Code</label>
                                        <input type="text" name="zip" class="form-control" id="zip" value="<?= htmlspecialchars($user['zip'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters" style="color: #fff;">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-3 text-primary" style="margin-top: 15px;">Profile Photo & Password</h6>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="profile_photo">Profile Photo</label>
                                        <input type="file" name="profile_photo" class="form-control" id="profilePhotoInput" onchange="previewProfilePhoto(event)">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="about">About</label>
                                        <textarea name="about" class="form-control" id="about" rows="2"><?= htmlspecialchars($user['about'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="password">New Password (leave blank to keep current)</label>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm New Password</label>
                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right" style="margin-top: 15px;">
                                        <a href="settings.php" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function previewProfilePhoto(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('profilePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    </script>
</body>
<footer style="background-color: #051922;">
    <div class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-box about-widget">
                        <h2 class="widget-title">About us</h2>
                        <p>Saka Bukit is your trusted destination for eCommerce and booking services.
                             We offer a seamless shopping experience and easy reservations for various 
                             services. Our platform combines convenience, quality, and reliability to 
                             serve individuals and businesses across the region. Shop and book with confidence at Saka Bukit.</p>
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
                                <input type="email" placeholder="Email" style="flex: 1; height: 40px; padding: 0 10px;">
                                <button type="submit" style="height: 40px; display: flex; align-items: center; justify-content: center; padding: 0 15px;">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- <div class="container text-center mt-5">
            <p class="mb-0" style="color: orange;">&copy; <?= date('Y'); ?> Saka Buk IT. All rights reserved.</p>
            <small>Climb mountains not so the world can see you, but so you can see the world.</small>
        </div> -->
    </footer>
        <div class="copyright text-center">
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
</html>