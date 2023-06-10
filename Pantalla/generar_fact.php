<?php
require 'config/database.php';
require 'fpdf/fpdf.php';

$db = new Database();
$con = $db->conectar();

// Obtener el código de compra de la URL
if (!isset($_GET['compra_id'])) {
    header('Location: index.php');
    exit();
}

$codCompra = $_GET['compra_id'];

// Obtener información de la compra y el cupón asociado
$sqlCompra = $con->prepare("SELECT C.cod_compra, C.fecha_compra, CU.TITULO, CU.DESCRIPCION, CU.PRECIO_REGULAR, CU.PRECIO_OFERTA, CU.FECHA_CANJE, CL.nombre_completo, CL.direccion, CL.user
                            FROM compra C
                            INNER JOIN cupon CU ON C.id_cupon = CU.ID
                            INNER JOIN cliente CL ON C.id_cliente = CL.ID
                            WHERE C.cod_compra = :cod_compra");
$sqlCompra->bindParam(':cod_compra', $codCompra);
$sqlCompra->execute();
$compra = $sqlCompra->fetch(PDO::FETCH_ASSOC);

if (!$compra) {
    echo "Compra no encontrada.";
    exit();
}

$fechaCompra = date('d-m-Y', strtotime($compra['fecha_compra']));

// Generar el PDF de la factura
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Factura de Compra'), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode('Código de Compra: ') . $codCompra, 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Fecha de Compra: ') . $fechaCompra, 0, 1);
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Datos del Cliente:'), 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Nombre: ') . $compra['nombre_completo'], 0, 1);
$pdf->Cell(0, 10, utf8_decode('Dirección: ') . $compra['direccion'], 0, 1);
$pdf->Cell(0, 10, utf8_decode('Usuario: ') . $compra['user'], 0, 1);

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Datos del Cupón:'), 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Título: ') . $compra['TITULO'], 0, 1);

$pdf->SetFont('Arial', '', 10); // Ajusta el tamaño de fuente para el párrafo grande
$pdf->MultiCell(0, 10, utf8_decode('Descripción: ') . $compra['DESCRIPCION'], 0);

$pdf->SetFont('Arial', '', 14);
$precioRegular = number_format($compra['PRECIO_REGULAR'], 2);
$pdf->Cell(0, 10, utf8_decode('Precio Regular: $') . $precioRegular, 0, 1);

$precioOferta = number_format($compra['PRECIO_OFERTA'], 2);
$pdf->Cell(0, 10, utf8_decode('Precio Promoción: $') . $precioOferta, 0, 1);

$pdf->Cell(0, 10, utf8_decode('Utilizar antes de: ') . utf8_decode(date('d-m-Y', strtotime($compra['FECHA_CANJE']))), 0, 1);

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('TOTAL: $') . $precioOferta, 0, 1);




$pdf->Output('factura.pdf', 'F');

 // Redirigir al usuario a la página de descarga del PDF
header('Location: descargar_factura.php');
exit();


