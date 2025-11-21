<?php
// Page variables
$page_title = "The Smart House Guys - Chicago's Premier Home Remodeling & Window Experts Since 2011";
$page_description = "Building Performance Institute CERTIFIED. 15+ years experience in home improvement, window installation, kitchen, bathroom, basement remodeling in Chicago & suburbs.";
$current_page = "index";

// Include head section
include 'includes/head.php';

// Include navigation
include 'includes/navbar.php';
?>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-content">
            <h1>Chicago's Smartest Home Remodeling & Window Experts</h1>
            <p>Building Performance Institute CERTIFIED PROFESSIONALS with 15+ years of experience transforming homes across Chicagoland since 2011</p>

            <div class="badge-container">
                <div class="trust-badge">
                    <i class="fas fa-certificate"></i>
                    <span>BPI Certified</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-star"></i>
                    <span>5 Star Rated</span>
                </div>
                <div class="trust-badge">
                    <i class="fas fa-tools"></i>
                    <span>15+ Years Experience</span>
                </div>
            </div>

            <a href="#contact" class="btn btn-primary btn-lg">Schedule Your Free Estimate</a>
        </div>
    </header>

    <div class="sub-header">
        <p>Serving Chicago & All Surrounding Suburbs | Licensed, Insured & Certified</p>
    </div>

    <!-- Services Section -->
    <section id="services" class="services animated-section">
        <div class="container">
            <h2>Our Expert Services</h2>
            <p style="text-align: center; max-width: 700px; margin: 0 auto 40px;">From energy-efficient windows to complete home remodels, we deliver exceptional craftsmanship and customer service on every project.</p>

            <div class="services-grid">
                <div class="service-card">
                    <div class="icon"><i class="fas fa-window-maximize"></i></div>
                    <h3>High-Efficiency Windows & Doors</h3>
                    <p>Expert installation of energy-efficient windows and doors that reduce costs and increase comfort year-round.</p>
                    <a href="windows.php" class="btn-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="service-card">
                    <div class="icon"><i class="fas fa-utensils"></i></div>
                    <h3>Kitchen Remodeling</h3>
                    <p>Transform your kitchen into a beautiful, functional space that brings your family together.</p>
                    <a href="kitchen-remodeling.php" class="btn-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="service-card">
                    <div class="icon"><i class="fas fa-bath"></i></div>
                    <h3>Bathroom Remodeling</h3>
                    <p>Create your dream bathroom with custom designs, quality fixtures, and expert installation.</p>
                    <a href="bathroom-remodeling.php" class="btn-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="service-card">
                    <div class="icon"><i class="fas fa-home"></i></div>
                    <h3>Basement Remodeling</h3>
                    <p>Unlock valuable living space with a professionally finished basement customized to your needs.</p>
                    <a href="basement-remodeling.php" class="btn-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="service-card">
                    <div class="icon"><i class="fas fa-warehouse"></i></div>
                    <h3>Attic Remodeling</h3>
                    <p>Convert your attic into a beautiful bedroom, office, or living space with proper insulation and design.</p>
                    <a href="attic-remodeling.php" class="btn-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="service-card">
                    <div class="icon"><i class="fas fa-layer-group"></i></div>
                    <h3>Deck Building & Repair</h3>
                    <p>Expand your outdoor living with custom deck construction and professional deck repair services.</p>
                    <a href="deck-building.php" class="btn-link">Learn More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section id="why-us" class="why-choose-us animated-section">
        <div class="container">
            <h2>Why Choose The Smart House Guys?</h2>
            <p style="text-align: center; color: var(--white); max-width: 800px; margin: 0 auto 40px;">We're not just contractors – we're Building Performance Institute certified professionals committed to excellence in every project.</p>

            <div class="why-grid">
                <div class="why-card">
                    <div class="icon"><i class="fas fa-award"></i></div>
                    <h4>BPI Certified Professionals</h4>
                    <p>Building Analyst & Building Envelope Professional certifications ensure top-quality work.</p>
                </div>

                <div class="why-card">
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <h4>Since 2011</h4>
                    <p>Over a decade of satisfied customers and successful projects across Chicagoland.</p>
                </div>

                <div class="why-card">
                    <div class="icon"><i class="fas fa-hard-hat"></i></div>
                    <h4>15+ Years Experience</h4>
                    <p>Our team brings 15+ years of construction expertise to every project.</p>
                </div>

                <div class="why-card">
                    <div class="icon"><i class="fas fa-star"></i></div>
                    <h4>5 Star Rated</h4>
                    <p>Consistently rated 5 out of 5 by our satisfied customers.</p>
                </div>

                <div class="why-card">
                    <div class="icon"><i class="fas fa-shield-alt"></i></div>
                    <h4>Licensed & Insured</h4>
                    <p>Fully licensed, bonded, and insured for your peace of mind.</p>
                </div>

                <div class="why-card">
                    <div class="icon"><i class="fas fa-handshake"></i></div>
                    <h4>Customer-Focused</h4>
                    <p>We prioritize communication, quality, and your complete satisfaction.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Certifications Section -->
    <section class="certifications animated-section">
        <div class="container">
            <h2>Our Professional Certifications</h2>
            <p style="max-width: 700px; margin: 0 auto 40px;">We hold the highest certifications in building performance and energy efficiency.</p>

            <div class="cert-grid">
                <div class="cert-badge">
                    <i class="fas fa-graduation-cap"></i>
                    <h4>Mechanical Engineer</h4>
                </div>
                <div class="cert-badge">
                    <i class="fas fa-user-tie"></i>
                    <h4>Building Analyst Professional</h4>
                </div>
                <div class="cert-badge">
                    <i class="fas fa-building"></i>
                    <h4>Building Envelope Professional</h4>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/financing-section.php'; ?>

    <!-- Reviews Section -->
    <section id="reviews" class="reviews animated-section">
        <div class="container">
            <h2>What Our Clients Say</h2>
            <div class="reviews-grid">
                <div class="review-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"The Smart House Guys transformed our kitchen beyond our expectations. Professional, on-time, and the quality is outstanding. Worth every penny!"</p>
                    <div class="reviewer">Jennifer M. - Oak Park, IL</div>
                </div>

                <div class="review-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"Our new windows have made such a difference! The house is warmer in winter, cooler in summer, and our energy bills dropped significantly. Highly recommend!"</p>
                    <div class="reviewer">Robert T. - Evanston, IL</div>
                </div>

                <div class="review-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"From start to finish, the basement remodeling process was smooth. They communicated every step, stayed on budget, and delivered exceptional craftsmanship."</p>
                    <div class="reviewer">Maria G. - Chicago, IL</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="cta-section animated-section">
        <div class="container">
            <h2>Ready to Transform Your Home?</h2>
            <p>Get your free, no-obligation estimate today. Typical projects range from $0 - $30,000.</p>

            <div class="cta-buttons">
                <a href="tel:7735529347" class="btn-cta">
                    <i class="fas fa-phone"></i>
                    Call (773) 552-9347
                </a>
                <a href="windows.php#appointment" class="btn-cta btn-cta-secondary">
                    <i class="fas fa-calendar-check"></i>
                    Schedule Online
                </a>
            </div>
        </div>
    </section>

    <!-- Areas Served Section -->
    <section class="areas-served animated-section">
        <div class="container">
            <h2>Proudly Serving Chicago & Surrounding Areas</h2>
            <div class="areas-grid">
                <div class="area-item">Arlington Heights</div>
                <div class="area-item">Bellwood</div>
                <div class="area-item">Bensenville</div>
                <div class="area-item">Berwyn</div>
                <div class="area-item">Broadview</div>
                <div class="area-item">Brookfield</div>
                <div class="area-item">Chicago</div>
                <div class="area-item">Cicero</div>
                <div class="area-item">Des Plaines</div>
                <div class="area-item">Elk Grove Village</div>
                <div class="area-item">Elmhurst</div>
                <div class="area-item">Elmwood Park</div>
                <div class="area-item">Evanston</div>
                <div class="area-item">Forest Park</div>
                <div class="area-item">Glenview</div>
                <div class="area-item">Hinsdale</div>
                <div class="area-item">Lincoln Park</div>
                <div class="area-item">Oak Park</div>
                <div class="area-item">Skokie</div>
                <div class="area-item">& Many More</div>
            </div>
            <p style="text-align: center; margin-top: 30px;">
                <a href="areas-served.php" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">
                    View All Service Areas <i class="fas fa-arrow-right"></i>
                </a>
            </p>
        </div>
    </section>

<?php
// Include footer
include 'includes/footer.php';

// Include scripts
include 'includes/scripts.php';
?>
