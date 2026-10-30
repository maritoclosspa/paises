<?php
require_once "conexion.php";

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("País no válido. <a href='index.php'>Volver al listado</a>");
}

$pais_id = (int) $_GET['id'];

// Obtener el país seleccionado
$sql_pais = "SELECT pais_id, pais_descripcion FROM paises WHERE pais_id = ?";
$stmt_pais = $conexion->prepare($sql_pais);
$stmt_pais->bind_param("i", $pais_id);
$stmt_pais->execute();
$resultado_pais = $stmt_pais->get_result();

if ($resultado_pais->num_rows === 0) {
    die("No se encontró el país. <a href='index.php'>Volver al listado</a>");
}

$pais = $resultado_pais->fetch_assoc();

// Agregar una provincia al país seleccionado
$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre_provincia = trim($_POST["pro_descripcion"] ?? "");

    if ($nombre_provincia === "") {
        $mensaje_error = "Escribí el nombre de la provincia.";
    } else {
        // Evitar cargar dos veces la misma provincia para un país
        $sql_verificar = "SELECT pro_id FROM provincias
                          WHERE pais_id = ? AND LOWER(pro_descripcion) = LOWER(?)";
        $stmt_verificar = $conexion->prepare($sql_verificar);
        $stmt_verificar->bind_param("is", $pais_id, $nombre_provincia);
        $stmt_verificar->execute();
        $existe = $stmt_verificar->get_result();

        if ($existe->num_rows > 0) {
            $mensaje_error = "Esa provincia ya está registrada para este país.";
        } else {
            $sql_agregar = "INSERT INTO provincias (pais_id, pro_descripcion)
                            VALUES (?, ?)";
            $stmt_agregar = $conexion->prepare($sql_agregar);
            $stmt_agregar->bind_param("is", $pais_id, $nombre_provincia);

            if ($stmt_agregar->execute()) {
                header("Location: provincias.php?id=" . $pais_id);
                exit;
            } else {
                $mensaje_error = "No se pudo agregar la provincia. Intentá nuevamente.";
            }
        }
    }
}

// Obtener solamente las provincias de ese país
$sql_provincias = "SELECT pro_id, pro_descripcion
                   FROM provincias
                   WHERE pais_id = ?
                   ORDER BY pro_descripcion ASC";
