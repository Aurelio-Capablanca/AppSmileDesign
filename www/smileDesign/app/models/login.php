<?php
/*
*	Clase para manejar la tabla usuarios de la base de datos. Es clase hija de Validator.
*/
class Usuarios extends Validator
{

    private $id = null;
    private $usuario = null;
    private $clave = null;
    private $correos = null;

    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setUsuario($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->usuario = $value;
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

    public function setCorreos($value)
    {
        if ($this->validateEmail($value)) {
            $this->correos = $value;
            return true;
        } else {
            return false;
        }
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function getCorreos()
    {
        return $this->correos;
    }

    public function checkUser($usuario)
    {
        $sql = 'SELECT idpaciente, correopaciente From pacientes Where correopaciente = ?';
        $params = array($usuario);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['idpaciente'];                                   
            $this->usuario = $usuario;
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT clavepaciente FROM pacientes WHERE idpaciente = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        if (password_verify($password, $data['clavepaciente'])) {
            return true;
        } else {
            return false;
        }
    }


}
