<?php
// api/book_appointment.php - API para agendar nuevas citas

require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Method not allowed');
}

try {
    // Validate required fields
    $required_fields = ['customerName', 'customerEmail', 'customerPhone', 'customerAddress', 'appointmentDate', 'appointmentTime'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            json_response(false, 'All fields marked with * are required');
        }
    }
    
    // Sanitizar y validar datos
    $customerName = sanitize_input($_POST['customerName']);
    $customerEmail = sanitize_input($_POST['customerEmail']);
    $customerPhone = sanitize_input($_POST['customerPhone']);
    $customerAddress = sanitize_input($_POST['customerAddress']);
    $appointmentDate = sanitize_input($_POST['appointmentDate']);
    $appointmentTime = sanitize_input($_POST['appointmentTime']);
    $notes = sanitize_input($_POST['notes'] ?? '');

    // Capturar información de tracking
    $serviceType = sanitize_input($_POST['serviceType'] ?? 'general');

    // Extraer solo el nombre de la página (no la URL completa)
    $referrerPageFull = sanitize_input($_POST['referrerPage'] ?? $_SERVER['HTTP_REFERER'] ?? '');
    $referrerPage = '';
    if ($referrerPageFull) {
        // Extraer solo el nombre del archivo (ej: /windows.php -> windows)
        $pathInfo = parse_url($referrerPageFull, PHP_URL_PATH);
        $filename = basename($pathInfo, '.php');
        $referrerPage = $filename ?: 'unknown';
    }
    
    // Specific validations
    if (!validate_email($customerEmail)) {
        json_response(false, 'Please enter a valid email address');
    }

    if (!validate_phone($customerPhone)) {
        json_response(false, 'Please enter a valid phone number');
    }

    // Validate date (cannot be in the past)
    $selectedDate = new DateTime($appointmentDate);
    $today = new DateTime('today');

    if ($selectedDate < $today) {
        json_response(false, 'The selected date cannot be in the past');
    }
    
    // Validar formato de hora
    if (!preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $appointmentTime)) {
        //json_response(false, 'Formato de hora inválido');
    }
    
    // Connect to database
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        json_response(false, 'Database connection error');
    }

    // Check if appointment already exists at that date and time
    $checkQuery = "SELECT id FROM appointments
                   WHERE appointment_date = :date
                   AND appointment_time = :time
                   AND status != 'cancelled'";

    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':date', $appointmentDate);
    $checkStmt->bindParam(':time', $appointmentTime);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        json_response(false, 'Sorry, that time slot is already booked. Please select another time.');
    }
    
    // Verify if the day is within business hours
    $dayOfWeek = $selectedDate->format('N'); // 1 = Monday, 7 = Sunday

    // Consultar horarios de negocio desde la tabla business_hours
    $hoursQuery = "SELECT day_of_week, start_time, end_time, is_active
                   FROM business_hours
                   WHERE day_of_week = :day_of_week";

    $hoursStmt = $db->prepare($hoursQuery);
    $hoursStmt->bindParam(':day_of_week', $dayOfWeek);
    $hoursStmt->execute();

    $businessHour = $hoursStmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si hay configuración para este día
    if (!$businessHour) {
        json_response(false, 'No business hours configured for this day');
    }

    // Verificar si trabajamos en este día
    if (!$businessHour['is_active']) {
        json_response(false, 'We are closed on this day');
    }

    // Verify if the time is within business hours
    $appointmentTimeObj = new DateTime($appointmentTime);
    $startTime = new DateTime($businessHour['start_time']);
    $endTime = new DateTime($businessHour['end_time']);

    if ($appointmentTimeObj < $startTime || $appointmentTimeObj >= $endTime) {
        json_response(false, 'The selected time is outside our business hours');
    }
    
    // Insertar la nueva cita
    $insertQuery = "INSERT INTO appointments
                    (customer_name, customer_email, customer_phone, customer_address,
                     appointment_date, appointment_time, notes, service_type, referrer_page, status)
                    VALUES
                    (:customer_name, :customer_email, :customer_phone, :customer_address,
                     :appointment_date, :appointment_time, :notes, :service_type, :referrer_page, 'pending')";

    $insertStmt = $db->prepare($insertQuery);
    $insertStmt->bindParam(':customer_name', $customerName);
    $insertStmt->bindParam(':customer_email', $customerEmail);
    $insertStmt->bindParam(':customer_phone', $customerPhone);
    $insertStmt->bindParam(':customer_address', $customerAddress);
    $insertStmt->bindParam(':appointment_date', $appointmentDate);
    $insertStmt->bindParam(':appointment_time', $appointmentTime);
    $insertStmt->bindParam(':notes', $notes);
    $insertStmt->bindParam(':service_type', $serviceType);
    $insertStmt->bindParam(':referrer_page', $referrerPage);
    
    if ($insertStmt->execute()) {
        $appointmentId = $db->lastInsertId();
        
        // Format date for display
        $formattedDate = $selectedDate->format('l, F j, Y');
        $formattedTime = $appointmentTimeObj->format('g:i A');

        // Optional: Send confirmation email (requires SMTP configuration)
        // send_confirmation_email($customerEmail, $customerName, $formattedDate, $formattedTime);

        json_response(true, "Perfect! Your free consultation has been scheduled for {$formattedDate} at {$formattedTime}. You will receive an email confirmation shortly.", [
            'appointment_id' => $appointmentId,
            'date' => $formattedDate,
            'time' => $formattedTime
        ]);
    } else {
        json_response(false, 'Error scheduling appointment. Please try again.');
    }

} catch (Exception $e) {
    error_log("Error in book_appointment.php: " . $e->getMessage());
    json_response(false, 'An error has occurred. Please try again or contact us directly.');
}

// Función opcional para enviar email de confirmación
function send_confirmation_email($email, $name, $date, $time) {
    // Implementar envío de email usando PHPMailer o similar
    // Esta función requiere configuración adicional de SMTP
    
    $subject = "Confirmación de Cita - " . BUSINESS_NAME;
    $message = "
        Estimado/a {$name},
        
        Su consulta gratuita ha sido confirmada para:
        Fecha: {$date}
        Hora: {$time}
        
        Nos pondremos en contacto con usted antes de la cita para confirmar.
        
        Si necesita reagendar o tiene alguna pregunta, por favor contáctenos al " . BUSINESS_PHONE . "
        
        ¡Esperamos conocerle pronto!
        
        Saludos,
        El equipo de " . BUSINESS_NAME;
    
    // Usar mail() básico o PHPMailer para envío profesional
    // mail($email, $subject, $message);
}
?>