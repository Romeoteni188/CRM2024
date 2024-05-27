<?php
/*

  ____          _____               _ _           _       
 |  _ \        |  __ \             (_) |         | |      
 | |_) |_   _  | |__) |_ _ _ __ _____| |__  _   _| |_ ___ 
 |  _ <| | | | |  ___/ _` | '__|_  / | '_ \| | | | __/ _ \
 | |_) | |_| | | |  | (_| | |   / /| | |_) | |_| | ||  __/
 |____/ \__, | |_|   \__,_|_|  /___|_|_.__/ \__, |\__\___|
         __/ |                               __/ |        
        |___/                               |___/         
    
____________________________________
/ Si necesitas ayuda, contáctame en \
\ https://parzibyte.me               /
 ------------------------------------
        \   ^__^
         \  (oo)\_______
            (__)\       )\/\
                ||----w |
                ||     ||
Creado por Parzibyte (https://parzibyte.me).
------------------------------------------------------------------------------------------------

------------------------------------------------------------------------------------------------
*/ ?>
<?php

date_default_timezone_set("America/Mexico_City");

function obtenerDepartamentos()
{
    return [
        "Alta Verapaz"
        , "Baja Verapaz"
        , "Chimaltenango"
        , "Chiquimula"
        , "El Progreso"
        , "Escuintla"
        , "Guatemala"
        , "Huehuetenango"
        , "Izabal"
        , "Jalapa"
        , "Jutiapa"
        , "Petén"
        , "Quetzaltenango"
        , "Quiché"
        , "Retalhuleu"
        , "Sacatepéquez"
        , "San Marcos"
        , "Santa Rosa"
        , "Sololá"
        , "Suchitepéquez"
        , "Totonicapán"
        , "Zacapa"

    ];
}
function obtenerBD()
{
    // $password = ""; 
    // $user = "root";
    // $dbName = "crm";

    // $password = "4703";
    // $user = "root";
    // $dbName = "crm";
    // $servidor = "192.168.1.31";


    // $database = new PDO('mysql:host=localhost;dbname=' . $dbName, $user, $password, $servidor);
    // $database->query("set names utf8;");
    // $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    // $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    // return $database;


    $password = "4703";
    $user = "root";
    $dbName = "crm";
    $servidor = "192.168.1.31";

    try {
        // Construir el DSN (Data Source Name)
        $dsn = 'mysql:host=' . $servidor . ';dbname=' . $dbName;
        
        // Crear una nueva instancia de PDO
        $database = new PDO($dsn, $user, $password);
        
        // Establecer el charset a utf8
        $database->query("SET NAMES utf8;");
        
        // Configurar atributos de PDO
        $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        return $database;
    } catch (PDOException $e) {
        // Manejar error de conexión
        echo "Error en la conexión: " . $e->getMessage();
        return null;
    }


}

function agregarCliente($nombre, $edad, $departamento)
{
    $bd = obtenerBD();
    $fechaRegistro = date("Y-m-d");
    $sentencia = $bd->prepare("INSERT INTO clientes(nombre, edad, departamento, fecha_registro) VALUES (?, ?, ? ,?)");
    return $sentencia->execute([$nombre, $edad, $departamento, $fechaRegistro]);
}

function obtenerClientes()
{
    $bd = obtenerBD();
    $sentencia = $bd->query("SELECT id, nombre, edad, departamento, fecha_registro FROM clientes");
    return $sentencia->fetchAll();
}

function buscarClientes($nombre)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT id, nombre, edad, departamento, fecha_registro FROM clientes WHERE nombre LIKE ?");
    $sentencia->execute(["%$nombre%"]);
    return $sentencia->fetchAll();
}


function eliminarCliente($id)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("DELETE FROM clientes WHERE id = ?");
    return $sentencia->execute([$id]);
}

function obtenerClientePorId($id)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT id, nombre, edad, departamento, fecha_registro FROM clientes WHERE id = ?");
    $sentencia->execute([$id]);
    return $sentencia->fetchObject();
}
function actualizarCliente($nombre, $edad, $departamento, $id)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("UPDATE clientes SET nombre = ?, edad = ?, departamento = ? WHERE id = ?");
    return $sentencia->execute([$nombre, $edad, $departamento, $id]);
}

