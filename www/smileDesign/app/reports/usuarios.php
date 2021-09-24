<?php
require('../helpers/report.php');
require('../models/usuarios.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte de usuarios por tipo');

// Se instancia el módelo Categorías para obtener los datos.
$usuarios = new Usuarios;
session_start();
// Se verifica si existen registros (categorías) para mostrar, de lo contrario se imprime un mensaje.
if ($dataCategorias = $usuarios->readTipo()) {
    // Se recorren los registros ($dataCategorias) fila por fila ($rowCategoria).
    $pdf->Cell(176, 10, utf8_decode('Usuario que imprime: '.$_SESSION['aliasusuario']), 0, 0, 'C', 0);
    $pdf->Ln();
    $pdf->SetFont('Helvetica', 'B', 11);
    foreach ($dataCategorias as $rowCategoria) {
        $pdf->SetFillColor(0,0,0);
        // Se establece la fuente para el nombre de la categoría.
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetTextColor(255);
        // Se imprime una celda con el nombre de la categoría.
        $pdf->Cell(193, 10, utf8_decode(''.$rowCategoria['tipousuario']), 1, 1, 'C', 1);
        // Se establece la categoría para obtener sus productos, de lo contrario se imprime un mensaje de error.
        if ($usuarios->setTipo($rowCategoria['idtipousuario'])) {
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $usuarios->readUsuariosTipo()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(220);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Helvetica', 'B', 11);
                $pdf->SetTextColor(9,9,9);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(45, 10, utf8_decode('Nombre de usuario'), 1, 0, 'C', 1);
                $pdf->Cell(83, 10, utf8_decode('Correo'), 1, 0, 'C', 1);
                $pdf->Cell(65, 10, utf8_decode('Nombres'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataProductos as $rowProducto) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->SetTextColor(9,9,9);
                    $pdf->Cell(45, 10, utf8_decode($rowProducto['aliasusuario']), 1, 0,'C');
                    $pdf->Cell(83, 10, utf8_decode($rowProducto['correousuario']), 1, 0,'C');
                    $pdf->Cell(65, 10, utf8_decode($rowProducto['nombres']), 1, 1,'C');
                }
            } else {
                $pdf->SetTextColor(9,9,9);
                $pdf->Cell(0, 10, utf8_decode('No hay usuarios en este tipo'), 1, 1);
            }
        } else {
            $pdf->SetTextColor(9,9,9);
            $pdf->Cell(0, 10, utf8_decode('Categoría incorrecta o inexistente'), 1, 1);
        }
    }
} else {
    $pdf->SetTextColor(9,9,9);
    $pdf->Cell(0, 10, utf8_decode('No hay categorías para mostrar'), 1, 1);
}

// Se envía el documento al navegador y se llama al método Footer()
$pdf->Output();
?> 