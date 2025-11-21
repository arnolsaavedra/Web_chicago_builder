<?php
$page_title = "Chicago Deck Building | Schedule Your Free Estimate";
$page_description = "Expert deck building in Chicago, IL. Get a free, no-obligation estimate for your custom outdoor deck construction.";
$current_page = "deck";

include 'includes/head.php';
include 'includes/navbar.php';
?>

    <header class="hero" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.pexels.com/photos/1396132/pexels-photo-1396132.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2') no-repeat center center/cover;">
        <div class="hero-content">
            <h1>Deck Building in Chicago – Expand Your Outdoor Living</h1>
            <h2 style="font-size: 1.2rem;color: var(--white) !important;">Custom deck design and construction to enhance your home's outdoor entertainment space.</h2>
            <a href="#appointment" class="btn btn-primary btn-lg">Schedule Your Free Estimate</a>
        </div>
    </header>

    <div class="sub-header">
        <p>Your Trusted Deck Building Experts in Chicagoland Since 2011</p>
    </div>

    <section id="services" class="services animated-section">
        <div class="container">
            <h2>Our Deck Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="icon"><i class="fas fa-hammer"></i></div>
                    <h3>Custom Deck Design</h3>
                    <p>Personalized deck designs that complement your home and fit your lifestyle.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-tree"></i></div>
                    <h3>Wood & Composite Decks</h3>
                    <p>Quality materials including pressure-treated lumber, cedar, and low-maintenance composite options.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-tools"></i></div>
                    <h3>Deck Repair & Restoration</h3>
                    <p>Expert repairs and refinishing to restore your existing deck to like-new condition.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="appointment" class="appointment-section animated-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Schedule Your Free Consultation</h2>
                    <p class="lead">Ready to build your dream deck? Book your free, no-obligation consultation today. Our experts will visit your home, assess your needs, and provide you with a detailed estimate.</p>
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

                            <input type="hidden" id="serviceType" name="serviceType" value="deck">
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
            <h2>What Our Deck Clients Say</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p>"Our new composite deck is absolutely beautiful and requires almost no maintenance. The team was professional and the quality of construction is outstanding!"</p>
                    <div class="author">
                        Michael G.
                        <span class="location">- Lombard, IL</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"They built us the perfect deck for entertaining. Great design suggestions and expert craftsmanship. We use it every day in the summer!"</p>
                    <div class="author">
                        Rachel B.
                        <span class="location">- Glen Ellyn, IL</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"From permits to completion, everything was handled professionally. Our backyard is now our favorite place to relax!"</p>
                    <div class="author">
                        Daniel T.
                        <span class="location">- Hinsdale, IL</span>
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
