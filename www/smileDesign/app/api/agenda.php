<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/agenda.php');

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
            case 'searchagenda':
                $_POST = $consulta->validateForm($_POST);
                if ($_POST['search'] != '') {
                    if ($result['dataset'] = $consulta->SearchAgenda($_POST['search'])) {
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
                if ($consulta->setCosto($_POST['precio_consulta'])) {
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
                } else {
                    $result['exception'] = 'Precio no valido';
                }
                break;
            case 'update':
                $_POST = $consulta->validateForm($_POST);
                if ($consulta->setId($_POST['id_consulta'])) {
                    if ($data = $consulta->readOne()) {
                        if ($consulta->setNotas($_POST['notas_consulta'])) {
                            if ($consulta->setCosto($_POST['precio_consulta'])) {
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
                                $result['exception'] = 'Precio no valido';
                            }
                        } else {
                            $result['exception'] = 'Notas no reconocidas';
                        }
                    } else {
                        $result['exception'] = 'Dato no valido';
                    }
                } else {
                    $result['exception'] = 'id no reconocido';
                }
                break;
            case 'searchOneProcedure':
                $_POST = $consulta->validateForm($_POST);
                if ($_POST['notas_consultaP'] != '') {
                    if ($result['dataset'] = $consulta->searchOneProcedure($_POST['notas_consultaP'])) {
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
            case 'readOneProcedure':
                if ($consulta->setId($_POST['id_consultaP'])) {
                    // if ($result['dataset'] = $consulta->readOneProcedure()) {
                    //     $result['status'] = 1;                                
                    // } else {
                    //     if (Database::getException()) {
                    //         $result['exception'] = Database::getException();
                    //     } else {
                    //         $result['exception'] = 'Procedimiento inexistente';
                    //     }
                    // }      
                    $result['dataset'] = $consulta->readOneProcedure();
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Procedimiento incorrecto';
                }
                break;
            case 'readOneProcedure1':
                if ($consulta->setId($_POST['id_consultasP'])) {
                    if ($result['dataset'] = $consulta->readOneProcedure1()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'Procedimiento inexistente';
                        }
                    }
                } else {
                    $result['exception'] = 'Procedimiento incorrecto';
                }
                break;
            case 'readOneAsignado2':
                if ($consulta->setId($_POST['id_consultasPr'])) {
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
                    $result['exception'] = 'Consulta incorrecto';
                }
                break;
            case 'readAllAgenda':
                if ($result['dataset'] = $consulta->readAllAgenda()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay Consultas registradas';
                    }
                }
                break;
            case 'searchOneConsultasCantidad':
                $_POST = $consulta->validateForm($_POST);
                if ($_POST['codigos'] != '') {
                    if ($result['dataset'] = $consulta->searchOneConsultasC($_POST['codigos'])) {
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
            case 'readOneConsultasCantidad':
                if ($consulta->setId($_POST['id_tratamientos'])) {
                    // if ($result['dataset'] = $consulta->readOneConsultasC()) {
                    //     $result['status'] = 1;                                
                    // } else {
                    //     if (Database::getException()) {
                    //         $result['exception'] = Database::getException();
                    //     } else {
                    //         $result['exception'] = 'Consulta inexistente';
                    //     }
                    // } 
                    $result['dataset'] = $consulta->readOneConsultasC();
                    $result['status'] = 1;
                } else {
                    $result['exception'] = 'Consulta incorrecto';
                }
                break;
                case 'readAllProcedimientos':
                    if ($result['dataset'] = $consulta->readAllProcedimientos()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'No hay Consultas registradas';
                        }
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
