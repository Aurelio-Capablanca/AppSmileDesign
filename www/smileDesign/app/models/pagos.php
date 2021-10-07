
<?php 

class Pago extends Validator{

    private $id = null;
    private $saldo = null;
    private $tipo = null;
    private $estado = null;
    private $codigo = null;

    
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setSaldo($value)
    {
        if ($this->validateMoney($value)) {
            $this->saldo = $value;
            return true;
        } else {
            return false;
        }
    }


    public function setEstado($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTipo($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->tipo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCodigo($value)
    {
        if ($this->validateString($value, 1, 200)) {
            $this->codigo = $value;
            return true;
        } else {
            return false;
        }
    }

    // ----------------------------------------------

    public function getId()
    {
        return $this->id;
    }

    public function getSaldo()
    {
        return $this->saldo;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }
    // ------------------------------------------

    public function readAll3()
    {
        $sql = 'SELECT idpago ,nombrepaciente, apellidopaciente , fechaconsulta, pagodebe, pagoabono, pagototal, pagosaldo, tipopago ,estadopago, codigotratamiento
                From Pagos  ps
                Inner join Tipopago tg on tg.idtipopago=ps.idtipopago
                Inner join EstadoPago eg on eg.idestadopago = ps.idestadopago
                Inner join Consultas cl on cl.idconsulta = ps.idconsulta
				inner join CantidadConsultas cc on cc.idconsulta = cl.idconsulta
				inner join Tratamientos tr on tr.idtratamiento = cc.idtratamiento
                Inner join Pacienteasignado pa on pa.idpacienteasignado = tr.idpacienteasignado
                Inner join pacientes tp on tp.idpaciente = pa.idpaciente
                Order By idpago ASC';
        $params = null;
        return Database::getRows($sql, $params);
    }


    public function readAll()
    {
        $sql = 'SELECT hp.nombrepaciente, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento, codigotratamientoh
        from historialpagos hp
		Inner join pagos pg on pg.idconsulta = hp.tratamiento
		Inner join consultas cl on cl.idconsulta = pg.idconsulta
		inner join cantidadconsultas cc on cc.idconsulta = cl.idconsulta
		inner join tratamientos tr on tr.idtratamiento = cc.idtratamiento
		inner join pacienteasignado pa on pa.idpacienteasignado = tr.idpacienteasignado
		inner join pacientes pc on pc.idpaciente = pa.idpaciente
        Where pagoabonoh >0  and pagosaldoh >=0 and pc.idpaciente = ?             
        group by  hp.nombrepaciente, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento, codigotratamientoh
        having count(pagoabonoh)>=1 and count(pagodebeh)>=1';
        $params = array($_SESSION['idpaciente']);
        return Database::getRows($sql, $params);
    }

    // public function readAllTIPO()
    // {
    //     $sql = 'SELECT idtipopago, tipopago
    //             FROM tipopago';
    //     $params = null;
    //     return Database::getRows($sql, $params);
    // }    

    // public function readAllESTADO()
    // {
    //     $sql = 'SELECT idestadopago, estadopago
    //             FROM estadopago';
    //     $params = null;
    //     return Database::getRows($sql, $params);
    // }

    // public function readOnes()
    // {
    //     $sql = 'SELECT idpago ,nombrepaciente, apellidopaciente , fechaconsulta, pagodebe, pagoabono, pagototal, pagosaldo, tipopago ,estadopago, codigotratamiento
    //             From Pagos  ps
    //             Inner join Tipopago tg on tg.idtipopago=ps.idtipopago
    //             Inner join EstadoPago eg on eg.idestadopago = ps.idestadopago
    //             Inner join Consultas cl on cl.idconsulta = ps.idconsulta
	// 			inner join CantidadConsultas cc on cc.idconsulta = cl.idconsulta
	// 			inner join Tratamientos tr on tr.idtratamiento = cc.idtratamiento
    //             Inner join Pacienteasignado pa on pa.idpacienteasignado = tr.idpacienteasignado
    //             Inner join pacientes tp on tp.idpaciente = pa.idpaciente
    //             WHERE idpago = ?';
    //     $params = array($this->id);
    //     return Database::getRow($sql, $params);
    // }   

    // public function searchRows($value)
    // {
    //     $sql = 'SELECT idpago ,nombrepaciente , apellidopaciente , fechaconsulta, pagodebe, pagoabono, pagototal, pagosaldo, tipopago ,estadopago, codigotratamiento
    //             From Pagos  ps
    //             Inner join Tipopago tg on tg.idtipopago=ps.idtipopago
    //             Inner join EstadoPago eg on eg.idestadopago = ps.idestadopago
    //             Inner join Consultas cl on cl.idconsulta = ps.idconsulta
	// 			inner join CantidadConsultas cc on cc.idconsulta = cl.idconsulta
	// 			inner join Tratamientos tr on tr.idtratamiento = cc.idtratamiento
    //             Inner join Pacienteasignado pa on pa.idpacienteasignado = tr.idpacienteasignado
    //             Inner join pacientes tp on tp.idpaciente = pa.idpaciente
    //             WHERE nombrepaciente ILIKE ? or apellidoPaciente ILIKE ? 
    //             Order By idpago ASC';
    //     $params = array("%$value%","%$value%");
    //     return Database::getRows($sql, $params);
    // }

    // public function createCuenta()
    // {
    //     $sql = 'SELECT * FROM Calculo_Cuenta (?)';
    //     $params = array($this->id);
    //     return Database::executeRow($sql, $params);
    // }

    // public function createSaldo()
    // {
    //     $sql = 'SELECT * FROM Calculo_Saldo (?,?)';
    //     $params = array($this->id, $this->saldo);
    //     return Database::executeRow($sql, $params);
    // }

    // public function searchOneCount($value)
    // {
    //     $sql = 'SELECT  nombrepaciente, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento, codigotratamientoh
    //     from historialpagos 
    //     Where codigotratamientoh ILIKE ?
    //     and pagoabonoh >0  and pagosaldoh >=0                 
    //     group by  nombrepaciente, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento, codigotratamientoh
    //     having count(pagodebeh)>=1 and count(pagoabonoh)>=1';
    //     $params = array("%$value%");
    //     return Database::getRows($sql, $params);
    // }
    
   

    // public function readOneCount()
    // {
    //     $sql = 'SELECT nombrepaciente, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento, codigotratamientoh, idhistorial
    //     from historialpagos 
    //     Where tratamiento = ?
    //     and pagoabonoh >0  and pagosaldoh >=0                 
    //     group by  nombrepaciente, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento, codigotratamientoh, idhistorial
    //     having count(pagoabonoh)>=1 and count(pagodebeh)>=1 ';
    //     $params = array($this->id);
    //     return Database::getRow($sql, $params);
    // }

    // public function updateRow()
    // {
    //     $sql = 'UPDATE pagos SET idtipopago=? WHERE idpago=?';
    //     $params = array($this->tipo, $this->id);
    //     return Database::executeRow($sql, $params);
    // }

    // public function deleteRow()
    // {
    //     $sql = 'UPDATE pagos SET idestadopago=? WHERE idpago=?';
    //     $params = array($this->estado, $this->id);
    //     return Database::executeRow($sql, $params);
    // }

    // public function UpdateCuenta()
    // {
    //     $sql = 'SELECT * From Actualizar_Pagos(?)';
    //     $params = array($this->id);
    //     return Database::executeRow($sql, $params);
    // }

    // public function readOnePayment()
    // {
    //     $sql = 'SELECT count(cc.idcantidadconsulta) as idconsulta, costoprocedimiento, descripcionprocedimiento, nombreprocedimiento ,
    //                     nombrepaciente, tr.idtratamiento, codigotratamiento, pagoabono, pagototal, count(cc.idcantidadconsulta)*costoprocedimiento as pagosaldo
    //             from CantidadConsultas cc
    //             inner join Tratamientos tr on tr.idTratamiento=cc.idTratamiento
	// 			inner join Consultas cl on cl.idConsulta=cc.idConsulta
    //             inner join pagos pg on pg.idconsulta=cl.idconsulta							                
    //             inner join ConsultaProcedimiento co on co.idConsulta=cl.idConsulta
    //             inner join Procedimientos pr on pr.idProcedimiento=co.idProcedimiento
    //             inner join pacienteasignado ap on ap.idpacienteasignado = tr.idpacienteasignado
    //             inner join pacientes pc on pc.idpaciente = ap.idpaciente
    //             Where pg.idpago = ?
    //             group by  costoprocedimiento, descripcionprocedimiento, nombreprocedimiento ,
    //                     nombrepaciente, tr.idtratamiento, codigotratamiento, pagoabono, pagototal';
    //     $params = array($this->codigo);
    //     return Database::getRows($sql, $params);
    // }      


    // public function readOnepaciente()
    // {
    //     $sql = 'SELECT pc.nombrepaciente as nombrepaciente, apellidopaciente, telefonopaciente, duipaciente, direccionpaciente, correopaciente
    //     from pacientes pc
    //     inner join pacienteasignado pp on pp.idpaciente = pc.idpaciente
    //     inner join tratamientos tr on tr.idpacienteasignado = pp.idpacienteasignado
    //    	inner join cantidadconsultas cc on cc.idtratamiento = tr.idtratamiento
	// 	inner join consultas cl on cl.idconsulta = cc.idconsulta
	// 	inner join pagos pg on pg.idconsulta = cl.idconsulta
    //     Where pg.idpago = ?';
    //     $params = array($this->codigo);
    //     return Database::getRow($sql, $params);
    // }

    // public function readOneAbonos()
    // {
    //     $sql = 'SELECT nombrepaciente, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento, codigotratamientoh
    //     from historialpagos
    //     where  pagoabonoh >0  and pagosaldoh >=0 and codigotratamientoh = ?
    //     group by nombrepaciente, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento , codigotratamientoh
    //     having count(pagoabonoh)>=1 and count(pagodebeh)>=1';
    //     $params = array($this->codigo);
    //     return Database::getRows($sql, $params);
    // } 
    
    
    // public function readOneAbonosNombres()
    // {
    //     $sql = 'SELECT nombrepaciente, fecharegistro, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento, codigotratamientoh
    //     from historialpagos
    //     where pagoabonoh>=1 And codigotratamientoh = ?
    //     group by nombrepaciente, fecharegistro, pagodebeh, pagoabonoh, pagototalh, pagosaldoh, tratamiento , codigotratamientoh
    //     having count(pagoabonoh)>=1';
    //     $params = array($this->codigo);
    //     return Database::getRow($sql, $params);
    // } 


    // public function suspenderPago()
    // {
    //     $sql = 'UPDATE pagos Set idestadopago=3 Where idpago = ?';
    //     $params = array($this->id);
    //     return Database::executeRow($sql, $params);
    // }


    // public function readTipoPagos()
    // {
    //     $sql = "SELECT Round(count(idpago) * 100.0 / (select count(idpago) from pagos)::numeric,2) ||' '|| '%' as pagodebe, tipopago
    //     from pagos 
    //     inner join tipopago Using(idtipopago)
    //     where idtipopago = ?
    //     group by tipopago";
    //     $params = array($this->id);
    //     return Database::getRows($sql, $params);
    // }

    


}