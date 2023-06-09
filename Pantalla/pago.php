<?php
require 'config/database.php';
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
    $anioActual = date('Y'); // Obtener el año actual completo

    // Obtener el mes y año de la fecha de vencimiento ingresada
    $partesFecha = explode('/', $fechaVencimiento);
    $mesVencimiento = $partesFecha[0];
    $anioVencimiento = $partesFecha[1];

    if ($anioVencimiento < $anioActual || ($anioVencimiento == $anioActual && $mesVencimiento < $mesActual)) {
        echo "La tarjeta ha vencido.";
        exit();
    }

    // Guardar la compra en la base de datos
    $codCompra = uniqid(); // Generar un código único de compra
    $idEmpresa = $cupon['id_empresa'];
    $cantidad = 1; // Solo se permite comprar un cupón a la vez
    $total = $cupon['precio_oferta'];

    $sqlGuardarCompra = $con->prepare("INSERT INTO compra (cod_compra, id_cupon, id_cliente, id_empresa, cantidad, total) VALUES (:cod_compra, :id_cupon, :id_cliente, :id_empresa, :cantidad, :total)");
    $sqlGuardarCompra->bindParam(':cod_compra', $codCompra);
    $sqlGuardarCompra->bindParam(':id_cupon', $cuponId);
    $sqlGuardarCompra->bindParam(':id_cliente', $idCliente);
    $sqlGuardarCompra->bindParam(':id_empresa', $idEmpresa);
    $sqlGuardarCompra->bindParam(':cantidad', $cantidad);
    $sqlGuardarCompra->bindParam(':total', $total);
    $sqlGuardarCompra->execute();

    echo "Compra realizada exitosamente.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Compra de Cupones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #daa8a8;
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
<h1>Compra de Cupones</h1>
<p>Para comprar un cupón, por favor siga los siguientes pasos:</p>
<form action="" method="POST">
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
</body>
</html>
