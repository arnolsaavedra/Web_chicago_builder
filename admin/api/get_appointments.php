<?php
// admin/api/get_appointments.php
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
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        json_response(false, 'Database connection error');
    }

    $whereConditions = [];
    $params = [];

    // Optional filters
    if (!empty($_GET['status'])) {
        $whereConditions[] = "status = ?";
        $params[] = $_GET['status'];
    }
    
    if (!empty($_GET['dateFrom'])) {
        $whereConditions[] = "appointment_date >= ?";
        $params[] = $_GET['dateFrom'];
    }
    
    if (!empty($_GET['dateTo'])) {
        $whereConditions[] = "appointment_date <= ?";
        $params[] = $_GET['dateTo'];
    }
    
    $whereClause = empty($whereConditions) ? '' : 'WHERE ' . implode(' AND ', $whereConditions);

    $query = "SELECT id, customer_name, customer_email, customer_phone, customer_address,
                     appointment_date, appointment_time, status, notes, created_at, updated_at
              FROM appointments
              {$whereClause}
              ORDER BY appointment_date DESC, appointment_time DESC";

    $stmt = $db->prepare($query);
    $stmt->execute($params);

    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    json_response(true, 'Appointments retrieved successfully', $appointments);

} catch (Exception $e) {
    error_log("Error in get_appointments.php: " . $e->getMessage());
    json_response(false, 'Error retrieving appointments: ' . $e->getMessage());
}
?>