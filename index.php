<?php
require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header-content">
            <!-- Logo SVG centrado -->
            <img src="assets/corporacion-fomenthum-logo.svg" alt="Logo Corporación Fomenthum" class="logo-img">
            <h1>Sistema de Gestión de Empleados</h1>
        </div>
        
        <nav>
            <ul>
                <li><a href="empleados/index.php">Ver Empleados</a></li>
                <li><a href="empleados/crear.php">Registrar Empleado</a></li>
            </ul>
        </nav>
        <p style="text-align: center;">Bienvenido al sistema de gestión de empleados de <strong>Fomenthum</strong>. Utiliza los botones para interactuar con la aplicación.</p>
    </div>
</body>
</html>
