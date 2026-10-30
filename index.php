
<?php
require_once "conexion.php";

$sql = "SELECT * FROM paises ORDER BY pais_id ASC";
$resultado = $conexion->query($sql);
$total = $resultado->num_rows;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Países</title>

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

        .encabezado {
            max-width: 1200px;
            margin: auto;
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
            max-width: 1150px;
            margin: 45px auto;
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

        .resumen {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(120deg, #ffffff, #e0e7ff);
            padding: 25px 30px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 12px 30px rgba(15,23,42,0.15);
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
            background: white;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 12px 30px rgba(15,23,42,0.15);
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
            transition: 0.2s;
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
            transition: 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 16px #4f46e544;
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

        th:first-child {
            border-radius: 8px 0 0 8px;
        }

        th:last-child {
            border-radius: 0 8px 8px 0;
        }

        td {
            padding: 17px 16px;
            border-bottom: 1px solid #edf0f5;
            font-size: 14px;
        }

        tbody tr {
            transition: 0.2s;
        }

        tbody tr:hover {
            background: #f5f3ff;
        }

        .numero {
            color: #6366f1;
            font-weight: bold;
        }

        .nombre-pais {
            font-weight: 600;
            color: #334155;
        }

        .acciones {
            display: flex;
            gap: 9px;
        }

        .editar, .eliminar {
            text-decoration: none;
            padding: 9px 13px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            transition: 0.2s;
        }

        .editar {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .editar:hover {
            background: #bfdbfe;
        }

        .eliminar {
            background: #fee2e2;
            color: #dc2626;
        }

        .eliminar:hover {
            background: #fecaca;
        }

        .vacio {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
        }

        footer {
            text-align: center;
            color: #e0e7ff;
            font-size: 13px;
            padding: 20px;
        }

        @media (max-width: 600px) {
            header {
                padding: 24px 20px;
            }

            header h1 {
                font-size: 23px;
            }

            .contenedor {
                margin-top: 28px;
            }

            .resumen, .tarjeta {
                padding: 22px;
            }

            form {
                flex-direction: column;
            }

            button {
                width: 100%;
            }

            .acciones {
                flex-direction: column;
            }
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
        <h2>¡Bienvenido al panel!</h2>
        <p>Administrá y organizá la información de tus países.</p>
    </div>

    <div class="resumen">
        <div>
            <span>TOTAL DE PAÍSES REGISTRADOS</span>
            <h2><?php echo $total; ?></h2>
        </div>
        <div class="icono-resumen">🌍</div>
    </div>

    <div class="tarjeta">
        <div class="titulo-tarjeta">
            <div class="icono">＋</div>
            <h2>Agregar nuevo país</h2>
        </div>

        <form action="agregar.php" method="POST">
            <input
                type="text"
                name="pais_descripcion"
                placeholder="Escribí el nombre del país..."
                required
            >
            <button type="submit">＋ Agregar país</button>
        </form>
    </div>

    <div class="tarjeta">
        <div class="titulo-tarjeta">
            <div class="icono">📋</div>
            <h2>Listado de países</h2>
        </div>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del país</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($total > 0) { ?>
                        <?php while ($pais = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td class="numero">
                                    #<?php echo $pais['pais_id']; ?>
                                </td>

                                <td class="nombre-pais">
                                    <?php echo htmlspecialchars($pais['pais_descripcion']); ?>
                                </td>

                                <td>
                                    <div class="acciones">
                                        <a class="editar"
                                           href="provincias.php?id=<?php echo $pais['pais_id']; ?>">
                                            📍 Provincias
                                        </a>
                                        <a class="editar"
                                           href="editar.php?id=<?php echo $pais['pais_id']; ?>">
                                            ✏ Editar
                                        </a>

                                        <a class="eliminar"
                                           href="eliminar.php?id=<?php echo $pais['pais_id']; ?>"
                                           onclick="return confirm('¿Seguro que deseas eliminar este país?')">
                                            🗑 Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3" class="vacio">
                                Todavía no hay países registrados.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<footer>
    Sistema de Gestión de Países y Provincias · Proyecto académico
</footer>

</body>
</html>
