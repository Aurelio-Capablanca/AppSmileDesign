<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['id'])) {
    require('../helpers/report.php');
    require('../models/pagos.php');
    
    // Se instancia el módelo Categorias para procesar los datos.
    $pagos = new Pagos;
    session_start();
    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($pagos->setCodigo($_GET['id'])) {
        // Se verifica si la categoría del parametro existe, de lo contrario se direcciona a la página web de origen.
        if ($rowPago= $pagos->readOnepaciente()) {
            // Se instancia la clase para crear el reporte.
            $pdf = new Report;
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Factura');
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataCuenta = $pagos->readOnePayment()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(225);
                // Se establece la fuente para los encabezados.                
                $pdf->Cell(176, 10, utf8_decode('Nombre Paciente:  '.$rowPago['nombrepaciente'].' '.$rowPago['apellidopaciente']), 0, 0, 'C', 0);
                $pdf->Ln();
                $pdf->Cell(176, 10, utf8_decode('Dirección:  '.$rowPago['direccionpaciente']), 0, 0, 'C', 0);
                $pdf->Ln();
                $pdf->Cell(176, 10, utf8_decode('Contacto:  '.$rowPago['telefonopaciente'].' /Correo:  '.$rowPago['correopaciente']), 0, 0, 'C', 0);
                $pdf->Ln();
                $pdf->Cell(176, 10, utf8_decode('DUI:  '.$rowPago['duipaciente']), 0, 0, 'C', 0);
                $pdf->Ln();
                $pdf->Cell(176, 10, utf8_decode('Usuario que imprime: '.$_SESSION['aliasusuario']), 0, 0, 'C', 0);
                $pdf->Ln();                
                $pdf->SetFont('Helvetica', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(26, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
                $pdf->Cell(36, 10, utf8_decode('Precio Unitario'), 1, 0, 'C', 1);
                $pdf->Cell(56, 10, utf8_decode('Procedimiento'), 1, 0, 'C', 1);
                $pdf->Cell(78, 10, utf8_decode('Descripcion'), 1, 1, 'C', 1);                
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataCuenta as $rows) {
                    // Se imprimen las celdas con los datos de los productos.                    
                        $pdf->Cell(26, 10, utf8_decode($rows['idconsulta']), 1, 0);
                        $pdf->Cell(36, 10, utf8_decode($rows['costoprocedimiento']), 1, 0);
                        $pdf->Cell(56, 10, utf8_decode($rows['nombreprocedimiento']), 1, 0);
                        $pdf->Cell(78, 10, utf8_decode($rows['descripcionprocedimiento']), 1, 1);
                           
                }
                $pdf->Cell(175, 16, utf8_decode('SubTotal ($):'.$rows['pagototal']), 0, 0, 'R',0);
                $pdf->Ln();  
                $pdf->Cell(175, 16, utf8_decode('Cancela ($):'.$rows['pagoabono']), 0, 0, 'R',0);
            } else {
                $pdf->Cell(0, 10, utf8_decode('No hay Datos'), 1, 1);
            }
            // Se envía el documento al navegador y se llama al método Footer()      
            $pdf->Output();
        } else {
            header('location: ../../views/pagos.php');
        }
    } else {
        header('location: ../../views/pagos.php');
    }
} else {
    header('location: ../../views/pagos.php');
}
?>