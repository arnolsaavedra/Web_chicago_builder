<?php
// admin/api/get_business_hours.php
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

    $query = "SELECT day_of_week, start_time, end_time, is_active
              FROM business_hours
              ORDER BY day_of_week";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $hours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    json_response(true, 'Business hours retrieved successfully', $hours);

} catch (Exception $e) {
    error_log("Error in get_business_hours.php: " . $e->getMessage());
    json_response(false, 'Error retrieving business hours: ' . $e->getMessage());
}
?>
