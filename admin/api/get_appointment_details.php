<?php
// admin/api/get_appointment_details.php
session_start();
require_once '../../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Verify authentication
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    json_response(false, 'Unauthorized');
}

try {
    if (empty($_GET['id'])) {
        json_response(false, 'Appointment ID required');
    }

    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        json_response(false, 'Database connection error');
    }

    $query = "SELECT * FROM appointments WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id']]);

    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        json_response(false, 'Appointment not found');
    }

    json_response(true, 'Details retrieved successfully', $appointment);

} catch (Exception $e) {
    error_log("Error in get_appointment_details.php: " . $e->getMessage());
    json_response(false, 'Error retrieving details: ' . $e->getMessage());
}
?>
