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

// Obtener datos del cliente
$sqlCliente = $con->prepare("SELECT * FROM cliente WHERE user = :nombre_usuario");
$sqlCliente->bindParam(':nombre_usuario', $nombreUsuario);
$sqlCliente->execute();
$cliente = $sqlCliente->fetch(PDO::FETCH_ASSOC);

// Consulta para obtener los cupones
$sqlCupones = $con->prepare("SELECT CUPON.TITULO, format(CUPON.PRECIO_OFERTA, 2) AS PRECIO_OFERTA_2, CUPON.FECHA_FIN, CUPON.DESCRIPCION, EMPRESA.NOMBRE_EMPRESA, format(CUPON.PRECIO_REGULAR, 2) AS PRECIO_REGULAR_2 FROM CUPON INNER JOIN EMPRESA ON CUPON.ID_EMPRESA=EMPRESA.ID");
$sqlCupones->execute();
$resultado = $sqlCupones->fetchAll(PDO::FETCH_ASSOC);
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
    <title>La Cuponera SV</title>
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
    <a class="navbar-brand" href="#">LA CUPONERA SV</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Cupones comprados</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Facturas</a>
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
<main class="container">
    <div class="row">
        <?php foreach ($resultado as $row) { ?>
            <div class="col-md-6">
                <div class="h-100 p-5 bg-light text-dark border border-3 border-dark">
                    <h2><?php echo $row['TITULO']; ?></h2>
                    <p><?php echo $row['NOMBRE_EMPRESA']; ?></p>
                    <p><?php echo $row['DESCRIPCION']; ?></p>
                    <p>Precio regular: $<?php echo $row['PRECIO_REGULAR_2']; ?></p>
                    <b>Precio promoción: $<?php echo $row['PRECIO_OFERTA_2']; ?></b>
                    <p></p>  
                    <a href="login.php"><button class="btn btn-primary" type="button">COMPRAR</button></a>
                </div>
            </div>
        <?php } ?>
    </div>
</main>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>