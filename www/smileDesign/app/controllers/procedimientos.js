const API_CONSULTA = '../app/api/agenda.php?action=';

document.addEventListener('DOMContentLoaded', function () {
    //Se declaran las variables necesarias para inicializar los componentes del framework   
    readRowsProcedimientos(API_CONSULTA);
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
                    <p>${row.nombreprocedimiento}</p>
                    <br>                    
                    <p>${row.descripcionprocedimiento}</p>
                    <br>
                    <p>Fecha: ${row.fechaconsulta}</p>
                    <br>
                    <i class="material-icons right">more_vert</i>
                </span>                 
            </div>
            <div class="card-reveal">
                <span class="card-title grey-text text-darken-4">
                    <p>N# Consulta: ${row.consulta}</p>
                    <p>Nombre Doctor: ${row.nombredoctor} </p> 
                    <i class="material-icons right">close</i>
                </span>                
            </div>
        </div>
    </div>
    `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('procedimientosag').innerHTML = content;
    // Se inicializa el componente Material Box asignado a las imagenes para que funcione el efecto Lightbox.
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    // Se inicializa el componente Tooltip asignado a los enlaces para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}
