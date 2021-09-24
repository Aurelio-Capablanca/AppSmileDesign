<?php
if (isset($_GET['id'])) {
    if (isset($_GET['numero'])) {
    require('../helpers/report.php');
    require('../models/pagos.php');
    
    // Se instancia el módelo Categorias para procesar los datos.
    $pagos = new Pagos;
    session_start();
    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($pagos->setCodigo($_GET['id'])) {
        if ($pagos->setSaldo($_GET['numero'])) {
        // Se verifica si la categoría del parametro existe, de lo contrario se direcciona a la página web de origen.
        if ($rowPago = $pagos->readOnepacientes()) {
            // Se instancia la clase para crear el reporte.
            $pdf = new Report;
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Factura');
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataCuenta = $pagos->readOnePayments()) {
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
                $pdf->SetFont('Times', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(26, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
                $pdf->Cell(36, 10, utf8_decode('Precio Unitario'), 1, 0, 'C', 1);
                $pdf->Cell(56, 10, utf8_decode('Procedimiento'), 1, 0, 'C', 1);
                $pdf->Cell(78, 10, utf8_decode('Descripcion'), 1, 1, 'C', 1);                
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Times', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataCuenta as $rows) {
                    // Se imprimen las celdas con los datos de los productos.                    
                        $pdf->Cell(26, 10, utf8_decode($rows['idconsulta']), 1, 0);
                        $pdf->Cell(36, 10, utf8_decode($rows['costoprocedimiento']), 1, 0);
                        $pdf->Cell(56, 10, utf8_decode($rows['nombreprocedimiento']), 1, 0);
                        $pdf->Cell(78, 10, utf8_decode($rows['descripcionprocedimiento']), 1, 1);
                       //$pdf->Cell(28, 10, utf8_decode('Total:'.$rows['pagoabonoh']), 0, 0);    
                }
            } else {
                $pdf->Cell(0, 10, utf8_decode('No hay Datos'), 1, 1);
            }
            // Se envía el documento al navegador y se llama al método Footer()      
            $pdf->Output();
            } else {
                header('location: ../../views/private/pagos.php');
            }        
        } else {
            header('location: ../../views/private/pagos.php');
        }
    } else {
        header('location: ../../views/private/pagos.php');
    }
} else {
    header('location: ../../views/private/pagos.php');
}
} else {
    header('location: ../../views/private/pagos.php');
}
?>