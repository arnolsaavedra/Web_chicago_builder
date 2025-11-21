<?php
$page_title = "Chicago Basement Remodeling | Schedule Your Free Estimate";
$page_description = "Expert basement remodeling in Chicago, IL. Get a free, no-obligation estimate to transform your basement into usable living space.";
$current_page = "basement";

include 'includes/head.php';
include 'includes/navbar.php';
?>

    <header class="hero" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.pexels.com/photos/1643384/pexels-photo-1643384.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2') no-repeat center center/cover;">
        <div class="hero-content">
            <h1>Basement Remodeling in Chicago – Maximize Your Home's Potential</h1>
            <h2 style="font-size: 1.2rem;color: var(--white) !important;">Transform your unused basement into valuable living space for your family to enjoy.</h2>
            <a href="#appointment" class="btn btn-primary btn-lg">Schedule Your Free Estimate</a>
        </div>
    </header>

    <div class="sub-header">
        <p>Your Trusted Basement Finishing Experts in Chicagoland Since 2011</p>
    </div>

    <section id="services" class="services animated-section">
        <div class="container">
            <h2>Our Basement Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="icon"><i class="fas fa-home"></i></div>
                    <h3>Basement Finishing</h3>
                    <p>Complete basement transformations including framing, drywall, flooring, and finishing touches.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-couch"></i></div>
                    <h3>Rec Rooms & Entertainment</h3>
                    <p>Create the perfect space for family entertainment, home theaters, or game rooms.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-door-open"></i></div>
                    <h3>Basement Bedrooms & Baths</h3>
                    <p>Add functional living space with egress windows, bedrooms, and full bathrooms.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="appointment" class="appointment-section animated-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Schedule Your Free Consultation</h2>
                    <p class="lead">Ready to finish your basement? Book your free, no-obligation consultation today. Our experts will visit your home, assess your needs, and provide you with a detailed estimate.</p>
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

                            <input type="hidden" id="serviceType" name="serviceType" value="basement">
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
            <h2>What Our Basement Clients Say</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p>"They turned our dark, unused basement into a beautiful family room we use every day. The quality of work is exceptional and they stayed on budget!"</p>
                    <div class="author">
                        James M.
                        <span class="location">- Wheaton, IL</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"Professional, reliable, and great results. Our basement remodel added so much value to our home. Highly recommend The Smart House Guys!"</p>
                    <div class="author">
                        Patricia D.
                        <span class="location">- Downers Grove, IL</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"From design to completion, they made the process easy. Our finished basement is now our favorite space in the house!"</p>
                    <div class="author">
                        Thomas H.
                        <span class="location">- Elmhurst, IL</span>
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
