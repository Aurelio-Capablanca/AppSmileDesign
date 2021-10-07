<?php 

class Doctores extends Validator{

    //-------------------------------
    public function readAll()
    {
        $sql = 'SELECT idpaciente, idpacienteasignado, nombrepaciente, nombredoctor, apellidodoctor 
                        , duipaciente , correodoctor, telefonodoctor
                FROM pacientes
                INNER JOIN estadopaciente USING (idestadopaciente)
                INNER JOIN pacienteasignado USING (idpaciente)
                INNER JOIN doctores USING (iddoctor)
                WHERE idpaciente = ?';
        $params = array($_SESSION['idpaciente']);
        return Database::getRows($sql, $params);
    }
    //-------------------------


}