$stmt_provincias = $conexion->prepare($sql_provincias);
$stmt_provincias->bind_param("i", $pais_id);
$stmt_provincias->execute();
$resultado_provincias = $stmt_provincias->get_result();
$total_provincias = $resultado_provincias->num_rows;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provincias de <?php echo htmlspecialchars($pais['pais_descripcion']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a8a, #4f46e5, #9333ea);
            color: #1e293b;
        }

        header {
            background: rgba(15, 23, 42, 0.85);
            color: white;
            padding: 28px 8%;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
        }

        .encabezado, .contenedor {
            max-width: 1150px;
            margin-left: auto;
            margin-right: auto;
        }

        header h1 {
            font-size: 29px;
            letter-spacing: 0.5px;
        }

        header p {
            color: #c7d2fe;
            margin-top: 7px;
            font-size: 14px;
        }

        .contenedor {
            margin-top: 45px;
            margin-bottom: 45px;
            padding: 0 20px;
        }

        .bienvenida {
            color: white;
            margin-bottom: 25px;
        }

        .bienvenida h2 {
            font-size: 27px;
            margin-bottom: 7px;
        }

        .bienvenida p {
            color: #e0e7ff;
        }

        .resumen, .tarjeta {
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(15,23,42,0.15);
            margin-bottom: 25px;
        }

        .resumen {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 25px 30px;
            background: linear-gradient(120deg, #ffffff, #e0e7ff);
        }

        .resumen span {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
        }

        .resumen h2 {
            color: #4338ca;
            font-size: 38px;
            margin-top: 5px;
        }

        .icono-resumen {
            background: #c7d2fe;
            color: #4338ca;
            width: 65px;
            height: 65px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }

        .tarjeta {
            padding: 30px;
        }

        .titulo-tarjeta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
        }

        .titulo-tarjeta h2 {
            font-size: 20px;
            color: #1e293b;
        }

        .icono {
            background: #ede9fe;
            color: #7c3aed;
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        form {
            display: flex;
            gap: 12px;
        }

        input {
            flex: 1;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 9px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px #6366f122;
        }

        button {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 14px 23px;
            border-radius: 9px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
        }

        .mensaje-error {
            color: #b91c1c;
            background: #fee2e2;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .tabla-contenedor {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #eef2ff;
            color: #4338ca;
            text-align: left;
            padding: 16px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        th:first-child { border-radius: 8px 0 0 8px; }
        th:last-child { border-radius: 0 8px 8px 0; }

        td {
            padding: 17px 16px;
            border-bottom: 1px solid #edf0f5;
            font-size: 14px;
        }

        tbody tr:hover { background: #f5f3ff; }

        .numero {
            color: #6366f1;
            font-weight: bold;
        }

        .nombre {
            font-weight: 600;
            color: #334155;
        }

        .vacio {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
        }

        .volver {
            display: inline-block;
            text-decoration: none;
            background: #eef2ff;
            color: #4338ca;
            padding: 11px 16px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            transition: 0.2s;
        }

        .volver:hover { background: #c7d2fe; }

        footer {
            text-align: center;
            color: #e0e7ff;
            font-size: 13px;
            padding: 20px;
        }

        @media (max-width: 600px) {
            header { padding: 24px 20px; }
            header h1 { font-size: 23px; }
            .contenedor { margin-top: 28px; }
            .resumen, .tarjeta { padding: 22px; }
            form { flex-direction: column; }
            button { width: 100%; }
        }
    </style>
</head>
<body>

<header>
    <div class="encabezado">
        <h1>🌎 Sistema de Gestión</h1>
        <p>Administración de países y provincias</p>
    </div>
</header>

<div class="contenedor">
    <div class="bienvenida">
        <h2>Provincias de <?php echo htmlspecialchars($pais['pais_descripcion']); ?></h2>
        <p>Consultá las provincias o jurisdicciones asociadas a este país.</p>
    </div>

    <div class="resumen">
        <div>
            <span>TOTAL DE PROVINCIAS REGISTRADAS</span>
            <h2><?php echo $total_provincias; ?></h2>
        </div>
        <div class="icono-resumen">📍</div>
    </div>

    <div class="tarjeta">
        <div class="titulo-tarjeta">
            <div class="icono">＋</div>
            <h2>Agregar provincia</h2>
        </div>

        <?php if ($mensaje_error !== "") { ?>
            <div class="mensaje-error">
                <?php echo htmlspecialchars($mensaje_error); ?>
            </div>
        <?php } ?>

        <form action="provincias.php?id=<?php echo $pais_id; ?>" method="POST">
            <input
                type="text"
                name="pro_descripcion"
                placeholder="Escribí el nombre de la provincia..."
                maxlength="50"
                required
            >
            <button type="submit">＋ Agregar provincia</button>
        </form>
    </div>

    <div class="tarjeta">
        <div class="titulo-tarjeta">
            <div class="icono">📋</div>
            <h2>Listado de provincias</h2>
        </div>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de la provincia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_provincias > 0) { ?>
                        <?php while ($provincia = $resultado_provincias->fetch_assoc()) { ?>
                            <tr>
                                <td class="numero">#<?php echo (int) $provincia['pro_id']; ?></td>
                                <td class="nombre"><?php echo htmlspecialchars($provincia['pro_descripcion']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="2" class="vacio">
                                Este país todavía no tiene provincias registradas.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <a class="volver" href="index.php">← Volver a países</a>
</div>

<footer>
    Sistema de Gestión de Países y Provincias · Proyecto académico
</footer>

</body>
</html>
