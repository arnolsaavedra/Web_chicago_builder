<?php
$page_title = "Chicago Kitchen Remodeling | Schedule Your Free Estimate";
$page_description = "Expert kitchen remodeling in Chicago, IL. Get a free, no-obligation estimate for your dream kitchen transformation.";
$current_page = "kitchen";

include 'includes/head.php';
include 'includes/navbar.php';
?>

    <header class="hero" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.pexels.com/photos/2724749/pexels-photo-2724749.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2') no-repeat center center/cover;">
        <div class="hero-content">
            <h1>Kitchen Remodeling in Chicago – Transform Your Heart of the Home</h1>
            <h2 style="font-size: 1.2rem;color: var(--white) !important;">Custom kitchen designs that combine beauty, functionality, and lasting value for your home.</h2>
            <a href="#appointment" class="btn btn-primary btn-lg">Schedule Your Free Estimate</a>
        </div>
    </header>

    <div class="sub-header">
        <p>Your Trusted Kitchen Remodeling Experts in Chicagoland Since 2011</p>
    </div>

    <section id="services" class="services animated-section">
        <div class="container">
            <h2>Our Kitchen Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="icon"><i class="fas fa-drafting-compass"></i></div>
                    <h3>Custom Kitchen Design</h3>
                    <p>From modern to traditional, we create custom designs that match your style and maximize your space.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-hammer"></i></div>
                    <h3>Cabinet Installation</h3>
                    <p>High-quality custom and semi-custom cabinetry with expert installation for a perfect fit.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-border-all"></i></div>
                    <h3>Countertops & Backsplash</h3>
                    <p>Beautiful granite, quartz, and marble countertops with designer backsplash options.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="appointment" class="appointment-section animated-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Schedule Your Free Consultation</h2>
                    <p class="lead">Ready to transform your kitchen? Book your free, no-obligation consultation today. Our experts will visit your home, assess your needs, and provide you with a detailed estimate.</p>
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

                            <input type="hidden" id="serviceType" name="serviceType" value="kitchen">
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
            <h2>What Our Kitchen Clients Say</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p>"Our kitchen transformation exceeded all expectations. The team was professional, on-time, and the craftsmanship is outstanding. We love cooking in our new space!"</p>
                    <div class="author">
                        Jennifer M.
                        <span class="location">- Oak Park, IL</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"From design to installation, the entire process was smooth. They listened to our needs and delivered exactly what we wanted. Our kitchen is now the centerpiece of our home!"</p>
                    <div class="author">
                        David K.
                        <span class="location">- Evanston, IL</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"We couldn't be happier with our new kitchen! The attention to detail and quality of work is exceptional. Highly recommend The Smart House Guys for any remodeling project."</p>
                    <div class="author">
                        Lisa T.
                        <span class="location">- Naperville, IL</span>
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
