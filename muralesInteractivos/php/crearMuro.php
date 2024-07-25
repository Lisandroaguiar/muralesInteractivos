<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Coming+Soon&family=Darumadrop+One&display=swap" rel="stylesheet">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css"
    integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous" />

  <!-- LIBRERÍA GENERADOR -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

  <!-- estilo -->
  <link rel="stylesheet" href="../styles/index.css" />
  <link rel="stylesheet" href="../styles/crearMuro.css" />
  <link rel="icon" href="../assets/img/icon.png" />
  <title>Crear Muro</title>
</head>

<body>
  <div id="pagina">
    <div id="mostrarQR" class="flex">
      <h1>Generador de muros</h1>
      <div class="imagenQr flex" id="qrFantasma">
        <!-- <img src="../assets/img/codigoQR.png" alt=""> QR fantasma -->
        <div id="botonGenerar" class="flex">
          <form method="post" action="crearMuro.php" id="crearMuro" class="flex">
            <button type="submit" class="customButton" id="botonGenerarMuro">
              <h3>¡Generar muro!</h3>
            </button>
            <input type="hidden" name="crearMuroInput" id="crearMuroInput">
          </form>
        </div>
      </div>
      <div class="imagenQr flex hidden" id="qrcode"> <!-- QR GENERADO --> </div>
    </div>

    <?php
    require '../conector.php';
    $dom = $_ENV['DOM'];
    ?>

    <div id="generador">
      <!-- <div>
        <div id="botonGenerar">
          <form method="post" action="crearMuro.php" id="crearMuro" class="flex">
            <button type="submit" class="customButton" id="botonGenerarMuro">
              <h3>¡Generar muro!</h3>
            </button>
            <input type="hidden" name="crearMuroInput" id="crearMuroInput">
          </form>
        </div>
      </div> -->

      <div id="boton2" class="flex hidden">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crearMuroInput'])) {
          $UUID = uniqid("notitas");
          $nuevoLocal = "/php/notas.php?muro=" . $UUID;
          $nuevoGlobal = $dom . $nuevoLocal;

          $sql = "INSERT INTO `muros`(`UUID`) VALUES ('$UUID')";
          $stmt = $conector->prepare($sql);
          $stmt->execute();
          echo ("
            <script>
              let nuevoMuro = '$nuevoGlobal';
              console.log(nuevoMuro);
              new QRCode(document.getElementById('qrcode'), nuevoMuro);
              document.getElementById('boton2').classList.remove('hidden');
              document.getElementById('qrcode').classList.remove('hidden');
              document.getElementById('botonGenerar').style.display = 'none';
              document.getElementById('qrFantasma').style.display = 'none';
              document.getElementById('boton2').innerHTML = `
                <button id='dejarMensajeBtn' class='customButton' onclick=location.assign('$nuevoLocal')>
                  <h3>¡Dejá un mensaje!</h3>
                </button>
                <button id='downloadBtn' class='customButton'>
                  <h3>Descargar muro</h3>
                </button>
              `;
              document.getElementById('downloadBtn').addEventListener('click', function() {
                html2canvas(document.getElementById('qrcode')).then(canvas => {
                  const imgData = canvas.toDataURL('image/png');
                  const pdf = new jspdf.jsPDF();
                  const pageWidth = pdf.internal.pageSize.getWidth();
                  const pageHeight = pdf.internal.pageSize.getHeight();
                  pdf.setFillColor(255,243,175);
                  pdf.rect(0, 0, pageWidth, pageHeight, 'F'); // Fondo rojo

                  pdf.setFontSize(24);
                  pdf.text('Escanea este código QR para acceder a tu muro:', 10, 20);
                  pdf.addImage(imgData, 'PNG', pageWidth/2-50, pageHeight/2-50,100,100); // Puedes ajustar la posición y el tamaño
                  pdf.save('QR_Code.pdf');
                });
              });
            </script>
          ");
        }
        ?>
      </div>

    </div>
    <!-- <div class="contenedorTexto">
      <h4>Recordá guardar el QR de tu muro y dejar tu primer mensaje.</h4>
    </div> -->
    <button type="button" class="customButton" id="botonVolver" onclick="location.assign('../index.php')">
      <h4>Volver al escáner</h4>
    </button>
    <!-- <a href="../index.php">
      <div id="botonVolver"><img src="../assets/img/botonVolver.png" alt=""></div>
    </a> -->
  </div>

  <!-- Bootstrap -->
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"
    integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"
    integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd"
    crossorigin="anonymous"></script>
</body>

</html>