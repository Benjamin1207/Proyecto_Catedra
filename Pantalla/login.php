<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['nombre_usuario'];
    $password = $_POST['password'];

    $db = new Database();
    $con = $db->conectar();

    // Habilitar el manejo de excepciones en PDO
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // Consulta para verificar el usuario en diferentes tablas
        $sql = $con->prepare("SELECT
        CASE
            WHEN EXISTS(SELECT * FROM admin WHERE user = :nombre_usuario_admin AND password = :password_admin) THEN 'admin'
            WHEN EXISTS(SELECT * FROM cliente WHERE user = :nombre_usuario_cliente AND password = :password_cliente) THEN 'cliente'
            WHEN EXISTS(SELECT * FROM empresa WHERE user = :nombre_usuario_empresa AND password = :password_empresa) THEN 'empresa'
            ELSE NULL
        END AS usuario,
        CASE
            WHEN EXISTS(SELECT id FROM cliente WHERE user = :nombre_usuario_cliente2 AND password = :password_cliente2) THEN (SELECT id FROM cliente WHERE user = :nombre_usuario_cliente3 AND password = :password_cliente3)
            ELSE NULL
        END AS id_cliente");
        $sql->bindParam(':nombre_usuario_admin', $user);
        $sql->bindParam(':password_admin', $password);
        $sql->bindParam(':nombre_usuario_cliente', $user);
        $sql->bindParam(':password_cliente', $password);
        $sql->bindParam(':nombre_usuario_cliente2', $user);
        $sql->bindParam(':password_cliente2', $password);
        $sql->bindParam(':nombre_usuario_cliente3', $user);
        $sql->bindParam(':password_cliente3', $password);
        $sql->bindParam(':nombre_usuario_empresa', $user);
        $sql->bindParam(':password_empresa', $password);
        $sql->execute();

        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        if ($resultado && $resultado['usuario']) {
            // Usuario autenticado correctamente
            $tabla = $resultado['usuario'];
            $idCliente = $resultado['id_cliente'];
            
            // Realizar las acciones correspondientes según la tabla en la que se encontró el usuario

            // Redireccionar según el tipo de usuario
            switch ($tabla) {
                case 'admin':
                    header('Location: login.php');
                    break;
                case 'cliente':
                    $tabla = $resultado['usuario'];

                    // Establecer las variables de sesión
                    session_start();
                    $_SESSION['tipo_usuario'] = $tabla;
                    $_SESSION['nombre_usuario'] = $user;
                    $_SESSION['id_cliente'] = $idCliente;
                    
                    // Redireccionar según el tipo de usuario
                    if ($tabla === 'cliente') {
                        header('Location: index_cliente.php');
                    } else if ($tabla === 'empresa') {
                        header('Location: login.php');
                    } else {
                        // Tipo de usuario desconocido
                        $error = "Tipo de usuario desconocido.";
                    }
                    break;
                case 'empresa':
                    header('Location: index_cliente.php?nombre_usuario=' . $user);
                    break;
                default:
                    // Tipo de usuario desconocido
                    $error = "Tipo de usuario desconocido.";
                    break;
            }
        } else {
            // Credenciales inválidas
            $error = "Credenciales inválidas. Inténtalo nuevamente.";
        }
    } catch (PDOException $e) {
        // Capturar la excepción y mostrar el mensaje de error
        $error = "Error en la consulta: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #daa8a8;
        }

        form {
            background-color: #fff;
            padding: 20px;
            max-width: 500px;
            margin: 20px auto;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="password"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-size: 18px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <?php if(isset($error)){ ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="POST" action="">
        <label>Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Iniciar Sesión">
    </form>
</body>
</html>

