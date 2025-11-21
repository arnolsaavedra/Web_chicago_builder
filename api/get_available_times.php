<?php
// api/get_available_times.php - API para obtener horarios disponibles

require_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    json_response(false, 'Método no permitido');
}

try {
    // Validar parámetro de fecha
    if (empty($_GET['date'])) {
        json_response(false, 'Fecha requerida');
    }

    $requestedDate = sanitize_input($_GET['date']);

    // Validar formato de fecha
    $dateObj = DateTime::createFromFormat('Y-m-d', $requestedDate);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $requestedDate) {
        json_response(false, 'Formato de fecha inválido');
    }

    // No permitir fechas en el pasado
    $today = new DateTime('today');
    if ($dateObj < $today) {
        json_response(false, 'No se pueden agendar citas en el pasado');
    }

    // Obtener día de la semana (1 = Lunes, 7 = Domingo)
    $dayOfWeek = $dateObj->format('N');

    // Conectar a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        json_response(false, 'Database connection error');
    }

    // Consultar horarios de negocio desde la tabla business_hours
    $hoursQuery = "SELECT day_of_week, start_time, end_time, is_active
                   FROM business_hours
                   WHERE day_of_week = :day_of_week";

    $hoursStmt = $db->prepare($hoursQuery);
    $hoursStmt->bindParam(':day_of_week', $dayOfWeek);
    $hoursStmt->execute();

    $businessHour = $hoursStmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si hay configuración para este día
    if (!$businessHour) {
        json_response(false, 'No business hours configured for this day');
    }

    // Verificar si trabajamos en este día
    if (!$businessHour['is_active']) {
        json_response(false, 'We are closed on this day');
    }

    // Obtener citas ya agendadas para esta fecha
    $bookedTimes = [];
    $appointmentsQuery = "SELECT appointment_time
                          FROM appointments
                          WHERE appointment_date = :date
                          AND status != 'cancelled'";

    $appointmentsStmt = $db->prepare($appointmentsQuery);
    $appointmentsStmt->bindParam(':date', $requestedDate);
    $appointmentsStmt->execute();

    while ($row = $appointmentsStmt->fetch(PDO::FETCH_ASSOC)) {
        $bookedTimes[] = $row['appointment_time'];
    }

    // Generar slots de tiempo disponibles
    $availableTimes = generateTimeSlots(
        $businessHour['start_time'],
        $businessHour['end_time'],
        $bookedTimes,
        $dateObj
    );

    if (empty($availableTimes)) {
        json_response(false, 'No hay horarios disponibles para esta fecha');
    }

    json_response(true, 'Horarios obtenidos exitosamente', $availableTimes);

} catch (Exception $e) {
    error_log("Error en get_available_times.php: " . $e->getMessage());
    json_response(false, 'Error al obtener horarios disponibles: ' . $e->getMessage());
}

function generateTimeSlots($startTime, $endTime, $bookedTimes, $dateObj) {
    $slots = [];

    // Convertir a objetos DateTime
    $start = new DateTime($startTime);
    $end = new DateTime($endTime);
    $interval = TIME_SLOT_INTERVAL; // Definido en config.php (30 minutos)

    // Si es hoy, no permitir horarios en el pasado
    $now = new DateTime();
    $isToday = $dateObj->format('Y-m-d') === $now->format('Y-m-d');

    $current = clone $start;

    while ($current < $end) {
        $timeStr = $current->format('H:i:s');
        $displayTime = $current->format('g:i A');

        // Si es hoy, verificar que no sea una hora pasada
        $isAvailable = true;
        if ($isToday) {
            $currentTimeToday = clone $now;
            $slotTime = clone $current;
            $slotTime->setDate($now->format('Y'), $now->format('m'), $now->format('d'));

            // Agregar buffer de 2 horas para preparación
            $currentTimeToday->add(new DateInterval('PT2H'));

            if ($slotTime <= $currentTimeToday) {
                $isAvailable = false;
            }
        }

        // Verificar si el slot está ocupado
        if (in_array($timeStr, $bookedTimes)) {
            $isAvailable = false;
        }

        $slots[] = [
            'time' => $timeStr,
            'display' => $displayTime,
            'available' => $isAvailable
        ];

        // Avanzar al siguiente slot
        $current->add(new DateInterval('PT' . $interval . 'M'));
    }

    return $slots;
}
?>
