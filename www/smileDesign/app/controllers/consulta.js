const API_CONSULTAS = '../app/api/consulta.php?action=';
const ENDPOINT_CAUSA = '../app/api/consulta.php?action=readAllCAUSA';
const ENDPOINT_PROCEDIMIENTO = '../app/api/consulta.php?action=readAllPROCEDIMIENTO';


// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js 
    readRows(API_CONSULTAS);
});


// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {                
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <tr>
                <td>${row.nombrepaciente}</td> 
                <td>${row.fechaconsulta}</td>                
                <td>${row.horaconsulta}</td>                 
                <td>${row.causa}</td>                 
                <td>
                   <ul> 
                    <li><a href="#" onclick="openUpdateDialog(${row.idconsulta})" class="btn waves-effect blue tooltipped" data-tooltip="Actualizar"><i class="material-icons">mode_edit</i></a></li>
                    <br>
                    <li><a href="#" onclick="openDeleteDialog(${row.idconsulta})" class="btn waves-effect red tooltipped" data-tooltip="Eliminar"><i class="material-icons">delete</i></a></li>                                       
                    </ul>
                </td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('tbody-rows').innerHTML = content;
    // Se inicializa el componente Material Box asignado a las imagenes para que funcione el efecto Lightbox.
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    // Se inicializa el componente Tooltip asignado a los enlaces para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

document.getElementById('search-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_CONSULTAS, 'search-form');
});

function openUpdateDialog(id) {
    // Se restauran los elementos del formulario.
    document.getElementById('save-form').reset();
    // Se abre la caja de dialogo (modal) que contiene el formulario.
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    instance.open();
    // Se asigna el título para la caja de dialogo (modal).
    document.getElementById('modal-title').textContent = 'Actualizar Información del Paciente';        
    // Se define un objeto con los datos del registro seleccionado.   
    document.getElementById('fecha_consulta').required = false;

    const data = new FormData();
    data.append('id_consulta', id);

    fetch(API_CONSULTAS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById('id_consulta').value = response.dataset.idconsulta;                    
                    document.getElementById('fecha_consulta').value = response.dataset.fechaconsulta;
                    document.getElementById('hora_consulta').value = response.dataset.horaconsulta;
                    fillSelect(ENDPOINT_CAUSA, 'causa_consulta', response.dataset.idcausaconsulta);                    
                    // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
                    M.updateTextFields();                    
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

document.getElementById('save-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    let action = '';
    // Se comprueba si el campo oculto del formulario esta seteado para actualizar, de lo contrario será para crear.
        if (document.getElementById('id_consulta').value) {
            action = 'update';
        } else {
            action = 'create';
        }        
   
    saveRow(API_CONSULTAS, action, 'save-form', 'save-modal');
});

function openCreateDialog() {
    // Se restauran los elementos del formulario.
    document.getElementById('save-form').reset();
    // Se abre la caja de dialogo (modal) que contiene el formulario.
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    instance.open();
    // Se asigna el título para la caja de dialogo (modal).
    document.getElementById('modal-title').textContent = 'Ingresar Consulta';
    // Se llama a la función para llenar el select del estado cliente         
    fillSelect(ENDPOINT_CAUSA, 'causa_consulta', null);    
}
