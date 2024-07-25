<?php
//----------------------------------------------------------------------REPORTE DE ERRORES
ini_set('display_errors', 1);
ini_set('display_startup_error', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Coming+Soon&family=Darumadrop+One&display=swap" rel="stylesheet">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
    integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous" />

  <!-- PWA -->
  <link rel="manifest" href="./manifest.json" />
  <script type="text/javascript">
    if ("serviceWorker" in navigator) {
      navigator.serviceWorker.register("./scripts/sw.js");
    }

  </script>

  <!-- estilo -->
  <link rel="stylesheet" href="./styles/index.css" />
  <link rel="icon" href="./assets/img/icon.png" />
  <title>Proyecto Murales Interactivos</title>
</head>

<body>

  <div id="cuerpoPagina">
    <!-- -------------------------------------------------------------------------------------BODY -->
    <div id="pagina">
      <?php
      //----------------------------------------------------------------------CONECTAR CON DATABASE
      require './conector.php';

      //----------------------------------------------------------------------OBTENER LISTA DE MUROS
      $sql = "SELECT `UUID` FROM `muros`";
      $result = mysqli_query($conector, $sql);

      if ($result->num_rows > 0) { // Si hay resultados, convertirlos a formato JSON
        $rows = array();
        while ($row = $result->fetch_assoc()) {
          $rows[] = $row;
        }
        $JSONrows = json_encode($rows);
        echo ("<script> let muros = JSON.parse('$JSONrows'); </script>"); //guardar muros en javaScript
      } else {
        echo "No se encontraron datos.";
      }

      //----------------------------------------------------------------------RECIBIR LINK LEÍDO FUERA DE LA APP
      $param = 'null';
      if (!empty($_GET['muro'])) {
        $param = $_GET['muro'];
      }
      echo ("
        <script>
          let param = '$param';
        </script>
      ");
      ?>

      <!-- ----------------------------------------------------------------------CONTENIDO DE LA PÁGINA -->
      <div id="contenedorQR">
        <h1>Lector de qr</h1>
        <div id="reader"> <!-- ESCÁNER DE QR --> </div>
      </div>
      <div class="contenedorTexto">
        <h3>¿Qué puedo hacer con la app?</h3>
        <h4>Escanear el Qr de un muro e interactuar con él. </h4>
        <h4>Crear tu propio muro y compartirlo con quien quieras. </h4>
      </div>

      <button type="button" class="customButton" id="botonCrearMuro" onclick="location.assign('./php/crearMuro.php')">
        <h4>¡Quiero crear mi muro!</h4>
      </button>

    </div>


    <!-- -------------------------------------------------------------------------------------VALIDAR QR -->
    <script>
      validarQR(param);

      function validarQR(QR) {
        for (let i = 0; i < muros.length; i++) {
          if (QR.includes(muros[i].UUID)) {
            console.log('link valido');

            sessionStorage.setItem('muro', muros[i].UUID); //guardar UUID del muro
            location.assign(`./php/notas.php?muro=${muros[i].UUID}`);
            break; // detener el bucle una vez que encontramos el UUID correcto        } else {
            console.log('link inválido');
          }
        }
      }
    </script>

    <!-- -------------------------------------------------------------------------------------LIBRERÍA ESCÁNER -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
      validarQR("hola");

      // ----------------------------------------------------------------------ESCANEO EXITOSO
      function onScanSuccess(decodedText, decodedResult) {
        // handle the scanned code as you like, for example:
        console.log(`Code matched = ${decodedText}`, decodedResult);

        if (decodedText.includes("/php/notas.php?muro=")) { //***************************************** */
          validarQR(decodedText);
          console.log("entro");
        }
      }

      // ----------------------------------------------------------------------ESCANEO FALLIDO
      function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        // for example:
        // console.warn(`Code scan error = ${error}`);
      }

      let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: { width: 250, height: 250 } },
        /* verbose= */ false);
      html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>

    <!-- -------------------------------------------------------------------------------------BOOTSTRAP -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"
      integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ"
      crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"
      integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd"
      crossorigin="anonymous"></script>

  </div>
</body>

</html>