$(document).ready(function() {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    $('#appointmentDate').attr('min', today);

    // Load available times when date changes
    $('#appointmentDate').on('change', function() {
        const selectedDate = $(this).val();
        if (selectedDate) {
            loadAvailableTimes(selectedDate);
        }
    });

    // Handle time slot selection
    $(document).on('click', '.time-slot:not(.unavailable)', function() {
        $('.time-slot').removeClass('selected');
        $(this).addClass('selected');
        $('#selectedTime').val($(this).data('time'));
    });

    // Handle form submission
    $('#appointmentForm').on('submit', function(e) {
        e.preventDefault();

        if (!$('#selectedTime').val()) {
            showAlert('danger', 'Please select a preferred time for your appointment.');
            return;
        }

        const submitBtn = $(this).find('button[type="submit"]');
        const btnText = submitBtn.find('.btn-text');
        const btnSpinner = submitBtn.find('.btn-spinner');

        // Show loading state
        btnText.addClass('d-none');
        btnSpinner.removeClass('d-none');
        submitBtn.prop('disabled', true);

        // Capture referrer page
        $('#referrerPage').val(window.location.href);

        // Prepare form data
        const formData = {
            customerName: $('#customerName').val(),
            customerEmail: $('#customerEmail').val(),
            customerPhone: $('#customerPhone').val(),
            customerAddress: $('#customerAddress').val(),
            appointmentDate: $('#appointmentDate').val(),
            appointmentTime: $('#selectedTime').val(),
            notes: $('#notes').val(),
            serviceType: $('#serviceType').val(),
            referrerPage: $('#referrerPage').val()
        };

        // Submit form
        $.ajax({
            url: 'api/book_appointment.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#appointmentForm')[0].reset();
                    $('#timeSlots').html('<div class="text-muted">Please select a date first</div>');
                    $('#selectedTime').val('');

                    // Track form submission in Google Analytics
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'appointment_booked', {
                            'service_type': $('#serviceType').val(),
                            'page_location': window.location.href
                        });
                    }
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function() {
                showAlert('danger', 'Sorry, there was an error processing your request. Please try again or call us directly.');
            },
            complete: function() {
                // Reset button state
                btnText.removeClass('d-none');
                btnSpinner.addClass('d-none');
                submitBtn.prop('disabled', false);
            }
        });
    });

    // Phone number formatting
    $('#customerPhone').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length >= 6) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
        } else if (value.length >= 3) {
            value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2');
        }
        $(this).val(value);
    });
});

// Load available time slots
function loadAvailableTimes(date) {
    $('#timeSlots').html('<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"></div> Loading times...</div>');

    $.ajax({
        url: 'api/get_available_times.php',
        type: 'GET',
        data: { date: date },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                displayTimeSlots(response.data);
            } else {
                $('#timeSlots').html('<div class="text-danger">No available times for this date</div>');
            }
        },
        error: function() {
            $('#timeSlots').html('<div class="text-danger">Error loading available times</div>');
        }
    });
}

// Display time slots
function displayTimeSlots(times) {
    if (times.length === 0) {
        $('#timeSlots').html('<div class="text-muted">No available times for this date</div>');
        return;
    }

    let html = '';
    times.forEach(function(time) {
        const timeClass = time.available ? 'time-slot' : 'time-slot unavailable';
        html += `<div class="${timeClass}" data-time="${time.time}">${time.display}</div>`;
    });

    $('#timeSlots').html(html);
    $('#selectedTime').val(''); // Reset selected time
}

// Show alert message
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('#alert-container').html(alertHtml);

    // Auto-dismiss success alerts after 5 seconds
    if (type === 'success') {
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Scroll to alert
    $('html, body').animate({
        scrollTop: $('#alert-container').offset().top - 100
    }, 300);
}
