<?php
// include con las credenciales, conexion y consultas genericas a mysql
    $user = "xxxxxx";
    $password = "Xxxxxx123%";
    $database = "almacen";
    
    $pdo = new PDO("mysql:host=localhost;dbname=$database", $user, $password);

    $consulta_completa = "SELECT p.idProducto, descripcion, precio, talla, tipo, unidades From producto p join stock s ON p.idProducto = s.idProducto;";
    
    $consulta_generar_option = "select idProducto, concat(idProducto, \"-\", tipo, \"-\", descripcion, \"(\", talla, \") €->\", precio)producto, precio from producto;";

    $consulta_mod_stock = "SELECT * FROM stock";
?>