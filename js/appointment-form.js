/**
 * Appointment Form Component
 * The Smart House Guys - Reusable Appointment Booking System
 *
 * Usage:
 * <div id="appointmentFormContainer"
 *      data-service-type="windows"
 *      data-service-name="Window Installation">
 * </div>
 * <script src="js/appointment-form.js"></script>
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        apiEndpoint: 'api/book_appointment.php',
        timeSlotsEndpoint: 'api/get_available_times.php',
        minDate: new Date().toISOString().split('T')[0]
    };

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initAppointmentForm();
    });

    function initAppointmentForm() {
        const container = document.getElementById('appointmentFormContainer');
        if (!container) {
            console.warn('Appointment form container not found');
            return;
        }

        const serviceType = container.dataset.serviceType || 'general';
        const serviceName = container.dataset.serviceName || 'Service';

        // Render the form
        container.innerHTML = generateFormHTML(serviceType, serviceName);

        // Initialize form functionality
        setupFormHandlers(serviceType);
    }

    function generateFormHTML(serviceType, serviceName) {
        return `
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
                                <li class="mb-2"><i class="fas fa-check-circle text-warning me-2"></i> Discuss financing options</li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <div class="appointment-form">
                                <h3 class="text-center mb-4 text-primary">Book Your ${serviceName} Appointment</h3>

                                <div id="alert-container"></div>

                                <form id="appointmentForm">
                                    <!-- Hidden tracking fields -->
                                    <input type="hidden" id="serviceType" name="serviceType" value="${serviceType}">
                                    <input type="hidden" id="referrerPage" name="referrerPage" value="">

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
                                            <input type="date" class="form-control" id="appointmentDate" name="appointmentDate" required min="${CONFIG.minDate}">
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
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tell us about your ${serviceName.toLowerCase()} project, specific requirements, or any questions you have"></textarea>
                                    </div>

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
        `;
    }

    function setupFormHandlers(serviceType) {
        // Set referrer page
        document.getElementById('referrerPage').value = window.location.href;

        // Phone number formatting
        const phoneInput = document.getElementById('customerPhone');
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 3) {
                value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2');
            }
            this.value = value;
        });

        // Date change handler
        const dateInput = document.getElementById('appointmentDate');
        dateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                loadAvailableTimes(selectedDate);
            }
        });

        // Time slot selection handler (using event delegation)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('time-slot') && !e.target.classList.contains('unavailable')) {
                document.querySelectorAll('.time-slot').forEach(slot => slot.classList.remove('selected'));
                e.target.classList.add('selected');
                document.getElementById('selectedTime').value = e.target.dataset.time;
            }
        });

        // Form submission handler
        const form = document.getElementById('appointmentForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleFormSubmit(serviceType);
        });
    }

    function loadAvailableTimes(date) {
        const timeSlotsContainer = document.getElementById('timeSlots');
        timeSlotsContainer.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"></div> Loading times...</div>';

        fetch(`${CONFIG.timeSlotsEndpoint}?date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayTimeSlots(data.data);
                } else {
                    timeSlotsContainer.innerHTML = '<div class="text-danger">No available times for this date</div>';
                }
            })
            .catch(error => {
                console.error('Error loading time slots:', error);
                timeSlotsContainer.innerHTML = '<div class="text-danger">Error loading available times</div>';
            });
    }

    function displayTimeSlots(times) {
        const timeSlotsContainer = document.getElementById('timeSlots');

        if (times.length === 0) {
            timeSlotsContainer.innerHTML = '<div class="text-muted">No available times for this date</div>';
            return;
        }

        let html = '';
        times.forEach(time => {
            const timeClass = time.available ? 'time-slot' : 'time-slot unavailable';
            html += `<div class="${timeClass}" data-time="${time.time}">${time.display}</div>`;
        });

        timeSlotsContainer.innerHTML = html;
        document.getElementById('selectedTime').value = ''; // Reset selected time
    }

    function handleFormSubmit(serviceType) {
        const selectedTime = document.getElementById('selectedTime').value;

        if (!selectedTime) {
            showAlert('danger', 'Please select a preferred time for your appointment.');
            return;
        }

        const submitBtn = document.querySelector('#appointmentForm button[type="submit"]');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnSpinner = submitBtn.querySelector('.btn-spinner');

        // Show loading state
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
        submitBtn.disabled = true;

        // Prepare form data
        const formData = new FormData();
        formData.append('customerName', document.getElementById('customerName').value);
        formData.append('customerEmail', document.getElementById('customerEmail').value);
        formData.append('customerPhone', document.getElementById('customerPhone').value);
        formData.append('customerAddress', document.getElementById('customerAddress').value);
        formData.append('appointmentDate', document.getElementById('appointmentDate').value);
        formData.append('appointmentTime', selectedTime);
        formData.append('notes', document.getElementById('notes').value);
        formData.append('serviceType', serviceType);
        formData.append('referrerPage', window.location.href);

        // Submit form
        fetch(CONFIG.apiEndpoint, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                document.getElementById('appointmentForm').reset();
                document.getElementById('timeSlots').innerHTML = '<div class="text-muted">Please select a date first</div>';
                document.getElementById('selectedTime').value = '';

                // Track in Google Analytics
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'appointment_booked', {
                        'service_type': serviceType,
                        'page_location': window.location.href
                    });
                }
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            showAlert('danger', 'Sorry, there was an error processing your request. Please try again or call us directly at (773) 552-9347.');
        })
        .finally(() => {
            // Reset button state
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
            submitBtn.disabled = false;
        });
    }

    function showAlert(type, message) {
        const alertContainer = document.getElementById('alert-container');
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        alertContainer.innerHTML = alertHtml;

        // Auto-dismiss success alerts after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }

        // Scroll to alert
        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

})();
