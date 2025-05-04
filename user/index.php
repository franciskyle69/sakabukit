<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/logo.png">

    <style>
        #fullscreenPlayer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: translateY(100%);
            transition: transform 0.5s ease;
            text-align: center;
            padding: 20px;
        }

        #fullscreenPlayer h2 {
            color: white;
            margin-bottom: 10px;
            font-size: 2rem;
        }

        #fullscreenPlayer p {
            color: lightgray;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        #fullscreenVideo {
            width: 90%;
            max-height: 70%;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <?php include '../includes/navbar.php'; ?>

    <main class="container mt-4 mb-5">
        <div class="content p-4">
            <?php if ($role === 'user'): ?>
                <p>Welcome <?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>!</p>
            <?php else: ?>
                <p>Welcome, Guest!</p>
                <a href="../login.php">Login</a> or <a href="../signup.php">Sign up</a>
            <?php endif; ?>
            <p class="text-muted">Your partner in the mountain!</p>
            <br>

            <div class="row">
                <!-- First Video -->
                <div class="col-md-6 mb-4">
                    <div class="card product-card text-center">
                        <h5 class="card-title mt-3">Kulago</h5>
                        <div class="card-body d-flex justify-content-center">
                            <div class="video-wrapper" data-title="Kulago"
                                data-description="Discover the peaceful beauty of Mount Kulago, where every step brings you closer to nature's calm and endless adventure.">
                                <video class="w-100" style="max-height: 400px; object-fit: cover;">
                                    <source src="../assets/videos/promote.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                        <p class="text-muted">Discover the peaceful beauty of Mount Kulago, where every step brings you
                            closer to nature's calm and endless adventure.</p>
                    </div>
                </div>

                <!-- Second Video -->
                <div class="col-md-6 mb-4">
                    <div class="card product-card text-center">
                        <h5 class="card-title mt-3">Holon</h5>
                        <div class="card-body d-flex justify-content-center">
                            <div class="video-wrapper" data-title="Holon"
                                data-description="Journey to the breathtaking Lake Holon, hidden in the heart of the mountains — a perfect escape for your soul and spirit.">
                                <video class="w-100" style="max-height: 400px; object-fit: cover;">
                                    <source src="../assets/videos/holon.mp4" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                        <p class="text-muted">Journey to the breathtaking Lake Holon, hidden in the heart of the
                            mountains — a perfect escape for your soul and spirit.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Fullscreen Player Overlay -->
    <div id="fullscreenPlayer">
        <h2 id="fullscreenTitle"></h2>
        <p id="fullscreenDesc"></p>
        <video id="fullscreenVideo" controls></video>
    </div>

    <footer>
        <div class="container text-center mt-5">
            <p class="mb-0">&copy; <?= date('Y'); ?> Saka Buk IT. All rights reserved.</p>
            <small>Climb mountains not so the world can see you, but so you can see the world.</small>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        document.querySelectorAll('.video-wrapper').forEach(wrapper => {
            wrapper.addEventListener('click', () => {
                const videoSrc = wrapper.querySelector('video source').src;
                const title = wrapper.getAttribute('data-title');
                const description = wrapper.getAttribute('data-description');

                const fullscreenVideo = document.getElementById('fullscreenVideo');
                const fullscreenTitle = document.getElementById('fullscreenTitle');
                const fullscreenDesc = document.getElementById('fullscreenDesc');
                const fullscreenPlayer = document.getElementById('fullscreenPlayer');

                fullscreenVideo.src = videoSrc;
                fullscreenTitle.textContent = title;
                fullscreenDesc.textContent = description;

                fullscreenPlayer.style.transform = 'translateY(0%)'; // Slide up
                fullscreenVideo.play();
            });
        });

        // Close when clicking outside
        document.getElementById('fullscreenPlayer').addEventListener('click', (e) => {
            if (e.target.id === 'fullscreenPlayer') {
                const fullscreenVideo = document.getElementById('fullscreenVideo');
                fullscreenVideo.pause();
                fullscreenVideo.currentTime = 0;
                fullscreenVideo.src = '';
                document.getElementById('fullscreenPlayer').style.transform = 'translateY(100%)'; // Slide down
            }
        });
    </script>

</body>

</html>