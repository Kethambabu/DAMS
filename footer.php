<footer class="site-footer section-py-3" id="contact">
    <div class="container">
        <div class="row g-4 justify-content-between align-items-center">
            <div class="col-lg-4 col-md-6">
                <div class="footer-info">
                    <h5 class="text-primary mb-3">AIMS Hospital</h5>
                    <?php
                    $sql="SELECT * from tblpage where PageType='contactus'";
                    $query = $dbh -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);

                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                    foreach($results as $row)
                    {               ?>
                    <p class="text-muted">
                        <?php echo ($row->PageDescription);?>
                    </p>
                    <ul class="list-unstyled contact-info">
                        <li>
                            <i class="bi bi-clock text-primary me-2"></i>
                            <strong>Timing:</strong> <?php echo ($row->Timing);?>
                        </li>
                        <li>
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <strong>Email:</strong> <?php echo ($row->Email);?>
                        </li>
                        <li>
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <strong>Contact:</strong> <?php echo ($row->MobileNumber);?>
                        </li>
                    </ul>
                    <?php $cnt=$cnt+1;}} ?>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-links">
                    <h5 class="text-primary mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-muted">Home</a></li>
                        <li><a href="check-appointment.php" class="text-muted">Book Appointment</a></li>
                        <li><a href="chatbot.php" class="text-muted">AI Assistant</a></li>
                        <li><a href="#" class="text-muted">Services</a></li>
                        <li><a href="location-tracking.php" class="text-muted"><i class="bi bi-geo-alt text-primary me-2"></i>Find Me</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="footer-social">
                    <h5 class="text-primary mb-3">Connect With Us</h5>
                    <div class="social-icons">
                        <a href="#" class="btn btn-outline-primary btn-sm me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-primary btn-sm me-2"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="btn btn-outline-primary btn-sm me-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-primary btn-sm"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4 border-primary">

        <div class="text-center py-2">
            <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> AIIMS Hospital. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        background-color: #f8f9fa;
        padding: 2rem 0;
        border-top: 1px solid #e2e6ea;
    }

    .footer-info, .footer-links, .footer-social {
        transition: transform 0.3s ease;
    }

    .footer-info:hover, .footer-links:hover, .footer-social:hover {
        transform: translateY(-5px);
    }

    .contact-info li {
        margin-bottom: 0.5rem;
    }

    .footer-links ul li {
        margin-bottom: 0.25rem;
    }

    .footer-links a {
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: #2c7da0 !important;
    }

    .social-icons a {
        margin-right: 0.5rem;
        transition: all 0.3s ease;
    }

    .social-icons a:hover {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .site-footer {
            text-align: center;
        }

        .footer-social .social-icons {
            justify-content: center;
        }
    }
</style>