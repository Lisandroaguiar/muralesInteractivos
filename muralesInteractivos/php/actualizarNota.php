<?php
require '../conector.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensaje = $_POST['mensaje'];
    $posX = $_POST['posX'];
    $posY = $_POST['posY'];
    $color = $_POST['color']; // Recibimos el color desde la solicitud

    if (!empty($mensaje)) {
        // Realizar la actualización en la base de datos
        $sql = "UPDATE `notitas` SET `posX`=?, `posY`=?, `color`=? WHERE `mensaje`=?";
        $stmt = $conector->prepare($sql);
        $stmt->bind_param("ddss", $posX, $posY, $color, $mensaje);
        $stmt->execute();

        echo "Posición de la nota '$mensaje' actualizada";
    } else {
        echo "Mensaje de la nota no válido";
    }
}
?>
