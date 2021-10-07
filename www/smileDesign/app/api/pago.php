
<?php
require_once('../helpers/database.php');
require_once('../helpers/validator.php');
require_once('../models/pagos.php'); 

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
	 // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
	 // Se instancia la clase correspondiente. 
	 $pagos = new Pago;
	 // Se declara e inicializa un arreglo para guardar el resultado que retorna la API. 
	 $result = array('status' => 0, 'message' => null, 'exception' => null);
	 // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
     if (isset($_SESSION['idpaciente'])) {
		  // Se compara la acción a realizar cuando un administrador ha iniciado sesión. El socialismo no funciona 
		  switch ($_GET['action']) {
				case 'readAll':
					 if ($result['dataset'] = $pagos->readAll()) {
						  $result['status'] = 1;
					 } else {
						  if (Database::getException()) {
								$result['exception'] = Database::getException();
						  } else {
								$result['exception'] = 'No hay Pagos registrados';
						  }
					 }
	 				break;					
	// 				case 'readAllTIPO':
	// 					if ($result['dataset'] = $pagos->readAllTIPO()) {
	// 						 $result['status'] = 1;
	// 					} else {
	// 						 if (Database::getException()) {
	// 							   $result['exception'] = Database::getException();
	// 						 } else {
	// 							   $result['exception'] = 'No hay Tipos registrados';
	// 						 }
	// 					}
	// 				break;
	// 				case 'readAllESTADO':
	// 					if ($result['dataset'] = $pagos->readAllESTADO()) {
	// 						 $result['status'] = 1;
	// 					} else {
	// 						 if (Database::getException()) {
	// 							   $result['exception'] = Database::getException();
	// 						 } else {
	// 							   $result['exception'] = 'No hay Estados registrados';
	// 						 }
	// 					}
	// 				break;
	// 				case 'readOne':
	// 					if ($pagos->setId($_POST['id_pagos'])) {
	// 						 if ($result['dataset'] = $pagos->readOnes()) {
	// 							   $result['status'] = 1;
	// 						 } else {
	// 							   if (Database::getException()) {
	// 									$result['exception'] = Database::getException();
	// 							   } else {
	// 									$result['exception'] = 'Pagos inexistente';
	// 							   }
	// 						 }
	// 					} else {
	// 						 $result['exception'] = 'Pago incorrecto';
	// 					}
	// 				break;
	// 				case 'readOneT':
	// 					if ($pagos->setId($_POST['id_pagoT'])) {
	// 						 if ($result['dataset'] = $pagos->readOnes()) {
	// 							   $result['status'] = 1;
	// 						 } else {
	// 							   if (Database::getException()) {
	// 									$result['exception'] = Database::getException();
	// 							   } else {
	// 									$result['exception'] = 'Pagos inexistente';
	// 							   }
	// 						 }
	// 					} else {
	// 						 $result['exception'] = 'Pago incorrecto';
	// 					}
	// 				break;
	// 				case 'readOneE':
	// 					if ($pagos->setId($_POST['id_pagoE'])) {
	// 						 if ($result['dataset'] = $pagos->readOnes()) {
	// 							   $result['status'] = 1;
	// 						 } else {
	// 							   if (Database::getException()) {
	// 									$result['exception'] = Database::getException();
	// 							   } else {
	// 									$result['exception'] = 'Pagos inexistente';
	// 							   }
	// 						 }
	// 					} else {
	// 						 $result['exception'] = 'Pago incorrecto';
	// 					}
	// 				break;					
	// 				case 'readOneSaldo':
	// 					if ($pagos->setId($_POST['id_pago'])) {
	// 						 if ($result['dataset'] = $pagos->readOnes()) {
	// 							   $result['status'] = 1;
	// 						 } else {
	// 							   if (Database::getException()) {
	// 									$result['exception'] = Database::getException();
	// 							   } else {
	// 									$result['exception'] = 'Pagos inexistente';
	// 							   }
	// 						 }
	// 					} else {
	// 						 $result['exception'] = 'Pago incorrecto';
	// 					}
	// 				break;					
    //                 case 'search':
    //                     $_POST = $pagos->validateForm($_POST);
    //                     if ($_POST['search'] != '') {
    //                          if ($result['dataset'] = $pagos->searchRows($_POST['search'])) {
    //                                $result['status'] = 1;
    //                                $rows = count($result['dataset']);
    //                                if ($rows > 1) {
    //                                     $result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
    //                                } else {
    //                                     $result['message'] = 'Solo existe una coincidencia';
    //                                }
    //                          } else {
    //                                if (Database::getException()) {
    //                                     $result['exception'] = Database::getException();
    //                                } else {
    //                                     $result['exception'] = 'No hay coincidencias';
    //                                }
    //                          }
    //                     } else {
    //                          $result['exception'] = 'Ingrese un valor para buscar';
    //                     }
    //                 break;
	// 				case 'createCuenta':
	// 					if ($pagos->setId($_POST['id_pagos'])) {
	// 						if ($pagos->createCuenta()) {
	// 							$result['status'] = 1;
	// 							$result['message'] = 'Datos de la Cuenta Actualizados correctamente';                                                        
	// 							} else {
	// 							$result['exception'] = Database::getException();                                                        
	// 							} 
	// 					} else {
	// 						$result['exception'] = 'Precio no valido';
	// 					   }		
	// 				break;
	// 				case 'createSaldo':
	// 					if ($pagos->setId($_POST['id_pago'])) {
	// 						if ($pagos->setSaldo($_POST['pago_abono'])) {
	// 							if ($pagos->createSaldo()) {
	// 								$result['status'] = 1;
	// 								$result['message'] = 'Datos de la Cuenta Actualizados correctamente';                                                        
	// 								} else {
	// 								$result['exception'] = Database::getException();                                                        
	// 							} 	
	// 					} else {
	// 					$result['exception'] = 'Precio no valido';
	// 					}
	// 				 } else {
	// 				   $result['exception'] = 'Precio no valido';
	// 				 }		
	// 				break;
	// 				case 'UpdateCuenta':
	// 					if ($pagos->setId($_POST['id_pagoA'])) {
	// 						if ($pagos->UpdateCuenta()) {
	// 							$result['status'] = 1;
	// 							$result['message'] = 'Datos de la Cuenta Actualizados correctamente';                                                        
	// 							} else {
	// 							$result['exception'] = Database::getException();                                                        
	// 						}
	// 					} else {
	// 						$result['exception'] = 'Cuenta no valido';
	// 					}			 
	// 				break;						
	// 				case 'update':
	// 							$_POST = $pagos->validateForm($_POST); 
	// 							if ($pagos->setId($_POST['id_pagoT'])) {
	// 							 if ($data = $pagos->readOnes()) {
	// 							   if (isset($_POST['tipo'])) {
	// 								   if ($pagos->setTipo($_POST['tipo'])) {										
	// 									   if ($pagos->updateRow()) {
	// 										   $result['status'] = 1;
	// 										   $result['message'] = 'Pago Actualizado Correctamente';                                                        
	// 										 } else {
	// 											 $result['exception'] = Database::getException();                                                        
	// 										 }
	// 							   } else {
	// 								   $result['exception'] = 'Tipo inexistente';
	// 							   }
	// 					   } else {
	// 						 $result['exception'] = 'Inseleccionado inexistente';
	// 					   }	
	// 				   } else {
	// 					 $result['exception'] = 'Read One inexistente';
	// 				   }
	// 			   } else {
	// 				 $result['exception'] = 'Pago inexistente';
	// 			   }								
	// 			break;
	// 			case 'deleteLogic':
	// 				$_POST = $pagos->validateForm($_POST); 
	// 				if ($pagos->setId($_POST['id_pagoE'])) {
	// 				 if ($data = $pagos->readOnes()) {
	// 				   if (isset($_POST['estado'])) {
	// 					   if ($pagos->setEstado($_POST['estado'])) {										
	// 						   if ($pagos->deleteRow()) {
	// 							   $result['status'] = 1;
	// 							   $result['message'] = 'Pago Actualizado Correctamente';                                                        
	// 							 } else {
	// 								 $result['exception'] = Database::getException();                                                        
	// 							 }
	// 				   } else {
	// 					   $result['exception'] = 'Tipo inexistente';
	// 				   }
	// 		   } else {
	// 			 $result['exception'] = 'Inseleccionado inexistente';
	// 		   }	
	// 	   } else {
	// 		 $result['exception'] = 'Read One inexistente';
	// 	   }
	//    } else {
	// 	 $result['exception'] = 'Pago inexistente';
	//    }								
	//    break;
	//    case 'searchOneCount':
	// 	$_POST = $pagos->validateForm($_POST);
	// 	if ($_POST['nombre'] != '') {
	// 		if ($result['dataset'] = $pagos->searchOneCount($_POST['nombre'])) {
	// 			$result['status'] = 1;
	// 			$rows = count($result['dataset']);
	// 			if ($rows > 1) {
	// 				$result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
	// 			} else {
	// 				$result['message'] = 'Solo existe una coincidencia';
	// 			}
	// 		} else {
	// 			if (Database::getException()) {
	// 				$result['exception'] = Database::getException();
	// 			} else {
	// 				$result['exception'] = 'No hay coincidencias';
	// 			}
	// 		}
	// 	} else {
	// 		$result['exception'] = 'Ingrese un valor para buscar';
	// 	}
	// 	break;
	// 	case 'readOneCount':      
	// 	if ($pagos->setId($_POST['id_pagoSD'])) {                            
	// 		// if ($result['dataset'] = $pagos->readOneCount()) {
	// 		// 	$result['status'] = 1;                                
	// 		// } else {
	// 		// 	if (Database::getException()) {
	// 		// 		$result['exception'] = Database::getException();
	// 		// 	} else {
	// 		// 		$result['exception'] = 'Pago inexistente Orden';
	// 		// 	}
	// 		// }
	// 		$result['dataset'] = $pagos->readOneCount();  
	// 		$result['status'] = 1;                         
	// 	} else {
	// 		$result['exception'] = 'Pago incorrecto Orden';
	// 	}                    
	// 	break;
	// 	case 'suspenderPago':
	// 		if ($pagos->setId($_POST['id_pagoE'])) {
	// 			 if ($data = $pagos->readOnes()) {
	// 				   if ($pagos->suspenderPago()) {
	// 						$result['status'] = 1;									 
	// 					   $result['message'] = 'Pago Suspendido correctamente';									  
	// 				   } else {
	// 						$result['exception'] = Database::getException();
	// 				   }
	// 			 } else {
	// 				   $result['exception'] = 'Producto inexistente';
	// 			 }
	// 		} else {
	// 			 $result['exception'] = 'Producto incorrecto';
	// 		}
	// 		break;	
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

