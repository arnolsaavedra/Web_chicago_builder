<?php
// admin/api/get_dashboard_stats.php - API para estadísticas del dashboard

require_once '../../config.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        json_response(false, 'Error de conexión a la base de datos');
    }
    
    $today = date('Y-m-d');
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $monthStart = date('Y-m-01');
    
    // Citas de hoy
    $todayQuery = "SELECT COUNT(*) as count FROM appointments 
                   WHERE appointment_date = :today AND status != 'cancelled'";
    $todayStmt = $db->prepare($todayQuery);
    $todayStmt->bindParam(':today', $today);
    $todayStmt->execute();
    $todayCount = $todayStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Citas pendientes
    $pendingQuery = "SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'";
    $pendingStmt = $db->prepare($pendingQuery);
    $pendingStmt->execute();
    $pendingCount = $pendingStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Citas de esta semana
    $weekQuery = "SELECT COUNT(*) as count FROM appointments 
                  WHERE appointment_date >= :week_start AND status != 'cancelled'";
    $weekStmt = $db->prepare($weekQuery);
    $weekStmt->bindParam(':week_start', $weekStart);
    $weekStmt->execute();
    $weekCount = $weekStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Citas de este mes
    $monthQuery = "SELECT COUNT(*) as count FROM appointments 
                   WHERE appointment_date >= :month_start AND status != 'cancelled'";
    $monthStmt = $db->prepare($monthQuery);
    $monthStmt->bindParam(':month_start', $monthStart);
    $monthStmt->execute();
    $monthCount = $monthStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Citas recientes (últimas 10)
    $recentQuery = "SELECT id, customer_name, appointment_date, appointment_time, status 
                    FROM appointments 
                    ORDER BY created_at DESC 
                    LIMIT 10";
    $recentStmt = $db->prepare($recentQuery);
    $recentStmt->execute();
    $recent = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
    
    json_response(true, 'Estadísticas obtenidas', [
        'today' => $todayCount,
        'pending' => $pendingCount,
        'week' => $weekCount,
        'month' => $monthCount,
        'recent' => $recent
    ]);
    
} catch (Exception $e) {
    error_log("Error en get_dashboard_stats.php: " . $e->getMessage());
    json_response(false, 'Error al obtener estadísticas');
}
?>

<?php
// admin/api/get_appointments.php - API para obtener todas las citas

require_once '../../config.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        json_response(false, 'Error de conexión a la base de datos');
    }
    
    $whereConditions = [];
    $params = [];
    
    // Filtros opcionales
    if (!empty($_GET['status'])) {
        $whereConditions[] = "status = :status";
        $params[':status'] = $_GET['status'];
    }
    
    if (!empty($_GET['dateFrom'])) {
        $whereConditions[] = "appointment_date >= :date_from";
        $params[':date_from'] = $_GET['dateFrom'];
    }
    
    if (!empty($_GET['dateTo'])) {
        $whereConditions[] = "appointment_date <= :date_to";
        $params[':date_to'] = $_GET['dateTo'];
    }
    
    $whereClause = empty($whereConditions) ? '' : 'WHERE ' . implode(' AND ', $whereConditions);
    
    $query = "SELECT id, customer_name, customer_email, customer_phone, customer_address,
                     appointment_date, appointment_time, status, notes, created_at, updated_at
              FROM appointments 
              {$whereClause}
              ORDER BY appointment_date DESC, appointment_time DESC";
    
    $stmt = $db->prepare($query);
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value);
    }
    $stmt->execute();
    
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    json_response(true, 'Citas obtenidas', $appointments);
    
} catch (Exception $e) {
    error_log("Error en get_appointments.php: " . $e->getMessage());
    json_response(false, 'Error al obtener citas');
}
?>

<?php
// admin/api/get_appointment_details.php - API para obtener detalles de una cita

require_once '../../config.php';

header('Content-Type: application/json');

try {
    if (empty($_GET['id'])) {
        json_response(false, 'ID de cita requerido');
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        json_response(false, 'Error de conexión a la base de datos');
    }
    
    $query = "SELECT * FROM appointments WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$appointment) {
        json_response(false, 'Cita no encontrada');
    }
    
    json_response(true, 'Detalles obtenidos', $appointment);
    
} catch (Exception $e) {
    error_log("Error en get_appointment_details.php: " . $e->getMessage());
    json_response(false, 'Error al obtener detalles');
}
?>

<?php
// admin/api/update_appointment.php - API para actualizar una cita

require_once '../../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Método no permitido');
}

