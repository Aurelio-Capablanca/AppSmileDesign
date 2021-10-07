<?php
/*
*	Clase para manejar la tabla usuarios de la base de datos. Es clase hija de Validator.
*/
class Registro extends Validator
{

    private $correos = null;
    private $clave = null;


    public function setCorreos($value)
    {
        if ($this->validateEmail($value)) {
            $this->correos = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setClave($value)
    {
        if ($this->validatePassword($value)) {
            $this->clave = $value;
            return true;
        } else {
            return false;
        }
    }

    public function getCorreos()
    {
        return $this->correos;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function searchCorreo($value)
    {
        $sql = "SELECT idusuario, nombreusuario, apellidousuario, correousuario, aliasusuario,
                    UPPER(substring(nombreusuario,1,3)||''||substring(apellidousuario,1,3)||''||substring(md5(random()::text),1,3)) AS claveusuario
                From usuarios
                Where correousuario ILIKE ?";
        $params = array("%$value%");
        return Database::getRow($sql, $params);
    }

    public function restorePassword()
    {
        $fechahoy = date('Y-m-d');
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $sql = 'UPDATE pacientes set clavepaciente = ? Where correopaciente = ?';
        $params = array($hash, $this->correos);
        return Database::executeRow($sql, $params);
    }

}