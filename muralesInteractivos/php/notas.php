<?php
require '../conector.php';

$muro = $_GET['muro'];

// Fetch existing notes
$sql = "SELECT * FROM `notitas` WHERE `muro` = ?";
$stmt = $conector->prepare($sql);
$stmt->bind_param("s", $muro);
$stmt->execute();
$result = $stmt->get_result();

$notitas = [];
while ($row = $result->fetch_assoc()) {
    $notitas[] = $row;
}

// Verifica si los datos se están recuperando correctamente
// var_dump($notitas);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" />

    <script src="../libraries/p5.min.js"></script>
    <script src="../libraries/p5.sound.min.js"></script>

    <link rel="stylesheet" href="../styles/notas.css" />
    <link rel="stylesheet" href="../styles/index.css" />
    <link rel="icon" href="../assets/img/icon.png" />
    <title>Proyecto Murales Interactivos</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coming+Soon&family=Darumadrop+One&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- <div id="imagenFondo">
        <img src="../assets/img/fondoNota.png" alt="">
    </div> -->

    <div id="pagina">
        <div id="p5">
            <div id="canvas"> <!-- CANVAS P5 --> </div>
            <form method="post" action="guardarNota.php" id="falseForm">
                <input type="hidden" name="mensaje" id="falseInputNotita">
                <input type="hidden" name="muro" id="falseInputUUID" value="<?php echo $muro; ?>">
                <input type="hidden" name="posX" id="falseInputPosX">
                <input type="hidden" name="posY" id="falseInputPosY">
                <input type="hidden" name="color" id="falseInputColor">
            </form>
        </div>

        <!-- <div id=botonGenerarMuro style="display: none;">
            <a href="./crearMuro.php">

                <img src="../assets/img/botonGenerarMuro.png" alt='Imagen de boton'>

            </a>
        </div> -->
    
        <div class="contenedorBotones">
        <button type="button" class="customButton" id="botonCrearMuro" onclick="location.assign('./crearMuro.php')"
            style="display: none;">
            <h4>¡Quiero crear mi muro!</h4>
        </button>
        <button type="button" class="customButton" id="botonDejarNota" onclick="dejarNota()">
            <h3>¡Dejar Nota!</h3>
        </button>

        <button type="button" class="customButton" id="botonVolver" onclick="location.assign('../index.php')">
            <h4>Volver al escáner</h4>
        </button>
        </div>
    </div>

    <script>
        var existingNotes = <?php echo json_encode($notitas); ?>;
    </script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <script src="../scripts/sketch.js"></script>
</body>

</html>