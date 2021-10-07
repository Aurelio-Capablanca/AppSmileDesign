const API_PAGOS = '../app/api/pago.php?action=';
const ENDPOINT_TIPO = '../app/api/pago.php?action=readAllTIPO';
const ENDPOINT_ESTADO = '../app/api/pago.php?action=readAllESTADO';


// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js 
    readRows(API_PAGOS);
});


function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `            
            <tr>                
                <td>${row.pagodebeh}</td>
                <td>${row.pagoabonoh}</td>
                <td>${row.pagototalh}</td>
                <td>${row.pagosaldoh}</td>                
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