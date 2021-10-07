<?php 

class Consultas extends Validator{

private $id = null;
private $notasconsulta = null;
private $costoconsulta = null;
private $fechaconsulta = null;
private $horaconsulta = null;
private $causa = null;
private $idprocedimiento = null;


    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNotas($value)
    {
        if ($this->validateString($value, 1, 550)) {
            $this->notasconsulta = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCosto($value)
    {
        if ($this->validateMoney($value)) {
            $this->costoconsulta = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setFecha($value)
    {
        if ($this->validateDate($value)) {
            $this->fechaconsulta = $value;
            return true;
        } else {
            return false;
        }
    }
    
    public function setHora($value)
    {
        if ($this->validateString($value, 1, 550)) {
            $this->horaconsulta = $value;
            return true;
        } else {
            return false;
        }       
    }

    public function setCausa($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->causa = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setProcedimiento($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->idprocedimiento = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNotas()
    {
        return $this->notasconsulta;
    }

    public function getCosto()
    {
        return $this->costoconsulta;
    }

    public function getFecha()
    {
        return $this->fechaconsulta;
    }

    public function getHora()
    {
        return $this->horaconsulta;
    }

    public function getCausa()
    {
        return $this->causa;
    }

    public function getProcedimiento()
    {
        return $this->idprocedimiento;
    }

    public function createRow()
    {
        $sql = "INSERT INTO consultas(notasconsulta, costoconsulta, fechaconsulta, horaconsulta, idcausaconsulta)
                VALUES ('notas', '0.00', ?, ?, ?)";
        $params = array($this->fechaconsulta,$this->horaconsulta,$this->causa);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = "SELECT idconsulta,nombrepaciente ||' '|| apellidopaciente as Nombrepaciente, 
                        fechaconsulta, horaconsulta ,causa, idcausaconsulta, notasconsulta                            
                From consultas 
                inner join causaconsulta using(idcausaconsulta)
                inner join cantidadconsultas using(idconsulta)
                inner join tratamientos using(idtratamiento)
                inner join pacienteasignado using(idpacienteasignado)
                inner join pacientes using(idpaciente)
                Where idpaciente = ?
                order by idconsulta DESC"; 
        $params = array($_SESSION['idpaciente']);
        return Database::getRows($sql, $params);
    }

    public function searchRows($value)
    {
        $sql = "SELECT idconsulta,nombrepaciente ||' '|| apellidopaciente as Nombrepaciente, 
                            fechaconsulta, horaconsulta ,causa, idcausaconsulta , notasconsulta,extract(day from fechaconsulta) as fechaconsultas
                From consultas 
                inner join causaconsulta using(idcausaconsulta)
                inner join cantidadconsultas using(idconsulta)
                inner join tratamientos using(idtratamiento)
                inner join pacienteasignado using(idpacienteasignado)
                inner join pacientes using(idpaciente) 
                WHERE nombrepaciente ILIKE ? OR  apellidopaciente ILIKE ?
                order by idconsulta DESC";
        $params = array("%$value%","%$value%");
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT idconsulta, notasconsulta, costoconsulta, fechaconsulta ,horaconsulta, idcausaconsulta, causa
                FROM consultas 
                INNER JOIN causaconsulta USING(idcausaconsulta)
                WHERE idconsulta = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {        
        $sql = 'UPDATE consultas 
                SET fechaconsulta= ? ,horaconsulta=? ,  idcausaconsulta = ? 
                WHERE  idconsulta = ?';
        $params = array($this->fechaconsulta, $this->horaconsulta ,$this->causa, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function readAllPROCEDIMIENTO()
    {
        $sql = 'SELECT idprocedimiento, nombreprocedimiento
                FROM procedimientos'; 
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readAllCAUSA()
    {
        $sql = 'SELECT idcausaconsulta, causa
                FROM causaconsulta'; 
        $params = null;
        return Database::getRows($sql, $params);
    }
}