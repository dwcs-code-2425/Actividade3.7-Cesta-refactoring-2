<?php



function cerrarSesion(){
    //Tal y como se recomienda en https://www.php.net/manual/es/function.session-destroy.php
      iniciarSesion();
  
    //Vaciamos el array
    $_SESSION = array();
  
    if (ini_get("session.use_cookies")) {
        //obtenemos los parámetros de creación de la cookie de sesión
        $params = session_get_cookie_params();
        //borramos la cookie de sesión
        setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
        );
    }
    //Eliminamos los datos relacionados con la sesión en el almacenamiento servidor 
    session_destroy();
  
    //Eliminamos la cookie de noMostrar
      setcookie("noMostrar", "", time() - 1000);
  
  }

  function iniciarSesion(): bool {
    $iniciada = true;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $iniciada = session_start();
    }
    return $iniciada;
}

