<?php
require('../helpers/report.php');
require('../models/doctores.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Doctores por estado');

// Se instancia el módelo Clientes para obtener los datos.
$doctores = new Doctores;
// Verificamos si existen registros para mostrar
if ($dataDoctores = $doctores->readAll2()) {
    // Se recorren los registros ($dataClientes) fila por fila ($rowClientes).
    foreach ($dataDoctores as $rowDoctores) {
        $pdf->SetFillColor(0,0,0);
        // Se establece la fuente para el nombre del cliente.
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(255);
        // Se imprime una celda con el nombre del cliente.        
        $pdf->Cell(0,10, utf8_decode(''.$rowDoctores['estadodoctor']), 1, 1, 'C', 1);
        // Se establece el rowClientes para cada usuario.
        if ($doctores->setEstado($rowDoctores['idestadodoctor'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataVar = $doctores->readDoctoresEstado()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(220);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Helvetica', 'B', 11);
                $pdf->SetTextColor(9,9,9);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(60, 10, utf8_decode('Nombre del doctor'), 1, 0, 'C', 1);
                $pdf->Cell(80, 10, utf8_decode('Apellidos del doctor'), 1, 0, 'C', 1);
                $pdf->Cell(46, 10, utf8_decode('Dirección del doctor'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataVar as $rowVar) {
                    // Se imprimen las celdas con los datos de los clientes.
                    $pdf->SetTextColor(9,9,9);
                    $pdf->Cell(60, 10, utf8_decode($rowVar['nombredoctor']), 1, 0);
                    $pdf->Cell(80, 10, utf8_decode($rowVar['apellidodoctor']), 1, 0);
                    $pdf->Cell(46, 10, $rowVar['direcciondoctor'], 1, 1);
                }
            } else {
                $pdf->SetTextColor(9,9,9);
                $pdf->Cell(0, 10, utf8_decode('No hay doctores con este estado'), 1, 1);
            }
        } else {
            $pdf->SetTextColor(9,9,9);
            $pdf->Cell(0, 10, utf8_decode('Doctor incorrecto o inexistente'), 1, 1);        
        }

    }
} else {
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(0, 10, utf8_decode('No hay doctores para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama el metodo Output();
$pdf->Output();

?>