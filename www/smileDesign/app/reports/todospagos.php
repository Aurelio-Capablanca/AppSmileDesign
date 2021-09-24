<?php
require('../helpers/report.php');
require('../models/pagos.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Porcentajes de Pagos por Tipo');

// Se instancia el módelo Categorías para obtener los datos.
$pago = new  Pagos;
// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
if ($dataPagos = $pago->readAllTIPO()) {
    // Se recorren los registros ($dataCategorias) fila por fila ($rowCategoria).
    foreach ($dataPagos as $rowTipo) {
        // Se establece un color de relleno para mostrar el nombre de la categoría.
        $pdf->SetFillColor(0,0,0);
        // Se establece la fuente para el nombre de la categoría.
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(255);
        // Se imprime una celda con el nombre de la categoría.
        $pdf->Cell(0, 10, utf8_decode('Tipo Pago: '.$rowTipo['tipopago']), 1, 1, 'C', 1);        
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($pago->setId($rowTipo['idtipopago'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataCuentas = $pago->readTipoPagos()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(220);
                $pdf->SetTextColor(9,9,9);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Helvetica', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(0, 10, utf8_decode('Porcentaje: '), 1, 1, 'C', 1);                
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataCuentas as $row) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->Cell(0, 10, utf8_decode($row['pagodebe']), 1, 1,'C');                    
                }
            } else {
                $pdf->SetTextColor(9,9,9);
                $pdf->Cell(0, 10, utf8_decode('No hay Pagos para este Tipo de Pago'), 1, 1);
            }
        } else {
            $pdf->SetTextColor(9,9,9);
            $pdf->Cell(0, 10, utf8_decode('Tipo Pago incorrecto o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(0, 10, utf8_decode('No hay Datos para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>