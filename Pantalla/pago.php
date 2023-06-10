<?php
require 'config/database.php';
require 'fpdf/fpdf.php';

$db = new Database();
$con = $db->conectar();

// Verificar si el usuario está autenticado como cliente
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header('Location: login.php');
    exit();
}

$nombreUsuario = $_SESSION['nombre_usuario'];
$idCliente = $_SESSION['id_cliente'];

// Obtener datos del cliente
$sqlCliente = $con->prepare("SELECT * FROM cliente WHERE user = :nombre_usuario");
$sqlCliente->bindParam(':nombre_usuario', $nombreUsuario);
$sqlCliente->execute();
$cliente = $sqlCliente->fetch(PDO::FETCH_ASSOC);

// Verificar si se ha seleccionado un cupón
if (!isset($_GET['cupon_id'])) {
    header('Location: index.php');
    exit();
}

$cuponId = $_GET['cupon_id'];

// Obtener información del cupón seleccionado
$sqlCupon = $con->prepare("SELECT * FROM cupon WHERE id = :cupon_id");
$sqlCupon->bindParam(':cupon_id', $cuponId);
$sqlCupon->execute();
$cupon = $sqlCupon->fetch(PDO::FETCH_ASSOC);

// Verificar si el cliente ha alcanzado el límite de 5 cupones
$sqlTotalCompras = $con->prepare("SELECT COUNT(*) AS total_compras FROM compra WHERE id_cliente = :id_cliente");
$sqlTotalCompras->bindParam(':id_cliente', $idCliente);
$sqlTotalCompras->execute();
$totalCompras = $sqlTotalCompras->fetch(PDO::FETCH_ASSOC)['total_compras'];

if ($totalCompras >= 5) {
    echo "No puedes comprar más de 5 cupones.";
    exit();
}

// Validar la fecha de vencimiento de la tarjeta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroTarjeta = $_POST['numero_tarjeta'];
    $fechaVencimiento = $_POST['fecha_vencimiento'];

    // Obtener el mes y año actuales
    $mesActual = date('m');
    $anioActual = date('Y');

    // Obtener el mes y año de la fecha de vencimiento ingresada
    $partesFecha = explode('/', $fechaVencimiento);
    $mesVencimiento = intval($partesFecha[0]); // Convertir a número entero
    $anioVencimiento = intval($partesFecha[1]); // Convertir a número entero

    // Obtener el primer día del mes y año de vencimiento
    $primerDiaVencimiento = date('Y-m-d', mktime(0, 0, 0, $mesVencimiento, 1, $anioVencimiento));

    // Obtener la fecha actual
    $fechaActual = date('Y-m-d');

    if ($fechaActual >= $primerDiaVencimiento) {
        echo "La tarjeta ha vencido.";
        exit();
    }

    // Guardar la compra en la base de datos
    $codCompra = uniqid(); // Generar un código único de compra
    $idEmpresa = $cupon['id_empresa'];
    $cantidad = 1; // Solo se permite comprar un cupón a la vez
    $total = $cupon['precio_oferta'];
    $fechaCompra = date('Y-m-d'); // Obtener la fecha actual en el formato deseado (YYYY-MM-DD)

    $sqlGuardarCompra = $con->prepare("INSERT INTO compra (cod_compra, id_cupon, id_cliente, id_empresa, cantidad, total, fecha_compra) VALUES (:cod_compra, :id_cupon, :id_cliente, :id_empresa, :cantidad, :total, :fecha_compra)");
    $sqlGuardarCompra->bindParam(':cod_compra', $codCompra);
    $sqlGuardarCompra->bindParam(':id_cupon', $cuponId);
    $sqlGuardarCompra->bindParam(':id_cliente', $idCliente);
    $sqlGuardarCompra->bindParam(':id_empresa', $idEmpresa);
    $sqlGuardarCompra->bindParam(':cantidad', $cantidad);
    $sqlGuardarCompra->bindParam(':total', $total);
    $sqlGuardarCompra->bindParam(':fecha_compra', $fechaCompra);
    $sqlGuardarCompra->execute();


    // Generar el PDF de la factura
    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, utf8_decode('Factura de Compra'), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode('Código de Compra: ') . $codCompra, 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode('Fecha de Compra: ') . date('d-m-Y', strtotime($fechaCompra)), 0, 1);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, utf8_decode('Datos del Cliente:'), 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode('Nombre: ') . $cliente['nombre_completo'], 0, 1);
    $pdf->Cell(0, 10, utf8_decode('Dirección: ') . $cliente['direccion'], 0, 1);
    $pdf->Cell(0, 10, utf8_decode('Usuario: ') . $cliente['user'], 0, 1);

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, utf8_decode('Datos del Cupón:'), 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, utf8_decode('Título: ') . $cupon['titulo'], 0, 1);

    $pdf->SetFont('Arial', '', 10); // Ajusta el tamaño de fuente para el párrafo grande
    $pdf->MultiCell(0, 10, utf8_decode ('Descripción: ') . $cupon['descripcion'], 0);

    $pdf->SetFont('Arial', '', 14);
    $precioRegular = number_format($cupon['precio_regular'], 2);
    $pdf->Cell(0, 10, utf8_decode ('Precio Regular: $') . $precioRegular, 0, 1);

    $precioOferta = number_format($cupon['precio_oferta'], 2);
    $pdf->Cell(0, 10, utf8_decode ('Precio Promoción: $') . $precioOferta, 0, 1);

    $pdf->Cell(0, 10, utf8_decode('Utilizar antes de: ') . utf8_decode(date('d-m-Y', strtotime($cupon['fecha_canje']))), 0, 1);
