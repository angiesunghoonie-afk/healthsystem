<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'components/header.php'; ?>
</head>
<body>

    <?php include 'components/navbar.php'; ?>

    <div class="services-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Our Health Services</h1>
            <p>Comprehensive healthcare services for you and your family</p>
        </div>
    </div>

    <div class="services-section">
        <div class="services-container">
            <div class="section-header">
                <h2>What We Offer</h2>
                <p>Providing quality healthcare to our community with dedication and compassion</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon consultation-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <path d="M10 13a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2"/>
                            <line x1="8" y1="13" x2="16" y2="13"/>
                        </svg>
                    </div>
                    <h3>Medical Consultation</h3>
                    <p>Receive professional medical advice and treatment from our qualified healthcare providers. We offer general check-ups, diagnosis, and personalized treatment plans.</p>
                    <a href="register.php" class="service-link">Book Appointment →</a>
                </div>

                <div class="service-card">
                    <div class="service-icon vaccination-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <path d="M12 18v-6"/>
                            <path d="M9 15l3 3 3-3"/>
                        </svg>
                    </div>
                    <h3>Vaccination Programs</h3>
                    <p>Participate in our vaccination programs to protect yourself and your community from preventable diseases. We provide scheduled immunizations for all ages.</p>
                    <a href="register.php" class="service-link">Learn More →</a>
                </div>

                <div class="service-card">
                    <div class="service-icon education-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <h3>Health Education</h3>
                    <p>Attend health education sessions to learn about healthy lifestyles, disease prevention, and wellness practices. Empowering our community with knowledge.</p>
                    <a href="register.php" class="service-link">View Schedule →</a>
                </div>

                <div class="service-card">
                    <div class="service-icon maternal-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <path d="M12 8v4"/>
                            <path d="M12 16h.01"/>
                        </svg>
                    </div>
                    <h3>Maternal and Child Health</h3>
                    <p>Access specialized care for expectant mothers and children, including prenatal check-ups, postnatal care, and pediatric services for healthy development.</p>
                    <a href="register.php" class="service-link">Get Started →</a>
                </div>

                <div class="service-card">
                    <div class="service-icon laboratory-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 2v7.31"/>
                            <path d="M14 2v7.31"/>
                            <path d="M8.5 2h7"/>
                            <path d="M14 9.3a6.5 6.5 0 1 1-4 0"/>
                            <path d="M5.52 16h12.96"/>
                        </svg>
                    </div>
                    <h3>Laboratory Services</h3>
                    <p>Comprehensive laboratory tests and diagnostics including blood tests, urinalysis, and other essential medical screenings for accurate diagnosis.</p>
                    <a href="register.php" class="service-link">View Tests →</a>
                </div>

                <div class="service-card">
                    <div class="service-icon pharmacy-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        </svg>
                    </div>
                    <h3>Pharmacy Services</h3>
                    <p>Access affordable prescription medications and over-the-counter drugs. Our pharmacy ensures you get the right medicines with proper guidance.</p>
                    <a href="register.php" class="service-link">Learn More →</a>
                </div>

                <div class="service-card">
                    <div class="service-icon dental-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4.5 10.5C3 10.5 2 12 2 14c0 3 2 5 5 5 1.5 0 2.5-1 3.5-2s2-2 3.5-2 2.5 1 3.5 2 2 2 3.5 2c3 0 5-2 5-5 0-2-1-3.5-2.5-3.5"/>
                            <path d="M12 2v8"/>
                            <path d="M12 14c0 2-1 4-3 4"/>
                            <path d="M12 14c0 2 1 4 3 4"/>
                        </svg>
                    </div>
                    <h3>Dental Services</h3>
                    <p>Complete dental care including tooth extraction, cleaning, fillings, and oral health education to maintain your smile and overall health.</p>
                    <a href="register.php" class="service-link">Book Now →</a>
                </div>

                <div class="service-card">
                    <div class="service-icon emergency-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v4"/>
                            <path d="M12 18v4"/>
                            <path d="M4.93 4.93l2.83 2.83"/>
                            <path d="M16.24 16.24l2.83 2.83"/>
                            <path d="M2 12h4"/>
                            <path d="M18 12h4"/>
                            <path d="M4.93 19.07l2.83-2.83"/>
                            <path d="M16.24 7.76l2.83-2.83"/>
                        </svg>
                    </div>
                    <h3>Emergency Care</h3>
                    <p>24/7 emergency medical services for urgent health needs. Our trained staff is ready to provide immediate care and stabilization.</p>
                    <a href="contact.php" class="service-link">Contact Us →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="services-cta">
        <div class="cta-content">
            <h2>Ready to Access Our Services?</h2>
            <p>Register today to book appointments and manage your health records online</p>
            <div class="cta-buttons">
                <a href="register.php" class="cta-btn primary">Register Now</a>
                <a href="contact.php" class="cta-btn secondary">Contact Us</a>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
</body>
</html>