<?php
// admin/api/update_business_hours.php
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
    if (empty($_POST['hours'])) {
        json_response(false, 'Business hours data required');
    }

    $hours = json_decode($_POST['hours'], true);

    if (!$hours) {
        json_response(false, 'Invalid data format');
    }

    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        json_response(false, 'Database connection error');
    }

    // Start transaction
    $db->beginTransaction();

    try {
        foreach ($hours as $hour) {
            $updateQuery = "UPDATE business_hours
                            SET start_time = ?,
                                end_time = ?,
                                is_active = ?
                            WHERE day_of_week = ?";

            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->execute([
                $hour['start_time'],
                $hour['end_time'],
                $hour['is_active'] ? 1 : 0,
                $hour['day_of_week']
            ]);
        }

        // Commit transaction
        $db->commit();
        json_response(true, 'Business hours updated successfully');

    } catch (Exception $e) {
        // Rollback transaction
        $db->rollback();
        throw $e;
    }

} catch (Exception $e) {
    error_log("Error in update_business_hours.php: " . $e->getMessage());
    json_response(false, 'Error updating business hours: ' . $e->getMessage());
}
?>