<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTrade - Your Tech Marketplace</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="css/landing.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="images/header_logo.png" alt="TechTrade Logo" class="logo-img">
                <span class="logo-text">TechTrade</span>
            </a>
            <div class="nav-buttons">
                <a href="login/login.php" class="btn btn-outline-primary">Login</a>
                <a href="login/register.php" class="btn btn-primary">Register</a>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1>Your Ultimate Tech Marketplace</h1>
                        <p class="lead">Buy, sell, and trade the latest tech gadgets. Join our community of tech enthusiasts today!</p>
                        <div class="cta-buttons">
                            <a href="login/register.php" class="btn btn-primary btn-lg">Get Started</a>
                            <a href="#features" class="btn btn-outline-primary btn-lg">Learn More</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://metapod.com/cdn/shop/articles/tech-gift-for-him-2022-metapod.webp?v=1660778620&width=2048" alt="Tech devices" class="hero-image">
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="features">
            <div class="container">
                <h2 class="text-center mb-5">Why Choose TechTrade?</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-shop"></i>
                            <h3>Buy & Sell</h3>
                            <p>Access a wide range of tech products from trusted sellers</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-arrow-left-right"></i>
                            <h3>Trade-In</h3>
                            <p>Trade your old devices for credit towards new purchases</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <i class="bi bi-shield-check"></i>
                            <h3>Secure Trading</h3>
                            <p>Safe and secure transactions guaranteed</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2024 TechTrade. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="view/terms.php" class="footer-link">Terms of Service</a>
                    <a href="view/privacy.php" class="footer-link">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>