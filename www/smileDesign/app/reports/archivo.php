<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['id'])) {
    require('../helpers/report.php');
    require('../models/expedientes.php');
    
    // Se instancia el módelo Categorias para procesar los datos.
    $expedientes = new Productos;
    session_start();
    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($expedientes->setId($_GET['id'])) {
        // Se verifica si la categoría del parametro existe, de lo contrario se direcciona a la página web de origen.
        if ($rowExpediente = $expedientes->readArchivosall()) {
            // Se instancia la clase para crear el reporte.
            $pdf = new Report;
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReports('Archivo');
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataArchivos = $expedientes->readArchivos()) {                
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(225);
                // Se establece la fuente para los encabezados.                
                $pdf->Cell(176, 10, utf8_decode('Nombre Paciente:  '.$rowExpediente['nombrepaciente'].' '.$rowExpediente['apellidopaciente']), 0, 1, 'C', 0);
                $pdf->Ln();
                $pdf->Cell(176, 10, utf8_decode('Número de Expediente:  '.$rowExpediente['idexpediente']), 0, 1, 'C', 0);
                $pdf->Ln();    
                $pdf->Cell(176, 10, utf8_decode('Usuario que imprime: '.$_SESSION['aliasusuario']), 0, 0, 'C', 0);
                $pdf->Ln();           
                $pdf->SetFont('Helvetica', 'B', 12);
                // Se imprimen las celdas con los encabezados.                
                // Se establece la fuente para los datos de los productos.
                $pdf->Cell(195, 10, utf8_decode('Notas'), 1, 1, 'C', 1);                              
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataArchivos as $rows) {
                    // Se imprimen las celdas con los datos de los productos.                                            
                    $pdf->MultiCell(195, 10, utf8_decode($rows['notas']), 1, 1);                    
                }
                $pdf->SetFont('Helvetica', 'B', 11);
                $pdf->Cell(195, 10, utf8_decode('Observaciones'), 1, 1, 'C', 1);              
                $pdf->SetFont('Helvetica', '', 11);
                foreach ($dataArchivos as $rows) {
                    // Se imprimen las celdas con los datos de los productos.                    
                    $pdf->MultiCell(195, 10, utf8_decode($rows['observacionesperiodontograma']), 1, 1);
                }                                     
                } else {
                    $pdf->Cell(0, 10, utf8_decode('No hay Datos'), 1, 1);
                }
            // Se envía el documento al navegador y se llama al método Footer()      
            $pdf->Output();
        } else {
            header('location: ../../views/expedientes.php');
        }
    } else {
        header('location: ../../views/expedientes.php');
    }
} else {
    header('location: ../../views/expedientes.php');
}
?>