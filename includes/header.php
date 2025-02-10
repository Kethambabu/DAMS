<nav class="navbar navbar-expand-lg navbar-light transparent-header fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="chatbot.php">
            <img src="assets/img/logo-icon.png" alt="AI Chatbot Logo" width="40" height="40" class="d-inline-block align-text-top me-2 logo-glow">
            <span class="fw-bold text-primary">AIIMS<span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="index.php">
                        <i class="bi bi-house-heart me-2"></i>Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#about">
                        <i class="bi bi-info-circle me-2"></i>About
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link fw-semibold dropdown-toggle" href="#" id="appointmentDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-calendar-check me-2"></i>Appointments
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="appointmentDropdown">
                        <li><a class="dropdown-item" href="check-appointment.php">check Appointment</a></li>
                        <li><a class="dropdown-item" href="doctor/login.php">Doctor Login</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#booking">
                        <i class="bi bi-journal-medical me-2"></i>Booking
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#contact">
                        <i class="bi bi-envelope-heart me-2"></i>Contact
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="feedbacks.php">
                        <i class="bi bi-chat-heart me-2"></i>Feedbacks
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <a href="chatbot.php" class="btn btn-outline-primary rounded-pill">
                    <i class="bi bi-robot me-2"></i>AI Chatbot
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
    :root {
        --primary-color: #2c7da0;      /* Deep blue - professional, trustworthy */
        --secondary-color: #61a5c2;    /* Lighter blue - calming, medical */
        --background-color: rgba(255, 255, 255, 0.85);  /* Transparent white */
        --text-color: #333;            /* Dark gray - easy to read */
        --hover-color: #0077b6;        /* Vibrant blue - highlight */
    }

    .transparent-header {
        background-color: var(--background-color);
        backdrop-filter: blur(10px);  /* Adds a blur effect for transparency */
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 2px 10px rgba(44, 125, 160, 0.1);
        border-bottom: 1px solid rgba(97, 165, 194, 0.2);
        transition: all 0.3s ease;
    }

    .transparent-header.scrolled {
        background-color: rgba(255, 255, 255, 0.95);
        box-shadow: 0 4px 15px rgba(44, 125, 160, 0.15);
    }

    .navbar-brand {
        display: flex;
        align-items: center;
    }

    .navbar-brand .logo-glow {
        transition: all 0.3s ease;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(44, 125, 160, 0.3);
    }

    .navbar-brand .logo-glow:hover {
        transform: scale(1.1);
        box-shadow: 0 0 15px rgba(44, 125, 160, 0.5);
    }

    .navbar-brand span {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 1.2rem;
        text-shadow: 1px 1px 2px rgba(44, 125, 160, 0.2);
    }

    .nav-link {
        color: var(--text-color);
        font-weight: 500;
        transition: all 0.3s ease;
        position: relative;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -5px;
        left: 50%;
        background-color: var(--hover-color);
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .nav-link:hover {
        color: var(--hover-color);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .btn-outline-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(44, 125, 160, 0.2), transparent);
        transition: all 0.5s ease;
    }

    .btn-outline-primary:hover::before {
        left: 100%;
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
        transform: scale(1.05);
    }

    @media (max-width: 992px) {
        .navbar-brand span {
            font-size: 1rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.transparent-header');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});
</script>
