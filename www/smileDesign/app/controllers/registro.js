const API_LOGIN = '../app/api/registro.php?action=';

 document.getElementById('pass-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js    
    //---
    action = 'restorePassword';
    saveRow49(API_LOGIN, action ,'pass-form');
   
});