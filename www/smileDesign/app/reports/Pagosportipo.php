<?php
require('../helpers/report.php');
require('../models/pagos.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Cantidad equipo por equipo');

// Se instancia el módelo Categorías para obtener los datos.
$categoria = new Pagos;

// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
if ($dataCategorias = $categoria->readPago()) {
    // Se recorren los registros ($dataCategorias) fila por fila ($rowCategoria).
    foreach ($dataCategorias as $rowCategoria) {
        // Se establece un color de relleno para mostrar el nombre de la categoría.
        $pdf->SetFillColor(175);
        // Se establece la fuente para el nombre de la categoría.
        $pdf->SetFont('Times', 'B', 12);
        // Se imprime una celda con el nombre de la categoría.
        $pdf->Cell(0, 10, utf8_decode('Tipo de pago: '.$rowCategoria['tipopago']), 1, 1, 'C', 1);
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($categoria->setId($rowCategoria['idtipopago'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $categoria->readCantidad()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(225);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Times', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                
                $pdf->Cell(48, 10, utf8_decode('Pago debe'), 1, 0, 'C', 1);
                $pdf->Cell(48, 10, utf8_decode('Pago abono'), 1, 0, 'C', 1);
                $pdf->Cell(48, 10, utf8_decode('Pago total'), 1, 0, 'C', 1);
                $pdf->Cell(48, 10, utf8_decode('Pago saldo'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Times', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->Cell(48, 10, utf8_decode($rowProducto['pagodebe']), 1, 0);
                    $pdf->Cell(48, 10, $rowProducto['pagoabono'], 1, 0);
                    $pdf->Cell(48, 10, $rowProducto['pagototal'], 1, 0);
                    $pdf->Cell(48, 10, utf8_decode($rowProducto['pagosaldo']), 1, 1);
                }
            } else {
                $pdf->Cell(0, 10, utf8_decode('No hay cantidad equipo para este equipo'), 1, 1);
            }
        } else {
            $pdf->Cell(0, 10, utf8_decode('Equipo inexistente'), 1, 1);
        }
    }
} else {
    $pdf->Cell(0, 10, utf8_decode('No hay equipos para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?>