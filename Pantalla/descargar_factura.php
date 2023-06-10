<?php
// Verificar si el archivo PDF existe
if (file_exists('factura.pdf')) {
    // Enviar los encabezados para forzar la descarga del archivo
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="factura.pdf"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('factura.pdf'));
    readfile('factura.pdf');

    // Eliminar el archivo PDF después de la descarga
    unlink('factura.pdf');

    exit();
} else {
    echo "El archivo de factura no se encuentra disponible para descargar.";
}
?>
<?php
// Obtener el estado del pago desde la URL
$estadoPago = isset($_GET['estado_pago']) ? $_GET['estado_pago'] : '';

// Verificar el estado del pago y mostrar la alerta correspondiente
if ($estadoPago === 'success') {
    echo '<script>alert("Pago realizado correctamente.");</script>';
} elseif ($estadoPago === 'error') {
    echo '<script>alert("El pago no se ha completado.");</script>';
}
?>