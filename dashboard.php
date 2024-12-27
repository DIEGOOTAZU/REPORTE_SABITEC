<?php
// Incluir conexión a la base de datos
include 'bd.php';

// Obtener conteos dinámicos de tickets
$total_tickets_query = "SELECT COUNT(*) as total FROM tickets";
$pending_tickets_query = "SELECT COUNT(*) as pendientes FROM tickets WHERE estado = 'Pendiente'";
$resolved_tickets_query = "SELECT COUNT(*) as resueltos FROM tickets WHERE estado = 'Resuelto'";

$total_tickets_result = $conn->query($total_tickets_query);
$pending_tickets_result = $conn->query($pending_tickets_query);
$resolved_tickets_result = $conn->query($resolved_tickets_query);

$total_tickets = $total_tickets_result->fetch_assoc()['total'] ?? 0;
$pending_tickets = $pending_tickets_result->fetch_assoc()['pendientes'] ?? 0;
$resolved_tickets = $resolved_tickets_result->fetch_assoc()['resueltos'] ?? 0;

// Calcular porcentajes
$pending_percentage = $total_tickets > 0 ? round(($pending_tickets / $total_tickets) * 100, 2) : 0;
$resolved_percentage = $total_tickets > 0 ? round(($resolved_tickets / $total_tickets) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SabitecGPS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-container {
            background-color: #343a40;
            padding: 10px 20px;
            color: white;
        }
        .header-container h1 {
            font-size: 24px;
            display: inline-block;
        }
        .header-container nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
        .dashboard-cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px;
        }
        .card {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card h5 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 24px;
            font-weight: bold;
        }
        .card small {
            font-size: 16px;
            color: #6c757d;
        }
        .chart-container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        canvas {
            max-width: 100%;
            max-height: 300px;
        }
    </style>
</head>
<body>
<header>
    <div class="header-container d-flex justify-content-between align-items-center">
        <h1>SabitecGPS</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="soporte.php">Soporte Técnico</a>
        </nav>
    </div>
</header>
<main>
    <h2 class="text-center">Dashboard</h2>

    <div class="dashboard-cards">
        <div class="card">
            <h5>Total de Tickets</h5>
            <p><?php echo $total_tickets; ?></p>
        </div>
        <div class="card">
            <h5>Tickets Pendientes</h5>
            <p><?php echo $pending_tickets; ?></p>
            <small><?php echo $pending_percentage; ?>% del total</small>
        </div>
        <div class="card">
            <h5>Tickets Resueltos</h5>
            <p><?php echo $resolved_tickets; ?></p>
            <small><?php echo $resolved_percentage; ?>% del total</small>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="ticketsChart"></canvas>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ticketsChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'Resueltos'],
            datasets: [{
                data: [<?php echo $pending_tickets; ?>, <?php echo $resolved_tickets; ?>],
                backgroundColor: ['#ffc107', '#28a745']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
</script>
</body>
</html>
