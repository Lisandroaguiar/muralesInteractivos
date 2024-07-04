<?php
require '../conector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $muro = $_POST['muro'];
    $mensaje = $_POST['mensaje'];
    $posX = $_POST['posX'];
    $posY = $_POST['posY'];
    $color = $_POST['color'];

    if (!empty($mensaje) && !empty($muro)) {
        $sql = "INSERT INTO `notitas`(`muro`, `mensaje`, `posX`, `posY`, `color`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conector->prepare($sql);
        $stmt->bind_param("ssdds", $muro, $mensaje, $posX, $posY, $color);
        $stmt->execute();

        echo "Nuevo mensaje guardado: ($mensaje)";
    } else {
        echo "No hay notita";
    }
}
?>
