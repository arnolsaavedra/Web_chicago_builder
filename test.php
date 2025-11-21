<?php
// test_connection.php - Archivo para probar la conexión y configuración
// ELIMINAR DESPUÉS DE LA INSTALACIÓN POR SEGURIDAD

require_once 'config.php';

echo "<h2>🔧 Prueba de Conexión y Configuración</h2>";

// 1. Probar conexión a la base de datos
echo "<h3>1. Conexión a Base de Datos</h3>";
try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✅ <strong>Conexión exitosa</strong><br>";
        
        // Probar consulta simple
        $stmt = $db->query("SELECT 1 as test");
        if ($stmt) {
            echo "✅ <strong>Consulta básica funcionando</strong><br>";
        }
    } else {
        echo "❌ <strong>Error de conexión</strong><br>";
    }
} catch (Exception $e) {
    echo "❌ <strong>Error de conexión:</strong> " . $e->getMessage() . "<br>";
}

// 2. Verificar tablas
echo "<h3>2. Verificación de Tablas</h3>";
try {
    if ($db) {
        $tables = ['appointments', 'business_hours', 'admin_users'];
        foreach ($tables as $table) {
            $stmt = $db->query("SELECT COUNT(*) as count FROM $table");
            if ($stmt) {
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                echo "✅ <strong>Tabla '$table':</strong> $count registros<br>";
            }
        }
    }
} catch (Exception $e) {
    echo "❌ <strong>Error verificando tablas:</strong> " . $e->getMessage() . "<br>";
}

// 3. Verificar datos de ejemplo
echo "<h3>3. Datos de Ejemplo</h3>";
try {
    if ($db) {
        // Verificar usuario admin
        $stmt = $db->prepare("SELECT username, email FROM admin_users WHERE username = ?");
        $stmt->execute(['admin']);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            echo "✅ <strong>Usuario admin existe:</strong> " . $admin['email'] . "<br>";
        } else {
            echo "❌ <strong>Usuario admin NO existe</strong><br>";
        }
        
        // Verificar horarios de trabajo
        $stmt = $db->query("SELECT COUNT(*) as count FROM business_hours");
        $hours_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($hours_count > 0) {
            echo "✅ <strong>Horarios de trabajo configurados:</strong> $hours_count días<br>";
        } else {
            echo "❌ <strong>Horarios de trabajo NO configurados</strong><br>";
        }
    }
} catch (Exception $e) {
    echo "❌ <strong>Error verificando datos:</strong> " . $e->getMessage() . "<br>";
}

// 4. Probar función JSON
echo "<h3>4. Función JSON Response</h3>";
try {
    // Capturar la salida
    ob_start();
    json_response(true, 'Prueba exitosa', ['test' => 'data']);
    $json_output = ob_get_clean();
    
    $decoded = json_decode($json_output, true);
    if ($decoded && $decoded['success']) {
        echo "✅ <strong>Función json_response funcionando</strong><br>";
    } else {
        echo "❌ <strong>Error en función json_response</strong><br>";
    }
} catch (Exception $e) {
    echo "❌ <strong>Error en función JSON:</strong> " . $e->getMessage() . "<br>";
}

// 5. Información del sistema
echo "<h3>5. Información del Sistema</h3>";
echo "<strong>PHP Version:</strong> " . phpversion() . "<br>";
echo "<strong>PDO MySQL:</strong> " . (extension_loaded('pdo_mysql') ? '✅ Disponible' : '❌ No disponible') . "<br>";
echo "<strong>JSON:</strong> " . (function_exists('json_encode') ? '✅ Disponible' : '❌ No disponible') . "<br>";
echo "<strong>Sessions:</strong> " . (function_exists('session_start') ? '✅ Disponible' : '❌ No disponible') . "<br>";

// 6. Configuración actual
echo "<h3>6. Configuración Actual</h3>";
echo "<strong>DB_HOST:</strong> " . DB_HOST . "<br>";
echo "<strong>DB_NAME:</strong> " . DB_NAME . "<br>";
echo "<strong>DB_USER:</strong> " . DB_USER . "<br>";
echo "<strong>DB_PASS:</strong> " . (strlen(DB_PASS) > 0 ? '✅ Configurado' : '❌ Vacío') . "<br>";
echo "<strong>Timezone:</strong> " . date_default_timezone_get() . "<br>";

// 7. URLs de prueba
echo "<h3>7. URLs de Prueba</h3>";
$base_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);

echo "<strong>Landing Page:</strong> <a href='{$base_url}/index.php' target='_blank'>{$base_url}/index.php</a><br>";
echo "<strong>Admin Login:</strong> <a href='{$base_url}/admin/login.php' target='_blank'>{$base_url}/admin/login.php</a><br>";
echo "<strong>Dashboard Stats API:</strong> <a href='{$base_url}/admin/api/get_dashboard_stats.php' target='_blank'>{$base_url}/admin/api/get_dashboard_stats.php</a><br>";

echo "<hr>";
echo "<h3>🚨 IMPORTANTE</h3>";
echo "<p><strong style='color: red;'>ELIMINA este archivo (test_connection.php) después de verificar que todo funcione correctamente por seguridad.</strong></p>";

echo "<h3>📋 Siguientes Pasos</h3>";
echo "<ol>";
echo "<li>Si ves errores arriba, corrígelos antes de continuar</li>";
echo "<li>Ve al <a href='{$base_url}/admin/login.php'>Panel de Admin</a></li>";
echo "<li>Haz login con: <strong>usuario:</strong> admin, <strong>contraseña:</strong> admin123</li>";
echo "<li>Si todo funciona, ¡elimina este archivo!</li>";
echo "</ol>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h2 { color: #005A9C; border-bottom: 2px solid #005A9C; padding-bottom: 10px; }
h3 { color: #333; margin-top: 20px; }
</style>