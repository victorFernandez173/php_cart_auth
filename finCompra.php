<!DOCTYPE html>
<html lang="en">

<head>
    <title>Confirmacion de compra</title>
    <meta charset="utf-8">
    <?php
    include 'includes/comprobacion_inicio_sesion.php';
    include 'includes/css_ver_stock.php';
    include 'includes/mysql.php';
    ?>
</head>
<body>
    <h1>BIENVENIDO AL CARRITO</h1>
    <hr>
    <p>Revise la compra y confirme o vuelva al carrito:</p>

    <?php
    // bloque para actualizar la base de datos si la compra ha sido confirmada y el carrito no esta vacío
    // se recorren las filas de carrito identificando cada producto
    // y actualizando en consecuencia el stock de dicho producto en concreto en la base de datos
    // para ello extraigo de la descripcion del producto, su ID y lo utilizo en el update en mysql
    if (isset($_POST["compra_confirmada"]) && isset($_SESSION["carritoProvisional"][0])){
        $compraFallida = false;
        $sinExistencias = array();
        $existenciasRestantes = array();
        for ($i = 0; $i < count($_SESSION["carritoProvisional"]); $i++) {
            $posicion = strpos($_SESSION["carritoProvisional"][$i]["producto"], "-");
            $id = substr($_SESSION["carritoProvisional"][$i]["producto"], 0, $posicion);
            $unidades = $_SESSION["carritoProvisional"][$i]["unidades"];

            // comprobamos individualmente el stock de los productos
            $comprobacion = "select unidades from stock where idProducto = $id";
            $resultadoComprobacion = $pdo->query($comprobacion);
            $unidadesEnStock = $resultadoComprobacion->fetch();
            if($unidades > $unidadesEnStock[0]){
                array_push($sinExistencias, $_SESSION["carritoProvisional"][$i]["producto"]);
                array_push($existenciasRestantes, $unidadesEnStock[0]);
                $compraFallida = true;
            }
        }
        // si hay stock suficiente de todo aplico la actualización a la bbdd
        if($compraFallida == false) {
            for($i = 0; $i < count($_SESSION["carritoProvisional"]); $i++){
                $posicion = strpos($_SESSION["carritoProvisional"][$i]["producto"], "-");
                $id = substr($_SESSION["carritoProvisional"][$i]["producto"], 0, $posicion);
                $unidades = $_SESSION["carritoProvisional"][$i]["unidades"];
                $consulta_actualizacion = "UPDATE stock SET unidades = (unidades - $unidades) where idProducto = $id";
                $stmnt = $pdo->prepare($consulta_actualizacion);
                $stmnt->execute();
            }
            unset($_SESSION["carritoProvisional"]);
            echo "<center>GRACIAS POR SU COMPRA</center>";
        // si falta de algun producto, se avisa de cuales y se muestran cantidades para que se reajuste
        } else {
            echo "<p>No quedan suficientes unidades en stock de uno o varios productos, vuelva al carrito y reajuste el numero de unidades:</p>";
            echo "<ul>";
            for($i = 0; $i <count($sinExistencias); $i++){
                echo "<li>" . $sinExistencias[$i] . ". Unidades restantes en stock: " . $existenciasRestantes[$i] . "</li>";
            }
            echo "</ul>";
        }
    }
// si la variable $_SESSION["carritoProvisional"] ha sido creada y tiene algun contenido
// imprimo el contenido del carrito y además voy calculando los subtotales y el total 
    if(isset($_SESSION["carritoProvisional"]) && count($_SESSION["carritoProvisional"]) >= 1){
        $total = 0;
        echo "<table>";
        for ($i = 0; $i < count($_SESSION["carritoProvisional"]); $i++) {
            $posicion = strrpos($_SESSION["carritoProvisional"][$i]["producto"], ">");
            $precio = substr($_SESSION["carritoProvisional"][$i]["producto"], $posicion + 1);
            echo "<tr><td>" . $_SESSION["carritoProvisional"][$i]["producto"] . "</td><td>Unidades: " . $_SESSION["carritoProvisional"][$i]["unidades"] . "</td><td>Subtotal: " . $_SESSION["carritoProvisional"][$i]["unidades"] * (float)$precio . "</td></tr>";
            $total +=  ($_SESSION["carritoProvisional"][$i]["unidades"] * (float)$precio);
        }
        echo "<tr><td colspan=\"3\">TOTAL =  $total €</td></tr>";
        echo "</table>";
    } else {
        echo "<center>Carrito de la compra vacío</center>";
    }
    // a continuación formulario de compra confirmada que sirve para actualizar la base de datos
    //  ya que manda la info para confirmar que se aplique la compra
    ?>
    <form method="post" action="">
        <br><input type="submit" value="CONFIRMAR COMPRA" name="compra_confirmada">
    </form>

    <hr><br><br><a href="comprar.php">I.Volver al carrito</a><br>
    <br><a href="menu.php">II.Volver al menú</a><br>

    <form method="post" action="http://www.misitio.com/practica_AUTENTICACION_crypt/login.php">
        <br><input type="submit" value="Cerrar sesion" name="cerrar">
    </form>
</body>

</html>