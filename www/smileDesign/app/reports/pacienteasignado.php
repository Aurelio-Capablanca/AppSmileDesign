<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['id'])) {
    require('../helpers/report.php');
    require('../models/doctores.php');
    
    // Se instancia el módelo Categorias para procesar los datos.
    $doctores = new Doctores;
    session_start();
    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($doctores->setId($_GET['id'])) {
        // Se verifica si la categoría del parametro existe, de lo contrario se direcciona a la página web de origen.
        if ($rowDoctor= $doctores->readOne()) {
            // Se instancia la clase para crear el reporte.
            $pdf = new Report;
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Pacientes Asignados');
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataAsig = $doctores->readpacientesasignados()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(225);
                // Se establece la fuente para los encabezados.                
                $pdf->Cell(176, 10, utf8_decode('Nombre Doctor:  '.$rowDoctor['nombredoctor'].' '.$rowDoctor['apellidodoctor']), 0, 0, 'C', 0);
                $pdf->Ln();
                $pdf->Cell(176, 10, utf8_decode('Dirección:  '.$rowDoctor['direcciondoctor']), 0, 0, 'C', 0);
                $pdf->Ln();
                $pdf->Cell(176, 10, utf8_decode('Contacto:  '.$rowDoctor['telefonodoctor'].' /Correo:  '.$rowDoctor['correodoctor']), 0, 0, 'C', 0);
                $pdf->Ln();      
                $pdf->Cell(176, 10, utf8_decode('Usuario que imprime: '.$_SESSION['aliasusuario']), 0, 0, 'C', 0);
                $pdf->Ln();          
                $pdf->SetFont('Helvetica', 'B', 11);
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(56, 10, utf8_decode('Nombre Paciente'), 1, 0, 'C', 1);
                $pdf->Cell(36, 10, utf8_decode('Teléfono'), 1, 0, 'C', 1);
                $pdf->Cell(78, 10, utf8_decode('Correo Paciente'), 1, 1, 'C', 1);
                               
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Helvetica', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataAsig as $rows) {
                    // Se imprimen las celdas con los datos de los productos.                    
                    if(isset($rows['nombrepaciente'])){
                        $pdf->Cell(56, 10, utf8_decode($rows['nombrepaciente']), 1, 0);
                    }
                    if(isset($rows['telefonopaciente'])){
                        $pdf->Cell(36, 10, utf8_decode($rows['telefonopaciente']), 1, 0);
                    }                    
                    if(isset($rows['correopaciente'])){
                        $pdf->Cell(78, 10, utf8_decode($rows['correopaciente']), 1, 1);
                    }
                    // if(isset($rows['idtratamiento'])){
                    //     $pdf->Cell(28, 10, utf8_decode($rows['idtratamiento']), 1, 1);
                    // }                                      
                }
            } else {
                $pdf->Cell(0, 10, utf8_decode('No hay Datos'), 1, 1);
            }
            // Se envía el documento al navegador y se llama al método Footer()      
            $pdf->Output();
        } else {
            header('location: ../../views/private/doctores.php');
        }
    } else {
        header('location: ../../views/private/doctores.php');
    }
} else {
    header('location: ../../views/private/doctores.php');
}
?>