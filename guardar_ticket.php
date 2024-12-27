<?php
// Incluir conexión a la base de datos
include 'bd.php';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $nombre = $_POST['nombre'];
    $tecnico = $_POST['tecnico'];
    $departamento = $_POST['departamento'];
    $asunto = $_POST['asunto'];
    $problema = $_POST['problema'];
    $solucion = $_POST['solucion'];
    $tiempo_solucion = $_POST['tiempo_solucion'];

    // Generar serie aleatoria (formato: TK##L#)
    $serie = 'TK' . rand(10, 99) . chr(rand(65, 90)) . rand(1, 9);

    // Configurar estado como "Pendiente"
    $estado = 'Pendiente';

    // Insertar datos en la base de datos
    $sql = "INSERT INTO tickets (fecha, nombre, tecnico, departamento, asunto, problema, solucion, tiempo_solucion, serie, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $fecha, $nombre, $tecnico, $departamento, $asunto, $problema, $solucion, $tiempo_solucion, $serie, $estado);

    if ($stmt->execute()) {
        // Redirigir al formulario con un mensaje de éxito
        header("Location: nuevo_ticket.php?success=1");
        exit();
    } else {
        echo "<p style='color: red;'>Error al guardar el ticket: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
