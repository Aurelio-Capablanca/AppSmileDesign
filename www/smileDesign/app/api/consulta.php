<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/consultas.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente. 
    $consulta = new Consultas;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API. 
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idpaciente'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión. El socialismo no funciona 
        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $consulta->readAll()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay Consultas registradas';
                    }
                }
                break;
            case 'readAllCAUSA':
                if ($result['dataset'] = $consulta->readAllCAUSA()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay Causas registradas';
                    }
                }
                break;
            case 'readAllPROCEDIMIENTO':
                if ($result['dataset'] = $consulta->readAllPROCEDIMIENTO()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay Procedimientos registradas';
                    }
                }
                break;
            case 'search':
                $_POST = $consulta->validateForm($_POST);
                if ($_POST['search'] != '') {
                    if ($result['dataset'] = $consulta->searchRows($_POST['search'])) {
                        $result['status'] = 1;
                        $rows = count($result['dataset']);
                        if ($rows > 1) {
                            $result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
                        } else {
                            $result['message'] = 'Solo existe una coincidencia';
                        }
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
            case 'readOne':
                if ($consulta->setId($_POST['id_consulta'])) {
                    if ($result['dataset'] = $consulta->readOne()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'Consulta inexistente';
                        }
                    }
                } else {
                    $result['exception'] = 'identificador incorrecto';
                }
                break;
            case 'create':
                $_POST = $consulta->validateForm($_POST);
                if ($consulta->setFecha($_POST['fecha_consulta'])) {
                    if ($consulta->setHora($_POST['hora_consulta'])) {
                        if (isset($_POST['causa_consulta'])) {
                            if ($consulta->setCausa($_POST['causa_consulta'])) {
                                if ($consulta->createRow()) {
                                    $result['status'] = 1;
                                } else {
                                    $result['exception'] = Database::getException();;
                                }
                            } else {
                                $result['exception'] = 'Causa Incorrecta';
                            }
                        } else {
                            $result['exception'] = 'Causa  Incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Hora Incorrecta';
                    }
                } else {
                    $result['exception'] = 'Fecha Incorrecta';
                }
                break;
            case 'update':
                $_POST = $consulta->validateForm($_POST);
                if ($consulta->setId($_POST['id_consulta'])) {
                    if ($data = $consulta->readOne()) {
                        if ($consulta->setFecha($_POST['fecha_consulta'])) {
                            if ($consulta->setHora($_POST['hora_consulta'])) {
                                if (isset($_POST['causa_consulta'])) {
                                    if ($consulta->setCausa($_POST['causa_consulta'])) {
                                        if ($consulta->updateRow()) {
                                            $result['status'] = 1;
                                            $result['message'] = 'Datos de la Consulta Actualizados correctamente';
                                        } else {
                                            $result['exception'] = Database::getException();
                                        }
                                    } else {
                                        $result['exception'] = 'Causa Incorrecta';
                                    }
                                } else {
                                    $result['exception'] = 'Causa Incorrecta';
                                }
                            } else {
                                $result['exception'] = 'Hora Incorrecta';
                            }
                        } else {
                            $result['exception'] = 'fecha Incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Dato no valido';
                    }
                } else {
                    $result['exception'] = 'id no reconocido';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
