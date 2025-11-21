<?php
// admin/api/delete_appointment.php
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
    if (empty($_POST['id'])) {
        json_response(false, 'Appointment ID required');
    }

    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        json_response(false, 'Database connection error');
    }

    // Verify that the appointment exists
    $checkQuery = "SELECT id FROM appointments WHERE id = ?";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute([$_POST['id']]);

    if ($checkStmt->rowCount() === 0) {
        json_response(false, 'Appointment not found');
    }

    // Delete the appointment
    $deleteQuery = "DELETE FROM appointments WHERE id = ?";
    $deleteStmt = $db->prepare($deleteQuery);

    if ($deleteStmt->execute([$_POST['id']])) {
        json_response(true, 'Appointment deleted successfully');
    } else {
        json_response(false, 'Error deleting appointment');
    }

} catch (Exception $e) {
    error_log("Error in delete_appointment.php: " . $e->getMessage());
    json_response(false, 'Error deleting appointment: ' . $e->getMessage());
}
?>