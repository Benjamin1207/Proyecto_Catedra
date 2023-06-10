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

// Consulta para obtener las compras de cupones realizadas por el usuario
$sqlCompras = $con->prepare("SELECT C.cod_compra, CP.TITULO, CP.PRECIO_OFERTA, CP.FECHA_CANJE, CP.DESCRIPCION, EMP.NOMBRE_EMPRESA, CP.PRECIO_REGULAR, C.fecha_compra
                            FROM compra C
                            INNER JOIN cupon CP ON C.id_cupon = CP.ID
                            INNER JOIN empresa EMP ON CP.ID_EMPRESA = EMP.ID
                            WHERE C.id_cliente = :id_cliente");
$sqlCompras->bindParam(':id_cliente', $idCliente);
$sqlCompras->execute();
$compras = $sqlCompras->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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
          <a class="nav-link active" aria-current="page" href="#">Historial</a>
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
<p></p>
<main class="container">
    <h1>Historial de Compras</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Código de Compra</th>
                <th>Oferta</th>
                <th>Empresa</th>
                <th>Descripción</th>
                <th>Precio Oferta</th>
                <th>Precio Regular</th>
                <th>Fecha de Compra</th>
                <th>Disponible hasta</th>
                <th>Factura</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($compras as $compra) { ?>
                <tr>
                    <td><?php echo $compra['cod_compra']; ?></td>
                    <td><?php echo $compra['TITULO']; ?></td>
                    <td><?php echo $compra['NOMBRE_EMPRESA']; ?></td>
                    <td><?php echo $compra['DESCRIPCION']; ?></td>
                    <td>$<?php echo $compra['PRECIO_OFERTA']; ?></td>
                    <td>$<?php echo $compra['PRECIO_REGULAR']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($compra['fecha_compra'])); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($compra['FECHA_CANJE'])); ?></td>
                    <td>
                        <a href="generar_fact.php?compra_id=<?php echo $compra['cod_compra']; ?>" target="_blank">Factura</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</main>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>

