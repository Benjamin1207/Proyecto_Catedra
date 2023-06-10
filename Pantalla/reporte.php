<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de la Cuponera SV</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Reportes de la Cuponera SV</h1>

        <?php
$conexion = mysqli_connect("localhost", "usuario", "contraseña", "cupones_bd");

if (mysqli_connect_errno()) {
    echo "Error al conectar a la base de datos: " . mysqli_connect_error();
    exit();
}

$sqlCuponesVendidos = "SELECT empresas.nombre AS nombre_empresa, COUNT(*) AS total_cupones_vendidos
                       FROM empresas
                       INNER JOIN ofertas ON empresas.id = ofertas.id_empresa
                       INNER JOIN cupones ON ofertas.id = cupones.id_oferta
                       WHERE cupones.estado = 'comprado'
                       GROUP BY empresas.id";

$resultadoCuponesVendidos = mysqli_query($conexion, $sqlCuponesVendidos);

if ($resultadoCuponesVendidos) {
    echo "Total de cupones vendidos por empresa:<br>";
    while ($fila = mysqli_fetch_assoc($resultadoCuponesVendidos)) {
        echo $fila['nombre_empresa'] . ": " . $fila['total_cupones_vendidos'] . "<br>";
    }
    echo "<br>";
} else {
    echo "Error al obtener el total de cupones vendidos: " . mysqli_error($conexion);
}
$sqlGananciasObtenidas = "SELECT empresas.nombre AS nombre_empresa, SUM(ofertas.precio_oferta * cupones.cantidad) AS total_ganancias
                          FROM empresas
                          INNER JOIN ofertas ON empresas.id = ofertas.id_empresa
                          INNER JOIN cupones ON ofertas.id = cupones.id_oferta
                          WHERE cupones.estado = 'comprado'
                          GROUP BY empresas.id";

$resultadoGananciasObtenidas = mysqli_query($conexion, $sqlGananciasObtenidas);

if ($resultadoGananciasObtenidas) {
    echo "Total de ganancias obtenidas por empresa:<br>";
    while ($fila = mysqli_fetch_assoc($resultadoGananciasObtenidas)) {
        echo $fila['nombre_empresa'] . ": $" . $fila['total_ganancias'] . "<br>";
    }
    echo "<br>";
} else {
    echo "Error al obtener el total de ganancias: " . mysqli_error($conexion);
}

$sqlVentasPorEmpresa = "SELECT empresas.nombre AS nombre_empresa, COUNT(*) AS total_ventas
                        FROM empresas
                        INNER JOIN ofertas ON empresas.id = ofertas.id_empresa
                        INNER JOIN cupones ON ofertas.id = cupones.id_oferta
                        WHERE cupones.estado = 'comprado'
                        GROUP BY empresas.id";

$resultadoVentasPorEmpresa = mysqli_query($conexion, $sqlVentasPorEmpresa);

if ($resultadoVentasPorEmpresa) {
    echo "Total de ventas por empresa:<br>";
    while ($fila = mysqli_fetch_assoc($resultadoVentasPorEmpresa)) {
        echo $fila['nombre_empresa'] . ": " . $fila['total_ventas'] . "<br>";
    }
    echo "<br>";
} else {
    echo "Error al obtener el total de ventas por empresa: " . mysqli_error($conexion);
}

$sqlGananciasPorEmpresa = "SELECT empresas.nombre AS nombre_empresa, SUM(ofertas.precio_oferta * cupones.cantidad * empresas.porcentaje_comision) AS total_ganancias
                           FROM empresas
                           INNER JOIN ofertas ON empresas.id = ofertas.id_empresa
                           INNER JOIN cupones ON ofertas.id = cupones.id_oferta
                           WHERE cupones.estado = 'comprado'
                           GROUP BY empresas.id";

$resultadoGananciasPorEmpresa = mysqli_query($conexion, $sqlGananciasPorEmpresa);

if ($resultadoGananciasPorEmpresa) {
    echo "Total de ganancias por empresa:<br>";
    while ($fila = mysqli_fetch_assoc($resultadoGananciasPorEmpresa)) {
        echo $fila['nombre_empresa'] . ": $" . $fila['total_ganancias'] . "<br>";
    }
} else {
    echo "Error al obtener el total de ganancias por empresa: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>

        <h2>Total de cupones vendidos por empresa</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Total de cupones vendidos</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($fila = mysqli_fetch_assoc($resultadoCuponesVendidos)) {
                    echo "<tr>";
                    echo "<td>" . $fila['nombre_empresa'] . "</td>";
                    echo "<td>" . $fila['total_cupones_vendidos'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Total de ganancias obtenidas por empresa</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Total de ganancias</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($fila = mysqli_fetch_assoc($resultadoGananciasObtenidas)) {
                    echo "<tr>";
                    echo "<td>" . $fila['nombre_empresa'] . "</td>";
                    echo "<td>$" . $fila['total_ganancias'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Total de ventas por empresa</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Total de ventas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($fila = mysqli_fetch_assoc($resultadoVentasPorEmpresa)) {
                    echo "<tr>";
                    echo "<td>" . $fila['nombre_empresa'] . "</td>";
                    echo "<td>" . $fila['total_ventas'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Total de ganancias por empresa</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Total de ganancias</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($fila = mysqli_fetch_assoc($resultadoGananciasPorEmpresa)) {
                    echo "<tr>";
                    echo "<td>" . $fila['nombre_empresa'] . "</td>";
                    echo "<td>$" . $fila['total_ganancias'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.
