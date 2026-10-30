
<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = intval($_POST["pais_id"]);
    $pais = trim($_POST["pais_descripcion"]);

    if ($id > 0 && $pais != "") {
        $sql = "UPDATE paises SET pais_descripcion = ? WHERE pais_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $pais, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error al modificar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Datos incorrectos.";
    }

} else {
    if (isset($_GET["id"])) {
        $id = intval($_GET["id"]);

        $sql = "SELECT * FROM paises WHERE pais_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $pais = $resultado->fetch_assoc();
        $stmt->close();

        if (!$pais) {
            die("País no encontrado.");
        }
    } else {
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar país</title>
</head>
<body>
    <h2>Editar país</h2>

    <form method="POST" action="editar.php">
        <input type="hidden" name="pais_id"
               value="<?php echo htmlspecialchars($pais['pais_id']); ?>">

        <input type="text" name="pais_descripcion"
               value="<?php echo htmlspecialchars($pais['pais_descripcion']); ?>"
               required>

        <button type="submit">Guardar cambios</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
