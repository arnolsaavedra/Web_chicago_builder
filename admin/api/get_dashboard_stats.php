<?php
// admin/api/get_dashboard_stats.php
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

    $today = date('Y-m-d');
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $monthStart = date('Y-m-01');

    // Appointments for today
    $todayQuery = "SELECT COUNT(*) as count FROM appointments
                   WHERE appointment_date = ? AND status != 'cancelled'";
    $todayStmt = $db->prepare($todayQuery);
    $todayStmt->execute([$today]);
    $todayCount = $todayStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Pending appointments
    $pendingQuery = "SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'";
    $pendingStmt = $db->prepare($pendingQuery);
    $pendingStmt->execute();
    $pendingCount = $pendingStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Appointments for this week
    $weekQuery = "SELECT COUNT(*) as count FROM appointments
                  WHERE appointment_date >= ? AND status != 'cancelled'";
    $weekStmt = $db->prepare($weekQuery);
    $weekStmt->execute([$weekStart]);
    $weekCount = $weekStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Appointments for this month
    $monthQuery = "SELECT COUNT(*) as count FROM appointments
                   WHERE appointment_date >= ? AND status != 'cancelled'";
    $monthStmt = $db->prepare($monthQuery);
    $monthStmt->execute([$monthStart]);
    $monthCount = $monthStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Recent appointments (last 10)
    $recentQuery = "SELECT id, customer_name, appointment_date, appointment_time, status 
                    FROM appointments 
                    ORDER BY created_at DESC 
                    LIMIT 10";
    $recentStmt = $db->prepare($recentQuery);
    $recentStmt->execute();
    $recent = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
    
    json_response(true, 'Statistics retrieved successfully', [
        'today' => $todayCount,
        'pending' => $pendingCount,
        'week' => $weekCount,
        'month' => $monthCount,
        'recent' => $recent
    ]);

} catch (Exception $e) {
    error_log("Error in get_dashboard_stats.php: " . $e->getMessage());
    json_response(false, 'Error retrieving statistics: ' . $e->getMessage());
}
?>