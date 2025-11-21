<?php
// test_api_times.php - Prueba directa del API de horarios disponibles
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test API Horarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #005A9C;
            border-bottom: 3px solid #005A9C;
            padding-bottom: 10px;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #005A9C;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        .info {
            color: #007bff;
        }
        pre {
            background: #282c34;
            color: #abb2bf;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        button {
            background: #005A9C;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #004080;
        }
        #result {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test API de Horarios Disponibles</h1>

        <div class="test-section">
            <h2>1️⃣ Test de Configuración</h2>
            <?php
            require_once 'config.php';

            echo "<p><strong>Zona Horaria:</strong> " . date_default_timezone_get() . "</p>";
            echo "<p><strong>Fecha/Hora Actual:</strong> " . date('Y-m-d H:i:s') . "</p>";
            echo "<p><strong>TIME_SLOT_INTERVAL:</strong> " . TIME_SLOT_INTERVAL . " minutos</p>";
            ?>
        </div>

        <div class="test-section">
            <h2>2️⃣ Test de Conexión a Base de Datos</h2>
            <?php
            try {
                $database = new Database();
                $db = $database->getConnection();

                if ($db) {
                    echo "<p class='success'>✅ Conexión exitosa a la base de datos</p>";

                    // Verificar tabla appointments
                    try {
                        $stmt = $db->query("SELECT COUNT(*) as count FROM appointments");
                        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                        echo "<p class='success'>✅ Tabla 'appointments' existe: $count registros</p>";
                    } catch (Exception $e) {
                        echo "<p class='error'>❌ Tabla 'appointments' no existe: " . $e->getMessage() . "</p>";
                    }
                } else {
                    echo "<p class='error'>❌ No se pudo conectar a la base de datos</p>";
                }
            } catch (Exception $e) {
                echo "<p class='error'>❌ Error de conexión: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>

        <div class="test-section">
            <h2>3️⃣ Test Directo del API</h2>
            <p>Selecciona una fecha para probar:</p>
            <input type="date" id="testDate" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            <button onclick="testAPI()">Probar API</button>

            <div id="result"></div>
        </div>

        <div class="test-section">
            <h2>4️⃣ Horarios Predefinidos</h2>
            <p>El API usa estos horarios (definidos en get_available_times.php):</p>
            <pre>Lunes - Viernes:  8:00 AM - 6:00 PM
Sábado:           9:00 AM - 4:00 PM
Domingo:          Cerrado</pre>
        </div>

        <div class="test-section">
            <h2>5️⃣ Verificación de Archivos</h2>
            <?php
            $files = [
                'api/get_available_times.php',
                'config.php',
                'js/appointment.js'
            ];

            foreach ($files as $file) {
                if (file_exists($file)) {
                    $size = filesize($file);
                    echo "<p class='success'>✅ $file existe (" . number_format($size) . " bytes)</p>";
                } else {
                    echo "<p class='error'>❌ $file NO EXISTE</p>";
                }
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        function testAPI() {
            const date = document.getElementById('testDate').value;
            const resultDiv = document.getElementById('result');

            resultDiv.innerHTML = '<p class="info">🔄 Cargando...</p>';

            $.ajax({
                url: 'api/get_available_times.php',
                type: 'GET',
                data: { date: date },
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);

                    let html = '<h3>Respuesta del API:</h3>';
                    html += '<pre>' + JSON.stringify(response, null, 2) + '</pre>';

                    if (response.success) {
                        html += '<p class="success">✅ API funcionó correctamente</p>';
                        html += '<p><strong>Horarios disponibles:</strong> ' + response.data.length + '</p>';

                        if (response.data.length > 0) {
                            html += '<h4>Primeros 5 horarios:</h4><ul>';
                            response.data.slice(0, 5).forEach(function(slot) {
                                html += '<li>' + slot.display + ' - ' + (slot.available ? '✅ Disponible' : '❌ No disponible') + '</li>';
                            });
                            html += '</ul>';
                        }
                    } else {
                        html += '<p class="error">❌ Error: ' + response.message + '</p>';
                    }

                    resultDiv.innerHTML = html;
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr, status, error);

                    let html = '<h3 class="error">❌ Error de AJAX</h3>';
                    html += '<p><strong>Status:</strong> ' + status + '</p>';
                    html += '<p><strong>Error:</strong> ' + error + '</p>';
                    html += '<p><strong>Status Code:</strong> ' + xhr.status + '</p>';

                    if (xhr.responseText) {
                        html += '<h4>Respuesta del servidor:</h4>';
                        html += '<pre>' + xhr.responseText.substring(0, 500) + '</pre>';
                    }

                    resultDiv.innerHTML = html;
                }
            });
        }

        // Test automático al cargar
        window.onload = function() {
            setTimeout(testAPI, 1000);
        };
    </script>
</body>
</html>
