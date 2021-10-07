<?php 

class Tratamientos extends Validator{

    //-------------------------------
    public function readAll()
    {
        $sql = "SELECT fechainicio, tipotratamiento, nombredoctor ||' '|| apellidodoctor as nombredoctor
        From tratamientos tr
        inner join tipotratamiento tt on tt.idtipotratamiento = tr.idtipotratamiento
        inner join estadotratamiento te on te.idestadotratamiento = tr.idestadotratamiento
        inner join pacienteasignado pa on pa.idpacienteasignado = tr.idpacienteasignado
        inner join pacientes pc on pc.idpaciente = pa.idpaciente
        inner join doctores dr on dr.iddoctor = pa.iddoctor
        Where pc.idpaciente = ?";
        $params = array($_SESSION['idpaciente']);
        return Database::getRows($sql, $params);
    }
    //-------------------------

}