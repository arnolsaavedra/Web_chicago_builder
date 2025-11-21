<?php
// admin/api/update_appointment.php
session_start();
require_once '../../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Method not allowed');
}

// Verify authentication
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    json_response(false, 'Unauthorized');
}

try {
    $required_fields = ['id', 'appointment_date', 'appointment_time', 'status'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            json_response(false, 'Missing required fields: ' . implode(', ', $required_fields));
        }
    }

    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        json_response(false, 'Database connection error');
    }

    // Validate that the appointment exists
    $checkQuery = "SELECT id FROM appointments WHERE id = ?";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute([$_POST['id']]);

    if ($checkStmt->rowCount() === 0) {
        json_response(false, 'Appointment not found');
    }

    // Check for scheduling conflicts (only if date/time changed)
    $conflictQuery = "SELECT id FROM appointments
                      WHERE appointment_date = ?
                      AND appointment_time = ?
                      AND id != ?
                      AND status != 'cancelled'";

    $conflictStmt = $db->prepare($conflictQuery);
    $conflictStmt->execute([$_POST['appointment_date'], $_POST['appointment_time'], $_POST['id']]);

    if ($conflictStmt->rowCount() > 0) {
        json_response(false, 'An appointment already exists at that time');
    }

    // Update the appointment
    $updateQuery = "UPDATE appointments
                    SET appointment_date = ?,
                        appointment_time = ?,
                        status = ?,
                        notes = ?,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = ?";

    $updateStmt = $db->prepare($updateQuery);
    $result = $updateStmt->execute([
        $_POST['appointment_date'],
        $_POST['appointment_time'],
        $_POST['status'],
        $_POST['notes'] ?? '',
        $_POST['id']
    ]);

    if ($result) {
        json_response(true, 'Appointment updated successfully');
    } else {
        json_response(false, 'Error updating appointment');
    }

} catch (Exception $e) {
    error_log("Error in update_appointment.php: " . $e->getMessage());
    json_response(false, 'Error updating appointment: ' . $e->getMessage());
}
?>