<?php
$page_title = "Chicago Window Installation | Schedule Your Free Estimate";
$page_description = "Expert window installation in Chicago, IL. Get a free, no-obligation estimate for beautiful, energy-efficient windows.";
$current_page = "windows";

include 'includes/head.php';
include 'includes/navbar.php';
?>

    <header class="hero">
        <div class="hero-content">
            <h1>Window Replacement & Installation in Chicago – Energy-Efficient Solutions</h1>
            <h2 style="font-size: 1.2rem;color: var(--white) !important;">Improve comfort, reduce noise, and increase home value with our professional window services.</h2>
            <a href="#appointment" class="btn btn-primary btn-lg">Schedule Your Free Estimate</a>
        </div>
    </header>

    <div class="sub-header">
        <p>Your Trusted Local Window Installers in Chicagoland</p>
    </div>

    <section id="services" class="services animated-section">
        <div class="container">
            <h2>Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="icon"><i class="fas fa-home"></i></div>
                    <h3>Window Replacement</h3>
                    <p>Upgrade your old, inefficient windows with modern, high-performance replacements.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-tools"></i></div>
                    <h3>New Window Installation</h3>
                    <p>Perfect for new constructions or home additions. We ensure a perfect fit every time.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><i class="fas fa-leaf"></i></div>
                    <h3>Energy-Efficient Windows</h3>
                    <p>Lower your heating and cooling costs and stay comfortable through every Chicago season.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="appointment" class="appointment-section animated-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>Schedule Your Free Consultation</h2>
                    <p class="lead">Ready to transform your home? Book your free, no-obligation consultation today. Our experts will visit your home, assess your needs, and provide you with a detailed estimate.</p>
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

                            <input type="hidden" id="serviceType" name="serviceType" value="windows">
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

    <section class="video-section animated-section">
        <div class="container">
            <h2>See Our Professional Process</h2>
            <p>We combine craftsmanship with the best materials. Watch how we ensure a clean, efficient, and perfect installation for your home.</p>
            <div class="video-wrapper">
                <iframe src="https://www.youtube.com/embed/ScXXaB-d_tA?si=m5S8bM6E5yqj1sE8" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </section>

    <section id="gallery" class="gallery animated-section">
        <div class="container">
            <h2>Work We've Done in Chicago</h2>
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://images.pexels.com/photos/2251206/pexels-photo-2251206.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Modern kitchen window">
                    <div class="overlay">Lincoln Park Project</div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.pexels.com/photos/2724749/pexels-photo-2724749.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Bright living room window">
                    <div class="overlay">Wicker Park Renovation</div>
                </div>
                <div class="gallery-item">
                    <img src="https://images.pexels.com/photos/1743231/pexels-photo-1743231.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="Bay window in a classic home">
                    <div class="overlay">Evanston Home Upgrade</div>
                </div>
            </div>
        </div>
    </section>

    <section class="video-section animated-section">
        <div class="container">
            <h2>Hear From Our Happy Customers</h2>
            <p>Your neighbors trust us to enhance their homes. See what they have to say about their experience.</p>
            <div class="video-wrapper">
                <iframe src="https://www.youtube.com/embed/iAzoA3eI50M?si=GzK1J4FzL5pP6tA" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </section>

    <?php include 'includes/financing-section.php'; ?>

    <section id="testimonials" class="testimonials animated-section">
        <div class="container">
            <h2>What Our Clients Say</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p>"The entire process was seamless. The crew was professional, clean, and the new windows look fantastic. Our energy bill has already dropped. Highly recommend!"</p>
                    <div class="author">
                        Sarah J.
                        <span class="location">- Lakeview, Chicago</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"I was worried about the mess, but they left my home cleaner than they found it. The quality of the windows is top-notch. A truly professional service from start to finish."</p>
                    <div class="author">
                        Mike R.
                        <span class="location">- Naperville, IL</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"Finally, no more drafts! Our home is so much quieter and more comfortable now. Getting the free estimate was easy and there was no pressure at all."</p>
                    <div class="author">
                        Emily T.
                        <span class="location">- Lincoln Park, Chicago</span>
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
