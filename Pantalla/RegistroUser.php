<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $usuario = $_POST['user'];
    $correo = $_POST['email'];
    $contrasena = $_POST['pass1'];
    $nombre = $_POST['name'];
    $apellidos = $_POST['lastname'];
    $dui = $_POST['dui'];
    $fechaNacimiento = $_POST['birth'];

    // Validar la edad del usuario
    $fechaActual = new DateTime();
    $fechaNacimiento = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
    $edad = $fechaActual->diff($fechaNacimiento)->y;

    if ($edad < 18) {
        echo "No cumple con la edad adecuada";
    } else {
        // Realizar el proceso de registro del cliente
        // ...

        // Mostrar mensaje de éxito u otra acción necesaria
        echo "Registro exitoso";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Registro</title>
</head>
<body>

<br>
<div class="container" style="max-width: 650px; min-width: 400px;">
  <div class="card">
    <h2 class="card-header text-center">Formulario de Registro</h2>
    <div class="card-body">
      <form id="myForm" method="POST" action="">
        <div class="form-group">
          <legend>Datos de la Cuenta</legend>
          <label for="user">Usuario:</label>
          <input type="text" class="form-control" id="user" name="user" placeholder="Usuario">
          <label for="email">Correo Electronico:</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="nombre@ejemplo.com">
          <label for="Pass1">Contraseña:</label>
          <input type="password" class="form-control" id="pass1" name="pass1" placeholder="Contraseña">
          <label for="name">Nombres:</label>
          <input type="text" class="form-control" id="name" name="name">
          <label for="lasname">Apellidos:</label>
          <input type="text" class="form-control" id="lastname" name="lastname">
          <label for="dui">DUI:</label>
          <input type="text" class="form-control" id="dui" name="dui" placeholder="12345678-9">
          <label for="birth">Fecha de Nacimiento:</label>
          <input type="date" class="form-control" id="birth" name="birth">
        </div>

        <input type="submit" class="btn btn-success" id="boton" value="Registrar">
        <input type="reset" class="btn btn-danger" id="boton" value="Limpiar">
      </form>
    </div>
  </div>
</div>
    
</body>
</html>