function agregarVenta($idCliente, $monto, $fecha)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("INSERT INTO ventas_clientes(id_cliente, monto, fecha) VALUES (?, ?, ?)");
    return $sentencia->execute([$idCliente, $monto, $fecha]);
}

function totalAcumuladoVentasPorCliente($idCliente)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes WHERE id_cliente = ?");
    $sentencia->execute([$idCliente]);
    return $sentencia->fetchObject()->total;
}

function totalAcumuladoVentasPorClienteEnUltimoMes($idCliente)
{
    $inicio = date("Y-m-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes WHERE id_cliente = ? AND fecha >= ?");
    $sentencia->execute([$idCliente, $inicio]);
    return $sentencia->fetchObject()->total;
}
function totalAcumuladoVentasPorClienteEnUltimoAnio($idCliente)
{
    $inicio = date("Y-01-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes WHERE id_cliente = ? AND fecha >= ?");
    $sentencia->execute([$idCliente, $inicio]);
    return $sentencia->fetchObject()->total;
}
function totalAcumuladoVentasPorClienteAntesDeUltimoAnio($idCliente)
{
    $inicio = date("Y-01-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes WHERE id_cliente = ? AND fecha < ?");
    $sentencia->execute([$idCliente, $inicio]);
    return $sentencia->fetchObject()->total;
}

function obtenerNumeroTotalClientes()
{
    $bd = obtenerBD();
    $sentencia = $bd->query("SELECT COUNT(*) AS conteo FROM clientes");
    return $sentencia->fetchObject()->conteo;
}
function obtenerNumeroTotalClientesUltimos30Dias()
{
    $hace30Dias = date("Y-m-d", strtotime("-30 day"));
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COUNT(*) AS conteo FROM clientes WHERE fecha_registro >= ?");
    $sentencia->execute([$hace30Dias]);
    return $sentencia->fetchObject()->conteo;
}

function obtenerNumeroTotalClientesUltimoAnio()
{
    $inicio = date("Y-01-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COUNT(*) AS conteo FROM clientes WHERE fecha_registro >= ?");
    $sentencia->execute([$inicio]);
    return $sentencia->fetchObject()->conteo;
}

function obtenerNumeroTotalClientesAniosAnteriores()
{
    $inicio = date("Y-01-01");
    $bd = obtenerBD();
    $sentencia = $bd->prepare("SELECT COUNT(*) AS conteo FROM clientes WHERE fecha_registro < ?");
    $sentencia->execute([$inicio]);
    return $sentencia->fetchObject()->conteo;
}

function obtenerTotalDeVentas()
{
    $bd = obtenerBD();
    $sentencia = $bd->query("SELECT COALESCE(SUM(monto), 0) AS total FROM ventas_clientes");
    return $sentencia->fetchObject()->total;
}

function obtenerClientesPorDepartamento()
{
    $bd = obtenerBD();
    $sentencia = $bd->query("SELECT departamento, COUNT(*) AS conteo FROM clientes GROUP BY departamento");
    return $sentencia->fetchAll();
}

function obtenerConteoClientesPorRangoDeEdad($inicio, $fin)
{
    $bd = obtenerBD();
    $sentencia = $bd->prepare("select count(*) AS conteo from clientes WHERE edad >= ? AND edad <= ?;");
    $sentencia->execute([$inicio, $fin]);
    return $sentencia->fetchObject()->conteo;
}

function obtenerVentasAnioActualOrganizadasPorMes()
{
    $bd = obtenerBD();
    $anio = date("Y");
    $sentencia = $bd->prepare("select MONTH(fecha) AS mes, COUNT(*) AS total from ventas_clientes WHERE YEAR(fecha) = ? GROUP BY MONTH(fecha);");
    $sentencia->execute([$anio]);
    return $sentencia->fetchAll();
}

function obtenerReporteClientesEdades()
{
    $rangos = [
        [1, 10],
        [11, 20],
        [20, 40],
        [40, 80],
    ];
    $resultados = [];
    foreach ($rangos as $rango) {
        $inicio = $rango[0];
        $fin = $rango[1];
        $conteo = obtenerConteoClientesPorRangoDeEdad($inicio, $fin);
        $dato = new stdClass;
        $dato->etiqueta = $inicio . " - " . $fin;
        $dato->valor = $conteo;
        array_push($resultados, $dato);
    }
    return $resultados;
}
