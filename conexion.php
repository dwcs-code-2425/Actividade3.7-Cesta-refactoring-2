<?php



function readIniFile($file = "db_settings.ini"): array
{
    //https://www.php.net/manual/es/function.parse-ini-file.php
    //carga el fichero ini especificado en $file, y devuelve las configuraciones que hay en él a un array asociativo $settings 
    //o false si hay algún error y no consigue leer el fichero. 
    if (!$settings = parse_ini_file($file, TRUE))
        throw new exception('Unable to open ' . $file . '.');
    return $settings;
}

function getConnection(): PDO
{
    //leemos datos del ini file en un array asociativo
    $settings = readIniFile();

    //Creamos cadena de conexión concatenando
    $dsn = $settings['database']['driver'] .
        ':host=' . $settings['database']['host'] .
        ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
        ';dbname=' . $settings['database']['schema'];

    //Creamos el objeto PDO
    $conn = new PDO($dsn, $settings['database']['username'], $settings['database']['password']);
    return $conn;
}






$conProyecto = getConnection();

// $host = "localhost";
// $db = "proyecto";
// $user = "gestor";
// $pass = "secreto";
// $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
// try {
//     $conProyecto = new PDO($dsn, $user, $pass);
//     $conProyecto->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $ex) {
//     die("Error en la conexión: mensaje: " . $ex->getMessage());
// }
function consultarProducto($id)
{
    global $conProyecto;
    $consulta = "select * from productos where id=:i";
    $stmt1 = $conProyecto->prepare($consulta);
    try {
        $stmt1->execute([':i' => $id]);
    } catch (PDOException $ex) {
        die("Error al recuperar Productos: " . $ex->getMessage());
    }
    //esta consulta solo devuelve una fila es innecesario el while para recorrerla
    $producto = $stmt1->fetch(PDO::FETCH_ASSOC);
    $stmt1 = null;
    return $producto;
}
function cerrar(&$con)
{
    $con = null;
}
function cerrarTodo(&$con, &$st)
{
    $st = null;
    $con = null;
}

function getProductosByFamilia(string $familia): array|null
{
    try {
        global $conProyecto;
        $sql = "SELECT * FROM productos WHERE familia = :familia";
        $stmt = $conProyecto->prepare($sql);
        $stmt->bindParam("familia", $familia);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }
    catch(Exception $ex){
        error_log("Non se puideron recuperar os produtos da familia $familia ." . $ex->getTraceAsString());
        return null;
    }
}