try {
    $required_fields = ['id', 'appointment_date', 'appointment_time', 'status'];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            json_response(false, 'Campos requeridos: ' . implode(', ', $required_fields));
        }
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        json_response(false, 'Error de conexión a la base de datos');
    }
    
    // Validar que la cita existe
    $checkQuery = "SELECT id FROM appointments WHERE id = :id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':id', $_POST['id']);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() === 0) {
        json_response(false, 'Cita no encontrada');
    }
    
    // Verificar conflictos de horario (solo si cambió fecha/hora)
    $conflictQuery = "SELECT id FROM appointments 
                      WHERE appointment_date = :date 
                      AND appointment_time = :time 
                      AND id != :id 
                      AND status != 'cancelled'";
    
    $conflictStmt = $db->prepare($conflictQuery);
    $conflictStmt->bindParam(':date', $_POST['appointment_date']);
    $conflictStmt->bindParam(':time', $_POST['appointment_time']);
    $conflictStmt->bindParam(':id', $_POST['id']);
    $conflictStmt->execute();
    
    if ($conflictStmt->rowCount() > 0) {
        json_response(false, 'Ya existe una cita en ese horario');
    }
    
    // Actualizar la cita
    $updateQuery = "UPDATE appointments 
                    SET appointment_date = :date, 
                        appointment_time = :time, 
                        status = :status, 
                        notes = :notes,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";
    
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(':date', $_POST['appointment_date']);
    $updateStmt->bindParam(':time', $_POST['appointment_time']);
    $updateStmt->bindParam(':status', $_POST['status']);
    $updateStmt->bindParam(':notes', $_POST['notes']);
    $updateStmt->bindParam(':id', $_POST['id']);
    
    if ($updateStmt->execute()) {
        json_response(true, 'Cita actualizada exitosamente');
    } else {
        json_response(false, 'Error al actualizar la cita');
    }
    
} catch (Exception $e) {
    error_log("Error en update_appointment.php: " . $e->getMessage());
    json_response(false, 'Error al actualizar la cita');
}
?>

<?php
// admin/api/delete_appointment.php - API para eliminar una cita

require_once '../../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Método no permitido');
}

try {
    if (empty($_POST['id'])) {
        json_response(false, 'ID de cita requerido');
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        json_response(false, 'Error de conexión a la base de datos');
    }
    
    // Verificar que la cita existe
    $checkQuery = "SELECT id FROM appointments WHERE id = :id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':id', $_POST['id']);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() === 0) {
        json_response(false, 'Cita no encontrada');
    }
    
    // Eliminar la cita
    $deleteQuery = "DELETE FROM appointments WHERE id = :id";
    $deleteStmt = $db->prepare($deleteQuery);
    $deleteStmt->bindParam(':id', $_POST['id']);
    
    if ($deleteStmt->execute()) {
        json_response(true, 'Cita eliminada exitosamente');
    } else {
        json_response(false, 'Error al eliminar la cita');
    }
    
} catch (Exception $e) {
    error_log("Error en delete_appointment.php: " . $e->getMessage());
    json_response(false, 'Error al eliminar la cita');
}
?>

<?php
// admin/api/get_business_hours.php - API para obtener horarios de trabajo

require_once '../../config.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        json_response(false, 'Error de conexión a la base de datos');
    }
    
    $query = "SELECT day_of_week, start_time, end_time, is_active 
              FROM business_hours 
              ORDER BY day_of_week";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $hours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    json_response(true, 'Horarios obtenidos', $hours);
    
} catch (Exception $e) {
    error_log("Error en get_business_hours.php: " . $e->getMessage());
    json_response(false, 'Error al obtener horarios');
}
?>

<?php
// admin/api/update_business_hours.php - API para actualizar horarios de trabajo

require_once '../../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(false, 'Método no permitido');
}

try {
    if (empty($_POST['hours'])) {
        json_response(false, 'Datos de horarios requeridos');
    }
    
    $hours = json_decode($_POST['hours'], true);
    
    if (!$hours) {
        json_response(false, 'Formato de datos inválido');
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        json_response(false, 'Error de conexión a la base de datos');
    }
    
    // Iniciar transacción
    $db->beginTransaction();
    
    try {
        foreach ($hours as $hour) {
            $updateQuery = "UPDATE business_hours 
                            SET start_time = :start_time, 
                                end_time = :end_time, 
                                is_active = :is_active 
                            WHERE day_of_week = :day_of_week";
            
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(':start_time', $hour['start_time']);
            $updateStmt->bindParam(':end_time', $hour['end_time']);
            $updateStmt->bindParam(':is_active', $hour['is_active'], PDO::PARAM_BOOL);
            $updateStmt->bindParam(':day_of_week', $hour['day_of_week']);
            
            $updateStmt->execute();
        }
        
        // Confirmar transacción
        $db->commit();
        json_response(true, 'Horarios actualizados exitosamente');
        
    } catch (Exception $e) {
        // Revertir transacción
        $db->rollback();
        throw $e;
    }
    
} catch (Exception $e) {
    error_log("Error en update_business_hours.php: " . $e->getMessage());
    json_response(false, 'Error al actualizar horarios');
}
?>