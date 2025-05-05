<?php
// Homepage for PeakPrepSPM - Inspired by Pandai Malaysia
include 'includes/header.php';
?>

<div class="hero-section position-relative text-center text-white py-5" style="background: url('<?php echo BASE_URL; ?>assets/images/banner.jpeg') center/cover no-repeat; height: 400px;">
    <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6);"></div>
    <div class="container position-relative mt-5" style="z-index: 1;">
        <h1 class="display-4">Welcome to PeakPrepSPM</h1>
        <p class="lead">Empowering SPM Students with Smart Learning</p>
        <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn btn-outline-light btn-lg rounded-pill px-4 py-2 fw-bold shadow-sm">Get Started</a>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center">Why Choose PeakPrepSPM?</h2>
    <div class="row text-center py-4">
        <div class="col-md-4">
            <img src="<?php echo BASE_URL; ?>assets/images/interactive_quizzes.jpeg" class="img-fluid mb-3" width="100">
            <h4>Interactive Quizzes</h4>
            <p>Practice with SPM-format quizzes and receive instant feedback.</p>
        </div>
        <div class="col-md-4">
            <img src="<?php echo BASE_URL; ?>assets/images/expert_guidance.jpeg" class="img-fluid mb-3" width="100">
            <h4>Expert Guidance</h4>
            <p>Learn from experienced teachers with well-structured study materials.</p>
        </div>
        <div class="col-md-4">
            <img src="<?php echo BASE_URL; ?>assets/images/analytics.jpeg" class="img-fluid mb-3" width="100">
            <h4>Progress Analytics</h4>
            <p>Track your learning journey and improve your weak areas.</p>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center">Explore Our Features</h2>
    <div class="row mt-4">
        <div class="col-md-6">
            <img src="<?php echo BASE_URL; ?>assets/images/study_materials.jpeg" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h3>Comprehensive Study Materials</h3>
            <p>Access notes, videos, and practice questions for all SPM subjects.</p>
            <a href="<?php echo BASE_URL; ?>pages/materials.php" class="btn btn-outline-primary">Explore Now</a>
        </div>
    </div>
    <div class="row mt-5 flex-row-reverse">
        <div class="col-md-6">
            <img src="<?php echo BASE_URL; ?>assets/images/quiz.jpg" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h3>Engaging Quizzes</h3>
            <p>Take quizzes and receive instant explanations for correct answers.</p>
            <a href="<?php echo BASE_URL; ?>pages/quiz.php" class="btn btn-outline-success">Start Quiz</a>
        </div>
    </div>
</div>

<div class="testimonials-section bg-light py-5 mt-5">
    <div class="container">
        <h2 class="text-center">What Our Students Say</h2>
        <div class="row mt-4 text-center">
            <div class="col-md-4">
                <p class="blockquote">"PeakPrepSPM has transformed my study habits! The quizzes are fun and effective."</p>
                <h5>- Aisyah, SPM Candidate</h5>
            </div>
            <div class="col-md-4">
                <p class="blockquote">"The study materials are well-organized and easy to understand. Highly recommended!"</p>
                <h5>- Daniel, SPM Student</h5>
            </div>
            <div class="col-md-4">
                <p class="blockquote">"I love the leaderboard feature! Competing with friends makes studying exciting."</p>
                <h5>- Siti, Top Scorer</h5>
            </div>
        </div>
    </div>
</div>

<div class="cta-section position-relative text-white text-center py-5" style="background: url('<?php echo BASE_URL; ?>assets/images/cta_bg.jpg') center/cover no-repeat;">
    <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6);"></div>
    <div class="container position-relative" style="z-index: 1;">
        <h2 class="fw-bold">Join Thousands of Successful Students</h2>
        <p class="lead">Start your journey to SPM excellence today!</p>
        <a href="<?php echo BASE_URL; ?>auth/register.php" class="btn btn-lg btn-outline-light rounded-pill px-4 py-2 fw-bold shadow-lg">Sign Up Now</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
