<?php
// config.php - Configuración de conexión a la base de datos

// Configuración de la base de datos
define('DB_HOST', '123'); // Cambiar si tu host es diferente
define('DB_NAME', '123'); // Nombre de tu base de datos
define('DB_USER', '123'); // Tu usuario de MySQL
define('DB_PASS', '123'); // Tu contraseña de MySQL

// Configuración de zona horaria
date_default_timezone_set('America/Chicago');

// Configuración del negocio
define('BUSINESS_NAME', 'Chicago Window Installation');
define('BUSINESS_PHONE', '(773) 552-9347');
define('BUSINESS_EMAIL', 'windows@thesmarthouseguys.com');

// Configuración de horarios (en minutos)
define('APPOINTMENT_DURATION', 60); // Duración de cada cita en minutos
define('TIME_SLOT_INTERVAL', 30); // Intervalo entre horarios disponibles en minutos

class Database {
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Función para sanitizar datos
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para validar email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para validar teléfono (formato básico)
function validate_phone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return strlen($phone) >= 10 && strlen($phone) <= 15;
}

// Función para generar respuesta JSON
function json_response($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
?>