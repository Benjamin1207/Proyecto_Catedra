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
