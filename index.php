<?php
// Incluir conexión a la base de datos
include 'bd.php';

// Consulta por defecto
$consulta = "SELECT * FROM tickets";

// Verificar si se enviaron fechas para el filtro
if (isset($_GET['from-date']) && isset($_GET['to-date']) && !empty($_GET['from-date']) && !empty($_GET['to-date'])) {
    $from_date = $conn->real_escape_string($_GET['from-date']);
    $to_date = $conn->real_escape_string($_GET['to-date']);
    $consulta = "SELECT * FROM tickets WHERE fecha BETWEEN '$from_date' AND '$to_date'";
}

// Ejecutar consulta
$result = $conn->query($consulta);

// Validar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - SabitecGPS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f8f9fa;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .header-container {
        background-color: #343a40;
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-container h1 {
        font-size: 24px;
        margin: 0;
    }

    .header-container nav {
        color: white;
        margin: 0 10px;
        text-decoration: none;
        font-size: 16px;
    }

    .header-container nav input[type="text"] {
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .header-container nav button {
        padding: 5px 10px;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        color: white;
    }

    .header-container nav a {
        color: white;
        margin: 0 10px;
        text-decoration: none;
        font-size: 16px;
    }

    .header-container nav a:hover {
        text-decoration: underline;
    }

    .filter-container {
        margin: 20px auto;
        text-align: center;
    }

    .filter-container form,
    .filter-container a {
        display: inline-block;
        margin: 0 10px;
    }

    .filter-container input[type="date"] {
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .btn-primary {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        text-align: left;
        background-color: white;
        border-radius: 5px;
        overflow: hidden;
    }

    table thead {
        background-color: #343a40;
        color: white;
    }

    table th,
    table td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    h2 {
        text-align: center;
        margin: 20px 0;
    }

    .content h3 {
        width: 100%;
        text-align: center;
    }

    .content p {
        width: 100%;
        text-align: center;
    }
    </style>
</head>
<body>
<header>
    <div class="header-container">
        <h1>SabitecGPS</h1>
        <nav>
        <a href="dashboard.php">Dashboard</a>
            <a href="soporte.php">Soporte técnico</a>
            <input type="text" placeholder="Buscar">
            <button>Buscar</button>
        </nav>
    </div>
</header>
<main>
    <h2>Panel Administrativo</h2>
    <div class="filter-container">
        <form method="GET" action="" style="display: inline-block;">
            <div style="display: inline-block; margin-right: 10px;">
                <label for="from-date">Desde:</label>
                <input type="date" id="from-date" name="from-date" value="<?php echo isset($_GET['from-date']) ? $_GET['from-date'] : ''; ?>">
            </div>
            <div style="display: inline-block; margin-right: 10px;">
                <label for="to-date">Hasta:</label>
                <input type="date" id="to-date" name="to-date" value="<?php echo isset($_GET['to-date']) ? $_GET['to-date'] : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-right: 10px; padding: 8px 15px;">Filtrar</button>
        </form>
        <a href="?" class="btn btn-primary" style="text-decoration: none; color: white; padding: 8px 15px; border-radius: 5px; background-color: #007bff; border: none; display: inline-block;">Borrar Filtro</a>
    </div>
    <?php if (isset($_GET['from-date']) && isset($_GET['to-date']) && (empty($_GET['from-date']) || empty($_GET['to-date']))) { ?>
        <p class="error-message">Por favor, selecciona ambas fechas.</p>
    <?php } ?>
    <section class="content">
        <h3>Bienvenido administrador</h3>
        <p>Aquí se muestran todos los Tickets de LinuxStore los cuales podrá eliminar, modificar e imprimir.</p>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Serie</th>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>Asunto</th>
                    <th>Técnico</th>
                    <th>Prioridad</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar resultados de la consulta
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['fecha']}</td>
                            <td>{$row['serie']}</td>
                            <td>{$row['estado']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['asunto']}</td>
                            <td>{$row['tecnico']}</td>
                            <td>{$row['departamento']}</td>
                            <td>
                                <a href='editar_ticket.php?id={$row['id']}' class='btn btn-primary' style='text-decoration: none; color: white; padding: 5px 10px; border-radius: 5px; background-color: #007bff; border: none;'>Editar</a>
                                <a href='eliminar_ticket.php?id={$row['id']}' class='btn btn-primary' style='text-decoration: none; color: white; padding: 5px 10px; border-radius: 5px; background-color: #dc3545; border: none;'>Eliminar</a>
                                <a href='exportar_excel.php?id={$row['id']}' class='btn btn-primary' style='text-decoration: none; color: white; padding: 5px 10px; border-radius: 5px; background-color: #dc3545; border: none;'>exportar</a>";
                                

                        // Agregar check si el estado es "Resuelto"
                        if (strtoupper($row['estado']) === 'RESUELTO') {
                            echo " <span style='color: green; font-size: 20px;'>&#10004;</span>";
                        }

                        echo "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No hay tickets</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>
</body>
</html>
