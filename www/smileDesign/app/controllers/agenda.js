const API_CONSULTA = '../app/api/agenda.php?action=';

document.addEventListener('DOMContentLoaded', function () {
    //Se declaran las variables necesarias para inicializar los componentes del framework   
    readRowsAgenda(API_CONSULTA);
});

function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
        <div class="col s12 m6 l4">
        <div class="card">                                    
            <div class="card-content">
                <span class="card-title activator grey-text text-darken-4">                                            
                    <p>${row.nombrepaciente}</p>
                    <br>
                    <p>Día: ${row.fechaconsultas}</p>
                    <br>
                    <p>Fecha: ${row.fechaconsulta}</p>
                    <br>
                    <p>Hora: ${row.horaconsulta}</p>
                    <br>
                    <p>${row.causa}</p>
                    <br>
                    <i class="material-icons right">more_vert</i>
                </span>
                <p class="center">
                <a href="#" onclick="openProcedures(${row.idconsulta})" class="btn waves-effect grey tooltipped" data-tooltip="Buscar Procedimientos"><i class="material-icons">search</i></a>                                            
                </p>
            </div>
            <div class="card-reveal">
                <span class="card-title grey-text text-darken-4">
                    <p>N# Consulta: ${row.idcantidadconsulta}</p>
                    <p>Nombre Doctor: ${row.nombredoctor} </p> 
                    <i class="material-icons right">close</i>
                </span>                
            </div>
        </div>
    </div>
    `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('consulta').innerHTML = content;
    // Se inicializa el componente Material Box asignado a las imagenes para que funcione el efecto Lightbox.
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    // Se inicializa el componente Tooltip asignado a los enlaces para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}


// function readAllConsultas() {
//     fetch(API_CONSULTA + 'readAllAgenda', {
//         method: 'get'
//     }).then(function (request) {
//         // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
//         if (request.ok) {
//             request.json().then(function (response) {
//                 // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
//                 if (response.status) {
//                     let content = '';
//                     let url = '';
//                     // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
//                     response.dataset.map(function (row) {
//                         // Se define una dirección con los datos de cada categoría para mostrar sus productos en otra página web.
//                         //url = `productos.php?id=${row.idtipoproducto}&nombre=${row.tipoproducto}`;
//                         // Se crean y concatenan las tarjetas con los datos de cada categoría.
//                         content += `
//                             <div class="col s12 m6 l4">
//                                 <div class="card">                                    
//                                     <div class="card-content">
//                                         <span class="card-title activator grey-text text-darken-4">                                            
//                                             ${row.nombrepaciente}
//                                             <p>Día: </p><p>${row.fechaconsultas}</p>
//                                             <p>${row.fechaconsulta}</p>
//                                             <p>${row.horaconsulta}</p>
//                                             <p>${row.causa}</p>
//                                             <i class="material-icons right">more_vert</i>
//                                         </span>
//                                         <p class="center">
//                                         <a href="#" onclick="openProcedures(${row.idconsulta})" class="btn waves-effect grey tooltipped" data-tooltip="Buscar Procedimientos"><i class="material-icons">search</i></a>                                            
//                                         </p>
//                                     </div>
//                                     <div class="card-reveal">
//                                         <span class="card-title grey-text text-darken-4">
//                                             <p>N# Consulta: </p>${row.idconsulta}
//                                             <i class="material-icons right">close</i>
//                                         </span>
//                                         <!-- <p>${row.fechaconsulta}</p> -->
//                                        <!-- <p>${row.horaconsulta}</p> -->
//                                        <!-- <p>${row.causa}</p> -->
//                                     </div>
//                                 </div>
//                             </div>
//                         `;
//                     });
//                     // Se agregan las tarjetas a la etiqueta div mediante su id para mostrar las categorías.
//                     document.getElementById('consulta').innerHTML = content;
//                     // Se inicializa el componente Tooltip asignado a los enlaces para que funcionen las sugerencias textuales.
//                     M.Tooltip.init(document.querySelectorAll('.tooltipped'));
//                 } else {
//                     // Se presenta un mensaje de error cuando no existen datos para mostrar.
//                     document.getElementById('title').innerHTML = `<i class="material-icons small">cloud_off</i><span class="red-text">${response.exception}</span>`;
//                 }
//             });
//         } else {
//             console.log(request.status + ' ' + request.statusText);
//         }
//     }).catch(function (error) {
//         console.log(error);
//     });
// }


function fillTables(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `                           
            <tr>
                <td>${row.horaconsulta}</td>
                <td>${row.nombreprocedimiento}</td>
                <td>${row.descripcionprocedimiento}</td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('proced-rows').innerHTML = content;
    // Se inicializa el componente Material Box asignado a las imagenes para que funcione el efecto Lightbox.
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    // Se inicializa el componente Tooltip asignado a los enlaces para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

function openProcedures(id) {
    // Se restauran los elementos del formulario.
    document.getElementById('show-a-form').reset();
    // Se abre la caja de dialogo (modal) que contiene el formulario.
    let instance = M.Modal.getInstance(document.getElementById('show-proced-modal'));
    instance.open();
    // Se asigna el título para la caja de dialogo (modal).
    document.getElementById('modal-proced-title').textContent = 'Procedimientos';
    // Se deshabilitan los campos de alias y contraseña.    
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_consultaP', id);

    fetch(API_CONSULTA + 'readOneProcedure', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.                
                if (response.status) {
                    document.getElementById('id_consultaP').value = response.dataset.idconsulta;
                    document.getElementById('notas_consultaP').value = response.dataset.notasconsulta;
                    searchRows1(API_CONSULTA, 'show-a-form');

                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// document.getElementById('search-form').addEventListener('submit', function (event) {
//     // Se evita recargar la página web después de enviar el formulario.
//     event.preventDefault();
//     // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
//     searchAgendas(API_CONSULTA, 'search-form');
// });