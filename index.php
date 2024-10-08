<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Learn more about School & Job Search, our mission, and values." />
    <meta name="author" content="School & Job Search" />
    <title>Job_forum</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .hero-section {
            background: url('assets/img/hero-background.jpg') no-repeat center center;
            background-size: cover;
            position: relative;
            color: white;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
        }

        .hero-content p {
            font-size: 1.5rem;
            font-weight: 300;
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.2rem;
            }
        }

        .mission-section {
            background: linear-gradient(to right, #f9f9f9, #e3e3e3);
            padding: 50px 20px;
            position: relative;
        }

        .mission-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('assets/img/mission-background.jpg') no-repeat center center;
            background-size: cover;
            opacity: 0.2;
            z-index: 1;
        }

        .mission-content {
            position: relative;
            z-index: 2;
            text-align: left;
        }

        .mission-content h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .mission-content p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .mission-icon {
            font-size: 4rem;
            color: #007bff;
            margin-right: 20px;
        }

        .mission-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .mission-item:last-child {
            margin-bottom: 0;
        }

        .mission-item h3 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .mission-item p {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .mission-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .mission-icon {
                margin-bottom: 10px;
            }
        }

        /* Custom styles for values section */
        .card-value {
            border-radius: 15px; /* Rounded corners */
        }

        .card-value .card-body {
            position: relative;
            padding-top: 60px; /* Space for icon */
        }

        .card-icon {
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 3rem; /* Adjust the size of the icon */
            color: #007bff; /* Icon color */
        }

        .card-title {
            margin-top: 45px; /* Adjust this margin based on the icon size */
        }

        .card-text {
            font-size: 1rem;
        }

        /* Team Section Styles */
        .team-member img {
            transition: transform 0.3s ease, filter 0.3s ease;
            filter: grayscale(100%);
        }

        .team-member img:hover {
            transform: scale(1.1);
            filter: grayscale(0%);
        }

        .team-member h5 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .team-member p {
            font-size: 1rem;
            font-weight: 500;
        }

        /* Footer Styles */
        .footer {
            background: linear-gradient(to right, #343a40, #212529);
            color: white;
        }

        .footer .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .footer .social-icons a:hover {
            color: #007bff;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <img src="uploads/logosekolah.png" alt="Logo" class="img-fluid logo" style="width: 35px;">
        <a class="navbar-brand" href="#!">School & Job Search</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a> <!-- Link ke ID "about" -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a> <!-- Link ke ID "contact" -->
                </li>
            </ul>
        </div>
    </div>
</nav>


    <!-- Hero Section -->
    <header class="hero-section text-center py-5" data-aos="fade-up">
        <div class="container hero-content">
            <h1 class="display-4">About School & Job Search</h1>
            <p class="lead">Our mission is to connect learners and job seekers with the best opportunities and resources.</p>
        </div>
    </header>

    <!-- Central Button Section -->
    <section class="py-5 bg-light text-center" data-aos="zoom-in">
        <div class="container">
            <a href="dashboard.php" class="btn btn-lg btn-primary btn-dashboard">Go to Dashboard</a>
        </div>
    </section>

    <!-- Mission Section -->
    <section id="about" class="mission-section" data-aos="fade-up">
    <div class="container mission-content">
        <h2>Our Mission</h2>
            <div class="mission-item">
                <i class="fas fa-handshake mission-icon"></i>
                <div>
                    <h3>Connecting Opportunities</h3>
                    <p>We strive to bridge the gap between talent and opportunity, providing valuable resources for job seekers.</p>
                </div>
            </div>
            <div class="mission-item">
                <i class="fas fa-rocket mission-icon"></i>
                <div>
                    <h3>Innovating Solutions</h3>
                    <p>We are committed to continuous innovation to enhance the user experience and drive progress in the job market.</p>
                </div>
            </div>
            <div class="mission-item">
                <i class="fas fa-globe mission-icon"></i>
                <div>
                    <h3>Building Community</h3>
                    <p>We foster a supportive community where individuals can grow, learn, and succeed together.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-5 bg-light" data-aos="fade-left">
        <div class="container">
            <div class="row">
                <div class="col-lg-4" data-aos="flip-left">
                    <div class="card h-100 shadow-sm card-value">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="fas fa-gavel"></i> <!-- Contoh ikon, sesuaikan dengan yang relevan -->
                            </div>
                            <h3 class="card-title">Integrity</h3>
                            <p class="card-text">We believe in doing the right thing, even when no one is watching. Integrity is at the core of everything we do.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="flip-left">
                    <div class="card h-100 shadow-sm card-value">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="fas fa-lightbulb"></i> <!-- Contoh ikon, sesuaikan dengan yang relevan -->
                            </div>
                            <h3 class="card-title">Innovation</h3>
                            <p class="card-text">We are constantly exploring new ways to improve our platform and provide better services to our users.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="flip-left">
                    <div class="card h-100 shadow-sm card-value">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="fas fa-users"></i> <!-- Contoh ikon, sesuaikan dengan yang relevan -->
                            </div>
                            <h3 class="card-title">Community</h3>
                            <p class="card-text">Building a strong community is essential. We strive to create an inclusive environment where everyone can thrive.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-5 bg-white" data-aos="fade-up">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Meet Our Team</h2>
                <p class="lead">Our dedicated team is passionate about making a difference.</p>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 text-center mb-4 team-member" data-aos="zoom-in">
                    <img src="assets/img/team-member-1.jpg" class="img-fluid rounded-circle mb-3" alt="Team Member 1">
                    <h5>John Doe</h5>
                    <p class="text-muted">CEO</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center mb-4 team-member" data-aos="zoom-in">
                    <img src="assets/img/team-member-2.jpg" class="img-fluid rounded-circle mb-3" alt="Team Member 2">
                    <h5>Jane Smith</h5>
                    <p class="text-muted">CTO</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center mb-4 team-member" data-aos="zoom-in">
                    <img src="assets/img/team-member-3.jpg" class="img-fluid rounded-circle mb-3" alt="Team Member 3">
                    <h5>Mike Johnson</h5>
                    <p class="text-muted">Head of Marketing</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center mb-4 team-member" data-aos="zoom-in">
                    <img src="assets/img/team-member-4.jpg" class="img-fluid rounded-circle mb-3" alt="Team Member 4">
                    <h5>Sara Williams</h5>
                    <p class="text-muted">Lead Designer</p>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Footer -->
    <footer class="footer py-4">
        <div class="container text-center">
            <p class="text-muted small mb-2">&copy; School & Job Search 2024. All Rights Reserved.</p>
            <div class="social-icons">
                <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="me-2"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000, // Durasi animasi (ms)
            easing: 'ease-in-out', // Jenis easing animasi
            once: true, // Apakah animasi hanya diputar sekali
        });
    </script>
</body>

</html>
