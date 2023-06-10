<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombreEmpresa = $_POST["nombre_empresa"];
  $nitEmpresa = $_POST["nit_empresa"];
  $direccionEmpresa = $_POST["direccion_empresa"];
  $telefonoEmpresa = $_POST["telefono_empresa"];
  $correoEmpresa = $_POST["correo_empresa"];
  $usuarioEmpresa = $_POST["usuario_empresa"];
  $contrasenaEmpresa = $_POST["contrasena_empresa"];

  $conexion = mysqli_connect("localhost", "usuario", "contraseña", "cupones_bd");

  if (mysqli_connect_errno()) {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    exit();
  }

  $sql = "INSERT INTO empresas (nombre, nit, direccion, telefono, correo, usuario, contrasena)
          VALUES ('$nombreEmpresa', '$nitEmpresa', '$direccionEmpresa', '$telefonoEmpresa', '$correoEmpresa', '$usuarioEmpresa', '$contrasenaEmpresa')";

  if (mysqli_query($conexion, $sql)) {
    header("Location: inicio_sesion.php");
    exit();
  } else {
    echo "Error al registrar la empresa: " . mysqli_error($conexion);
  }

  mysqli_close($conexion);
}
?>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Nombre de empresa:</label>
        <input type="text" name="nombreEmpresa" required value="<?php echo $nombreEmpresa; ?>"><br>

        <label>NIT de empresa:</label>
        <input type="text" name="nitEmpresa" required value="<?php echo $nitEmpresa; ?>"><br>

        <label>Dirección:</label>
        <input type="text" name="direccion" required value="<?php echo $direccion; ?>"><br>

        <label>Teléfono:</label>
        <input type="tel" name="telefono" required value="<?php echo $telefono; ?>"><br>

        <label>Correo electrónico:</label>
        <input type="email" name="correo" required value="<?php echo $correo; ?>"><br>

        <label>Usuario:</label>
        <input type="text" name="usuario" required value="<?php echo $usuario; ?>"><br>

        <label>Contraseña:</label>
        <input type="password" name="contrasena" required value="<?php echo $contrasena; ?>"><br>

  <button type="submit">Registrar empresa</button>
</form>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <title>Registro de Empresa</title>
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
                <a class="nav-link active" aria-current="page"" href="index_cliente.php">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Historial</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="logout.php">Cerrar Sesión</a>
              </li>
            </ul>
          </div>
        </div>
    </nav>
    
    <div class="container">
        <form method="POST" action="?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>
            <div class="mb-3">
                <label for="nombre_empresa" class="form-label">Nombre de empresa:</label>
                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" required>
            </div>
            <div class="mb-3">
                <label for="nit_empresa" class="form-label">NIT de empresa:</label>
                <input type="text" class="form-control" id="nit_empresa" name="nit_empresa" required>
            </div>
            <div class="mb-3">
                <label for="direccion_empresa" class="form-label">Dirección:</label>
                <input type="text" class="form-control" id="direccion_empresa" name="direccion_empresa" required>
            </div>
            <div class="mb-3">
                <label for="telefono_empresa" class="form-label">Teléfono:</label>
                <input type="tel" class="form-control" id="telefono_empresa" name="telefono_empresa" required>
            </div>
            <div class="mb-3">
                <label for="correo_empresa" class="form-label">Correo electrónico:</label>
                <input type="email" class="form-control" id="correo_empresa" name="correo_empresa" required>
            </div>
            <div class="mb-3">
                <label for="usuario_empresa" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="usuario_empresa" name="usuario_empresa" required>
            </div>
            <div class="mb-3">
                <label for="contrasena_empresa" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="contrasena_empresa" name="contrasena_empresa" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar empresa</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

