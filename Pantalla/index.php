<?php
require 'config/database.php';
$db=new Database();
$con= $db->conectar();
$sql = $con->prepare("SELECT CUPON.TITULO, format (CUPON.PRECIO_OFERTA, 2) AS PRECIO_OFERTA_2, CUPON.FECHA_FIN, CUPON.DESCRIPCION, EMPRESA.NOMBRE_EMPRESA, format (CUPON.PRECIO_REGULAR, 2) AS PRECIO_REGULAR_2 FROM CUPON INNER JOIN EMPRESA ON CUPON.ID_EMPRESA=EMPRESA.ID");
$sql->execute();
$resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
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
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <span class="fs-4" style="color: black"> LA CUPONERA SV</span>
        </a>  
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0"></ul>

        <div class="col-md-3 text-end">
            <a href="login.php" style="text-decoration:none">
                <button type="button" class="btn btn-dark me-2">Login</button>
            </a>
            <a href="registro_cliente.html" style="text-decoration:none">
                <button type="button" class="btn btn-secondary">Sign-up</button>
            </a>
        </div>
    </header>
</div>
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
