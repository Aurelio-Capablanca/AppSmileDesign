<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/registros.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.    
    // Se instancia la clase correspondiente.
    $usuario = new Registro;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'error' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.    
    // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
    switch ($_GET['action']) {
        // case 'readAll':
        //     if ($usuario->readAll()) {
        //         $result['status'] = 1;
        //         $result['message'] = 'Existe al menos un usuario registrado';
        //     } else {
        //         if (Database::getException()) {
        //             $result['error'] = 1;
        //             $result['exception'] = Database::getException();
        //         } else {
        //             $result['exception'] = 'No existen usuarios registrados';
        //         }
        //     }
        //     break;
        case 'readOneMails':
            $_POST = $usuario->validateForm($_POST);
            if ($_POST['correo_enviar'] != '') {
                if ($result['dataset'] = $usuario->searchCorreo($_POST['correo_enviar'])) {
                    $result['status'] = 1;
                    $rows = count($result['dataset']);
                    $result['message'] = 'Correo Encontrado en el registro';
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay coincidencias';
                    }
                }
            } else {
                $result['exception'] = 'Ingrese un valor para buscar';
            }
            break;
        case 'restorePassword':
            $_POST = $usuario->validateForm($_POST);
            if ($usuario->setCorreos($_POST['usuario'])) {
                if ($_POST['clave'] == $_POST['confirmacion']) {
                    if ($usuario->setClave($_POST['clave'])) {
                        if ($usuario->restorePassword()) {
                            $result['status'] = 1;
                            $result['message'] = 'Clare restaurada correctamente';
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = $usuario->getPasswordError();
                    }
                } else {
                    $result['exception'] = 'Claves distintas';
                }
            } else {
                $result['exception'] = 'Claves distintas';
            }
            break;
        default:
            $result['exception'] = 'Acción no disponible fuera de la sesión';
    }

    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
