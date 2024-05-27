<?php
$usuario  = "root";
$password = "4703";
$servidor = "192.168.1.31";
$basededatos = "crm";
$con = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al Servidor");
$db = mysqli_select_db($con, $basededatos) or die("Upps! Error en conectar a la Base de Datos");
?>

