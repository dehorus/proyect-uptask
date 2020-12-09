eventListeners();

function eventListeners(){
    document.querySelector('#formulario').addEventListener('submit', validarRegistro);
}


function validarRegistro(e){
    e.preventDefault();

    let user = document.querySelector('#usuario').value, 
        password = document.querySelector('#password').value,
        tipo = document.querySelector('#tipo').value;

    if(user === '' || password === ''){
        Swal.fire({
            type: 'error',
            title: 'Error! ',
            text: 'Se deven llenar todos los campos',
          })
    } else {
      //Ambos campos tienen algo, llamar a ajax
        let datos = new FormData();
        datos.append('usuario', user);
        datos.append('password', password);
        datos.append('accion', tipo);

        //crear llamado Ajax

        let xhr = new XMLHttpRequest();

        //abrir conexion

        xhr.open('POST', 'inc/modelos/modelo-admin.php', true);

        // retornar datos

        xhr.onload = function(){
            if(this.status === 200){
                let respuesta = JSON.parse(xhr.responseText);

                //SI respuesta es correcta
                console.log(respuesta);
                if(respuesta.respuesta === 'correcto') {
                    //SI es un nuevo usuario 
                    if(respuesta.tipo === 'crear'){
                        Swal.fire({
                            type: 'success',
                            title: 'Usuario Creado',
                            text: 'Usuario creado correctamente',
                          })
                    } else if(respuesta.tipo === 'login'){
                        Swal.fire({
                            type: 'success',
                            title: 'Login correcto',
                            text: 'Presiona Ok para continuar',
                          })

                          .then(result => {
                              if(result.value){
                                  window.location.href = 'index.php';
                              }
                          })
                    } 
                } else {
                    //error
                    Swal.fire({
                        type: 'error',
                        title: 'Error! ',
                        text: 'Hubo un error con si ingreso de datos',
                      })
                }
            }
        }

        //enviar peticion

        xhr.send(datos);
    }    
}