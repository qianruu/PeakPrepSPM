<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/header.php'; // ✅ Ensure Header is Included
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us - PeakPrepSPM</title>
    
    <!-- Custom Styles & Google Fonts -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Mobile Button Styling -->
    <style>
        @media (max-width: 768px) {
            .mobile-white {
                background-color: white !important;
                color: black !important;
                border: 1px solid #ccc !important;
            }
            .mobile-white:hover {
                background-color: #f0f0f0 !important;
            }
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://source.unsplash.com/1600x900/?education,students') no-repeat center center/cover;
            color: white;
            padding: 100px 20px;
            text-align: center;
        }

        /* Features Section */
        .feature-icon {
            font-size: 3rem;
            color: #007bff;
        }

        /* CTA Section */
        .cta-section {
            background: #f8f9fa;
            padding: 50px 20px;
            text-align: center;
            border-radius: 15px;
        }

        /* Responsive Text */
        @media (max-width: 576px) {
            .hero h1 {
                font-size: 28px;
            }
            .hero p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- ✅ Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="display-4 fw-bold">About PeakPrepSPM</h1>
            <p class="lead">Empowering Students with Smart Learning Solutions</p>
        </div>
    </section>

    <!-- ✅ Features Section -->
    <div class="container mt-5">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="feature-icon">📚</div>
                <h4 class="mt-3">Personalized Learning</h4>
                <p>Adaptive quizzes, AI-based learning recommendations, and performance tracking.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon">🎯</div>
                <h4 class="mt-3">Targeted Success</h4>
                <p>Track progress, receive feedback, and stay ahead with real-time analytics.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon">🤝</div>
                <h4 class="mt-3">Community & Collaboration</h4>
                <p>Connect with teachers, peers, and mentors to enhance your learning experience.</p>
            </div>
        </div>
    </div>

    <!-- ✅ CTA Section -->
    <section class="cta-section mt-5">
        <div class="container">
            <h3>Join PeakPrepSPM Today!</h3>
            <p>Start your journey towards smarter and more effective learning.</p>
            <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn btn-primary btn-lg">Get Started</a>
        </div>
    </section>

    <?php include '../includes/footer.php'; // ✅ Ensure Footer is Included ?>

</body>
</html>
