<?php 

$accion = $_POST['accion'];
$password = $_POST['password'];
$usuario = $_POST['usuario'];

if($accion === 'crear') {
    //Codigo para crear

    //hashear password

    $opciones = array(
        'cost' => 12
    );

    $hash_password = password_hash($password, PASSWORD_BCRYPT, $opciones);

    //importar conexion 

    include '../functions/conection.php';

    try {
        //realizar consulta
        $stmt = $conn->prepare("INSERT INTO usuarios (user, password) VALUES (?, ?) ");
        $stmt->bind_param('ss', $usuario, $hash_password);
        $stmt->execute();
        if($stmt->affected_rows){
            $respuesta = array(
            'respuesta'=> 'correcto',
            'id_insertado' => $stmt->insert_id,
            'tipo' => $accion 
            );
        } else {
            $respuesta = array(
                'respuesta' => 'error'
            );
        }    
        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        //En caso de que haya error

        $respuesta = array(
            'pass' => $e->getMessage()
        );

    }

 
    echo json_encode($respuesta);
}


if($accion === 'login') {
    //Escribir codigo para logear

    include '../functions/conection.php';

    try {

        //Seleccionar admin o usuario

        $stmt = $conn->prepare("SELECT user, id, password FROM usuarios WHERE user = ?");
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        // Loguear usuario

        $stmt->bind_result($nombre_usuario, $id_usuario, $password_usuario);
        $stmt->fetch();
        if($nombre_usuario){

            //Usuario exist, verificar password

            if(password_verify($password, $password_usuario)){
                //Iniciar sesion
                session_start();
                $_SESSION['nombre'] = $nombre_usuario;
                $_SESSION['id'] = $id_usuario;
                $_SESSION['login'] = true;
                //Login Correcto
                $respuesta = array(
                    'respuesta' => 'correcto',
                    'nombre' => $nombre_usuario,
                    'tipo' => $accion
                );
                } else {
                    //Login incorrecto
                    $respuesta = array(
                    'resultado' => 'password incorrecto'
                    );
                }
            
        } else {
            $respuesta = array(
                'error' => 'Usuario no existente'
            );
        }
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        //En caso de que haya error

        $respuesta = array(
            'pass' => $e->getMessage()
        );

    }

 
    echo json_encode($respuesta);
}

