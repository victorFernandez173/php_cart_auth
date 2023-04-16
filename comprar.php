<!DOCTYPE html>
<html lang="en">

<head>
  <title>Pagina de compra</title>
  <meta charset="utf-8">
  <?php
    include 'includes/comprobacion_inicio_sesion.php';
    include 'includes/css_ver_stock.php';
    include 'includes/mysql.php';
    // mi idea ha sido hacer un carrito estilo interactivo 
    // en el puedes quitar/añadir/actualizar productos en tiempo real
    //  de todos los productos disponibles en la basa de datos
    ?>
</head>

<body>
  <h1>CARRITO DE LA COMPRA</h1>
  <hr>
  <p>Añada/modifique los productos y cantidades de su carrito:</p>
  <form method="post" action="">
    <label for="idProducto">Producto: </label>
    <select name="producto" id="idProducto">

      <?php
        // formulario para añadir productos al carrito
        // en este bloque de php, genero el listado option que ofrece todos los productos para añadir producto a la lista;
        $resultado = $pdo->query($consulta_generar_option);
        $lista_productos = $resultado->fetchAll();
        foreach ($lista_productos as $producto) {
            echo "<option value=\"$producto[1]\">$producto[1]</option>";
        }
        // creamos la variable de sesion para almacenar los valores del carrito
        if (!isset($_SESSION["carritoProvisional"][0])) {
            $_SESSION["carritoProvisional"] = array();
        }
        ?>

    </select>
    <label for="idCantidad">&nbsp; &nbsp; Cantidad</label>
    <input id="idCantidad" name="cantidad" type="number" min="0" value="0">
    <input type="submit" value="añadir al carrito" name="añadir"><br><br>
  </form>

  <p>Resumen de su carrito:</p>

  <?php
    // bloques de php para el procesado del carrito (quitar, añadir, actualizar) productos
    if (array_key_exists("añadir", $_POST)) {
        // se crea una variable con el objeto a añadir
        $objetoAñadir = array('producto' => $_POST["producto"], 'unidades' => $_POST["cantidad"]);
        $productoEnElCarrito = false;

        // si el carrito está vacío y hemos puesto una cantidad mayor que 0, se añade producto
        if (!isset($_SESSION["carritoProvisional"][0]) && isset($_POST["cantidad"]) && $_POST["cantidad"] != 0) {
            array_push($_SESSION["carritoProvisional"], $objetoAñadir);
        // pero si ya hay productos en el carrito, hay que comprobar si estamos actualizando uno existente o añadiendo
        } else if (isset($_SESSION["carritoProvisional"][0]) && isset($_POST)) {
            // si el nombre del producto en el formulario, está en el carrito, ya tenemos ese producto $productoEnElCarrito = true;
            for ($i = 0; $i < count($_SESSION["carritoProvisional"]); $i++) {
                $productoLeidoCarritoProvisional = $_SESSION["carritoProvisional"][$i];
                if ($productoLeidoCarritoProvisional["producto"] == $_POST["producto"]) {
                    $productoEnElCarrito = true;
                }
            }
            // si el producto ya esta en el carrito procesamos uno a uno los productos ya existentes en el carrito con el for
            // para decidir que hacer, si añadirlo sin más, o modificar la cantidad que ya tenía
            if ($productoEnElCarrito) {
                for ($i = 0; $i < count($_SESSION["carritoProvisional"]); $i++) {
                    $productoLeidoCarritoProvisional = $_SESSION["carritoProvisional"][$i];
                    // si el producto a añadir coincide con uno de los del carrito reasignamos a dicho producto la nueva cantidad
                    if ($productoLeidoCarritoProvisional["producto"] == $_POST["producto"]) {
                        $_SESSION["carritoProvisional"][$i] = array('producto' => $_POST["producto"], 'unidades' => $_POST["cantidad"]);
                        // pero si la cantidad es cero, lo eliminamos del carrito
                        if ($_POST["cantidad"] == 0) {
                            array_splice($_SESSION["carritoProvisional"], $i, 1);
                        }
                    }
                }
            } else {
                // si el producto no existia todavía en el carrito y la cantidad es mayor que 0, lo añadimos
                if ($_POST["cantidad"] != 0) {
                    array_push($_SESSION["carritoProvisional"], $objetoAñadir);
                }
            }
        }
    }
    // bloque final de php para mostrar todos los productos añadidos al carrito SI LOS HUBIERA, 
    // recorremos la variable $_SESSION["carritoProvisional"] y vamos imprimiendo la tabla
    if (isset($_SESSION["carritoProvisional"])) {
        echo "<table>";
        for ($i = 0; $i < count($_SESSION["carritoProvisional"]); $i++) {
            $posicion = strrpos($_SESSION["carritoProvisional"][$i]["producto"], ">");
            $precio = substr($_SESSION["carritoProvisional"][$i]["producto"], $posicion + 1);
            echo "<tr><td>" . $_SESSION["carritoProvisional"][$i]["producto"] . "</td><td>Unidades: " . $_SESSION["carritoProvisional"][$i]["unidades"] . "</tr>";
        }
        echo "</table>";
        // mensaje de carrito vacio
        if(count($_SESSION["carritoProvisional"]) < 1){
            echo "<center>Carrito de la compra vacío</center>";
        }
    } 
    ?>

  <hr><br><br><a href="finCompra.php">I.Confirmacion de compra</a><br>
  <br><a href="menu.php">II.Volver a menu</a><br><br>

  <form method="post"
    action="http://www.misitio.com/practicas_AUTENTICACION_P1_UT4/practica_AUTENTICACION_crypt/login.php">
    <br><input type="submit" value="Cerrar sesion" name="cerrar">
  </form>
</body>

</html>