;   

    $pdf->SetFont('Arial', 'B', 16);
    $precioOferta = number_format($cupon['precio_oferta'], 2);
    $pdf->Cell(0, 10, utf8_decode ('TOTAL: $') . $precioOferta, 0, 1);

    $pdf->Output('factura.pdf', 'F');

    // Redirigir al usuario a la página de descarga del PDF
    header('Location: descargar_factura.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra de Cupones</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="/style.css" rel="stylesheet">
    <title>Historial de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: light;
        }
    </style>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: light;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        p {
            font-size: 18px;
            color: #666;
        }

        ol {
            list-style-type: decimal;
            margin-left: 20px;
            margin-top: 20px;
        }

        ul {
            list-style-type: none;
            margin-left: 0;
            padding-left: 0;
        }

        li {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        label {
            display: block;
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
        }

        input[type="text"] {
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
            padding: 5px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            border: none;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand">LA CUPONERA SV</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
        <a class="nav-link" href="index_cliente.php">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="historial_compras.php">Historial</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
        </li>
      </ul>
      <span class="navbar-text">
        <?php echo $cliente['user']; ?>
      </span>
    </div>
  </div>
</nav>
<h1>Compra de Cupones</h1>
<p>Para comprar un cupón, por favor siga los siguientes pasos:</p>
<form id="formularioCompra" action="" method="POST">
    <ol>
        <li>
            Cupón seleccionado:
            <ul>
                <li><strong>Título:</strong> <?php echo $cupon['titulo']; ?></li>
                <li><strong>Descripción:</strong> <?php echo $cupon['descripcion']; ?></li>
                <li><strong>Precio regular:</strong> $<?php echo $cupon['precio_regular']; ?></li>
                <li><strong>Precio promoción:</strong> $<?php echo $cupon['precio_oferta']; ?></li>
            </ul>
        </li>
        <li>Ingrese los datos de su tarjeta de crédito/débito:</li>
        <ul>
            <li>
                <label for="numero_tarjeta">Número de tarjeta:</label>
                <input type="text" name="numero_tarjeta" id="numero_tarjeta" placeholder="Ingrese el número de su tarjeta" required>
            </li>
            <li>
                <label for="fecha_vencimiento">Fecha de vencimiento:</label>
                <input type="text" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="MM/AA" required>
            </li>
            <li>
                <label for="cvv">Código de seguridad (CVV):</label>
                <input type="text" name="cvv" id="cvv" placeholder="Ingrese el CVV de su tarjeta" required>
            </li>
        </ul>
        <li>
            <button type="submit" name="comprar">Comprar</button>
        </li>
        <li>Si la compra se realizó exitosamente, se generará la factura con el código único por cupón.</li>
        <li>El registro de la compra se guardará en el historial de compras del cliente.</li>
        <li>Recuerde que puede comprar un máximo de 5 cupones.</li>
    </ol>
</form>
<script>
// Obtener el campo de fecha de vencimiento
const fechaVencimientoInput = document.getElementById('fecha_vencimiento');

// Agregar el evento 'input' para detectar cambios en el campo
fechaVencimientoInput.addEventListener('input', function (event) {
  // Obtener el valor ingresado en el campo
  let fechaVencimiento = event.target.value;

  // Eliminar cualquier carácter que no sea un número o "/"
  fechaVencimiento = fechaVencimiento.replace(/[^0-9/]/g, '');

  // Verificar si se ha ingresado el mes completo
  if (fechaVencimiento.length >= 2) {
    // Autocompletar con el símbolo "/" si no está presente
    if (fechaVencimiento.charAt(2) !== '/') {
      fechaVencimiento = fechaVencimiento.slice(0, 2) + '/' + fechaVencimiento.slice(2);
    }
  }

  // Limitar la longitud del campo a 5 caracteres (MM/YY)
  fechaVencimiento = fechaVencimiento.slice(0, 5);

  // Actualizar el valor del campo
  fechaVencimientoInput.value = fechaVencimiento;
});
</script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
