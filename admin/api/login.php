<?php
// admin/api/login.php
session_start();
require_once '../../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Method not allowed');
}

try {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        json_response(false, 'Username and password are required');
    }

    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        json_response(false, 'Database connection error');
    }

    $query = "SELECT id, username, password_hash, email FROM admin_users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        json_response(false, 'Invalid credentials');
    }

    // Create session
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_email'] = $user['email'];
    $_SESSION['logged_in'] = true;

    json_response(true, 'Login successful');

} catch (Exception $e) {
    error_log("Error in login.php: " . $e->getMessage());
    json_response(false, 'Internal server error: ' . $e->getMessage());
}
?>