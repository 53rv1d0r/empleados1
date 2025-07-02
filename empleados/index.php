<?php
require_once '../includes/db.php';

// --- Lógica para eliminar empleado ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM empleados WHERE empleado_id = ?"); 
        $stmt->execute([$id]);
        
        // Redirige para evitar el reenvío del formulario al recargar la página
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        // Muestra un error si la eliminación falla
        echo "<p class='error'>Error al eliminar el empleado: " . $e->getMessage() . "</p>";
    }
}

// --- Obtener lista de empleados con sus datos maestros ---
$stmt = $pdo->query("
    SELECT
        e.empleado_id, e.nombres, e.apellidos, e.numero_documento, e.fecha_ingreso,
        td.nombre_tipo_documento AS tipo_documento_nombre,
        c.nombre_cargo AS cargo_nombre,
        tc.nombre_tipo_contrato AS tipo_contrato_nombre
    FROM
        empleados e
    JOIN
        tipos_documento td ON e.tipo_documento = td.tipos_documento_id
    JOIN
        cargos c ON e.cargo = c.cargos_id
    JOIN
        tipos_contrato tc ON e.tipo_contrato = tc.tipos_contrato_id
    ORDER BY
        e.apellidos, e.nombres
");
$empleados = $stmt->fetchAll(); // Obtiene todos los resultados
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Listado de Empleados</h2>
        <p><a href="crear.php">Registrar Nuevo Empleado</a></p>

        <!-- Contenedor responsivo para la tabla -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Documento</th>
                        <th>Tipo Documento</th>
                        <th>Cargo</th>
                        <th>Tipo Contrato</th>
                        <th>Fecha Ingreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($empleados) > 0): ?>
                        <?php foreach ($empleados as $empleado): ?>
                            <tr>
                                <!-- Se añade data-label a cada td para la responsividad móvil -->
                                <td data-label="Nombres"><?php echo htmlspecialchars($empleado['nombres']); ?></td>
                                <td data-label="Apellidos"><?php echo htmlspecialchars($empleado['apellidos']); ?></td>
                                <td data-label="Documento"><?php echo htmlspecialchars($empleado['numero_documento']); ?></td>
                                <td data-label="Tipo Documento"><?php echo htmlspecialchars($empleado['tipo_documento_nombre']); ?></td>
                                <td data-label="Cargo"><?php echo htmlspecialchars($empleado['cargo_nombre']); ?></td>
                                <td data-label="Tipo Contrato"><?php echo htmlspecialchars($empleado['tipo_contrato_nombre']); ?></td>
                                <td data-label="Fecha Ingreso"><?php echo htmlspecialchars($empleado['fecha_ingreso']); ?></td>
                                <td class="actions" data-label="Acciones">
                                    <a href="editar.php?id=<?php echo $empleado['empleado_id']; ?>">Editar</a>
                                    <a href="index.php?delete=<?php echo $empleado['empleado_id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este empleado?');" class="delete">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No hay empleados registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> <!-- Cierre de .table-responsive -->

        <p><a href="../index.php">Volver al Inicio</a></p>
    </div>
</body>
</html>
