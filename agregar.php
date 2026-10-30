
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Error: el formulario no está enviando los datos correctamente.");
}

if (!isset($_POST["pais_descripcion"])) {
    die("Error: no se recibió el nombre del país.");
}

$pais = trim($_POST["pais_descripcion"]);

if ($pais === "") {
    die("Error: el nombre del país está vacío.");
}

$sql = "INSERT INTO paises (pais_descripcion) VALUES (?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("s", $pais);

if (!$stmt->execute()) {
    die("Error al guardar: " . $stmt->error);
}

$stmt->close();

header("Location: index.php");
exit;
?>
