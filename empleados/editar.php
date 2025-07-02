<?php
require_once '../includes/db.php';

$errors = []; // Array para almacenar los mensajes de error de validación
$empleado = null; // Variable para almacenar los datos del empleado a editar

// --- Lógica para cargar los datos del empleado existente al cargar la página ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id']; // Obtiene el ID del empleado de la URL
    $stmt = $pdo->prepare("SELECT * FROM empleados WHERE empleado_id = ?"); 
    $stmt->execute([$id]);
    $empleado = $stmt->fetch(); // Obtiene el registro del empleado

    if (!$empleado) {
        die("Empleado no encontrado."); // Si no se encuentra el empleado, muestra un mensaje de error
    }
} else {
    die("ID de empleado no especificado."); // Si el ID no está en la URL, muestra un mensaje de error
}

// --- Obtener datos de tablas maestras para los menús desplegables ---
$tipos_documento = $pdo->query("SELECT * FROM tipos_documento ORDER BY nombre_tipo_documento")->fetchAll();
$cargos = $pdo->query("SELECT * FROM cargos ORDER BY nombre_cargo")->fetchAll();
$tipos_contrato = $pdo->query("SELECT * FROM tipos_contrato ORDER BY nombre_tipo_contrato")->fetchAll();

// --- Lógica para procesar el formulario cuando se envía (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge y sanitiza los datos del formulario
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $numero_documento = trim($_POST['numero_documento']);
    $tipo_documento = $_POST['tipo_documento'];
    $cargo = $_POST['cargo'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $empleado_id = $_POST['empleado_id']; // Obtiene el ID del campo oculto del formulario

    // --- Validación de campos obligatorios ---
    if (empty($nombres)) {
        $errors[] = "El campo Nombres es obligatorio.";
    }
    if (empty($apellidos)) {
        $errors[] = "El campo Apellidos es obligatorio.";
    }
    if (empty($numero_documento)) {
        $errors[] = "El campo Número de Documento es obligatorio.";
    }
    if (empty($tipo_documento)) { 
        $errors[] = "Debe seleccionar un Tipo de Documento.";
    }
    if (empty($cargo)) { 
        $errors[] = "Debe seleccionar un Cargo.";
    }
    if (empty($tipo_contrato)) { 
        $errors[] = "Debe seleccionar un Tipo de Contrato.";
    }
    if (empty($fecha_ingreso)) {
        $errors[] = "El campo Fecha de Ingreso es obligatorio.";
    }

    // --- Validación de número de documento único (excluyendo el empleado actual) ---
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE numero_documento = ? AND empleado_id != ?"); 
        $stmt->execute([$numero_documento, $empleado_id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "El número de documento ya existe para otro empleado.";
        }
    }

    // --- Si no hay errores, se procede a actualizar el empleado en la base de datos ---
    if (empty($errors)) {
        try {
            // ¡Nuevos nombres de columnas en la sentencia UPDATE!
            $stmt = $pdo->prepare("UPDATE empleados SET nombres = ?, apellidos = ?, numero_documento = ?, tipo_documento = ?, cargo = ?, tipo_contrato = ?, fecha_ingreso = ? WHERE empleado_id = ?"); 
            // Se pasan las nuevas variables a execute
            $stmt->execute([$nombres, $apellidos, $numero_documento, $tipo_documento, $cargo, $tipo_contrato, $fecha_ingreso, $empleado_id]);
            
            // Redirige al usuario al listado de empleados después de una actualización exitosa
            header('Location: index.php');
            exit(); // Asegura que el script se detenga
        } catch (PDOException $e) {
            // Captura y muestra errores de la base de datos
            $errors[] = "Error al actualizar el empleado: " . $e->getMessage();
        }
    } else {
        // Si hay errores, volvemos a cargar los datos del POST para que el usuario no pierda lo que escribió
        $empleado['nombres'] = $nombres;
        $empleado['apellidos'] = $apellidos;
        $empleado['numero_documento'] = $numero_documento;
        $empleado['tipo_documento'] = $tipo_documento; // Nuevo nombre
        $empleado['cargo'] = $cargo; // Nuevo nombre
        $empleado['tipo_contrato'] = $tipo_contrato; // Nuevo nombre
        $empleado['fecha_ingreso'] = $fecha_ingreso;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Editar Empleado</h2>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="editar.php?id=<?php echo htmlspecialchars($empleado['empleado_id']); ?>" method="POST">
            <input type="hidden" name="empleado_id" value="<?php echo htmlspecialchars($empleado['empleado_id']); ?>"> 

            <label for="nombres">Nombres:</label>
            <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($empleado['nombres']); ?>" required>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($empleado['apellidos']); ?>" required>

            <label for="numero_documento">Número de Documento:</label>
            <input type="text" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars($empleado['numero_documento']); ?>" required>

            <label for="tipo_documento">Tipo de Documento:</label> 
            <select id="tipo_documento" name="tipo_documento" required> 
                <option value="">Seleccione...</option>
                <?php foreach ($tipos_documento as $tipo): ?>
                    <!-- Se usan los nuevos nombres de las columnas para value y display -->
                    <option value="<?php echo $tipo['tipos_documento_id']; ?>" <?php echo ($empleado['tipo_documento'] == $tipo['tipos_documento_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tipo['nombre_tipo_documento']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="cargo">Cargo:</label> 
            <select id="cargo" name="cargo" required> 
                <option value="">Seleccione...</option>
                <?php foreach ($cargos as $cargo_item): ?>
                    <option value="<?php echo $cargo_item['cargos_id']; ?>" <?php echo ($empleado['cargo'] == $cargo_item['cargos_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cargo_item['nombre_cargo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="tipo_contrato">Tipo de Contrato:</label> 
            <select id="tipo_contrato" name="tipo_contrato" required> 
                <option value="">Seleccione...</option>
                <?php foreach ($tipos_contrato as $contrato_item): ?>
                    <option value="<?php echo $contrato_item['tipos_contrato_id']; ?>" <?php echo ($empleado['tipo_contrato'] == $contrato_item['tipos_contrato_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($contrato_item['nombre_tipo_contrato']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="fecha_ingreso">Fecha de Ingreso:</label>
            <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo htmlspecialchars($empleado['fecha_ingreso']); ?>" required>

            <button type="submit">Actualizar Empleado</button>
        </form>
        <p><a href="index.php">Volver al Listado</a></p>
    </div>
</body>
</html>