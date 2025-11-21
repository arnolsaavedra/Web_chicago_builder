<?php
$page_title = "Chicago Attic Remodeling | Schedule Your Free Estimate";
$page_description = "Expert attic remodeling in Chicago, IL. Get a free, no-obligation estimate to convert your attic into valuable living space.";
$current_page = "attic";

include 'includes/head.php';
include 'includes/navbar.php';
?>

    <header class="hero" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.pexels.com/photos/271816/pexels-photo-271816.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2') no-repeat center center/cover;">
        <div class="hero-content">
            <h1>Attic Remodeling in Chicago – Unlock Hidden Living Space</h1>
            <h2 style="font-size: 1.2rem;color: var(--white) !important;">Transform your unused attic into a beautiful bedroom, office, or bonus room.</h2>
            <a href="#appointment" class="btn btn-primary btn-lg">Schedule Your Free Estimate</a>
        </div>
    </header>

    <div class="sub-header">
        <p>Your Trusted Attic Conversion Experts in Chicagoland Since 2011</p>
    </div>

    <section id="services" class="services animated-section">
        <div class="container">
            <h2>Our Attic Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="icon"><i class="fas fa-bed"></i></div>
                    <h3>Attic Bedrooms</h3>
                    <p>Create a cozy master suite or guest bedroom with proper insulation and climate control.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-laptop-house"></i></div>
                    <h3>Home Office Conversions</h3>
                    <p>Design the perfect quiet workspace away from household distractions.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-stairs"></i></div>
                    <h3>Attic Access & Stairs</h3>
                    <p>Professional staircase installation and structural modifications for safe access.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="appointment" class="appointment-section animated-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Schedule Your Free Consultation</h2>
                    <p class="lead">Ready to convert your attic? Book your free, no-obligation consultation today. Our experts will visit your home, assess your needs, and provide you with a detailed estimate.</p>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i> Free in-home consultation</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i> Detailed written estimate</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i> No pressure, honest advice</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i> Professional assessment</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="appointment-form">
                        <h3 class="text-center mb-4 text-primary">Book Your Appointment</h3>

                        <div id="alert-container"></div>

                        <form id="appointmentForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customerName" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="customerName" name="customerName" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customerPhone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="customerPhone" name="customerPhone" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="customerEmail" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="customerEmail" name="customerEmail" required>
                            </div>

                            <div class="mb-3">
                                <label for="customerAddress" class="form-label">Home Address *</label>
                                <textarea class="form-control" id="customerAddress" name="customerAddress" rows="2" required placeholder="Street address, City, State, ZIP"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="appointmentDate" class="form-label">Preferred Date *</label>
                                    <input type="date" class="form-control" id="appointmentDate" name="appointmentDate" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Available Times *</label>
                                    <div id="timeSlots" class="time-slots">
                                        <div class="text-muted">Please select a date first</div>
                                    </div>
                                    <input type="hidden" id="selectedTime" name="selectedTime" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">Additional Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tell us about your project, specific requirements, or any questions you have"></textarea>
                            </div>

                            <input type="hidden" id="serviceType" name="serviceType" value="attic">
                            <input type="hidden" id="referrerPage" name="referrerPage" value="">

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <span class="btn-text">Schedule My Free Consultation</span>
                                <span class="btn-spinner d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Scheduling...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/financing-section.php'; ?>

    <section id="testimonials" class="testimonials animated-section">
        <div class="container">
            <h2>What Our Attic Clients Say</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p>"We never thought our attic could look this good! They transformed it into a beautiful master bedroom suite. Excellent work from start to finish!"</p>
                    <div class="author">
                        Brian W.
                        <span class="location">- Oak Lawn, IL</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"The team handled everything from permits to finishing touches. Our new home office in the attic is perfect for remote work. Couldn't be happier!"</p>
                    <div class="author">
                        Amanda K.
                        <span class="location">- Park Ridge, IL</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"Professional service and quality craftsmanship. They maximized every inch of space and created a beautiful room for our guests!"</p>
                    <div class="author">
                        Steven C.
                        <span class="location">- Skokie, IL</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
include 'includes/footer.php';
include 'includes/scripts.php';
?>
<script src="js/appointment.js"></script>
