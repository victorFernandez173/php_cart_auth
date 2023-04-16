<!DOCTYPE html>
<html lang="en">

<head>
  <title>Modificar stock</title>
  <meta charset="utf-8">
  <?php 
    include 'includes/comprobacion_inicio_sesion.php';
    include 'includes/css_modificar_stock.php';
    include 'includes/mysql.php';
    ?>
</head>

<body>
  <?php
    // bloque para eliminar producto de la tienda
    if (isset($_POST["prod_eliminar"]) && strlen($_POST["prod_eliminar"]) > 0){
        $id = (int)$_POST["prod_eliminar"];
        $buscar = "SELECT idProducto FROM producto WHERE idProducto = $id";
        $resultadoConsulta = $pdo->query($buscar);
        $idEncontrado = $resultadoConsulta->fetch();
        if($idEncontrado[0] != $id){
            echo '<script>alert("No existe ese producto")</script>';
        } else {
            $borrar = "DELETE FROM producto WHERE idProducto = $id;";
            $stmnt = $pdo->prepare($borrar);
            $stmnt->execute();
            echo '<script>alert("Ha borrado el producto")</script>';
        }
    } 
    // bloque para añadir un nuevo producto a la base de datos
    if (isset($_POST["descripcion"]) && strlen($_POST["descripcion"]) > 0) {
        $nuevo = "INSERT INTO producto (descripcion, precio, talla, tipo) VALUES (?, ?, ?, ?);";
        $stmnt = $pdo->prepare($nuevo);
        $stmnt->execute([$_POST["descripcion"], $_POST["precio"], $_POST["talla"], $_POST["tipo"]]);

        $resultado = $pdo->query("select idProducto from producto order by idProducto desc limit 1;");
        $ultimo = $resultado->fetch();

        $nuevo2 = "INSERT INTO stock (idProducto, unidades) VALUES (?, ?);";
        $stmnt = $pdo->prepare($nuevo2);
        $stmnt->execute([(int)$ultimo[0], 0]);
        echo '<script>alert("Producto añadido con exito")</script>';
    }
    // bloque que realiza la actualización del stock si se ha enviado el formulario para modificar
    if (isset($_POST["modificar"])) {
        foreach ($_POST as $idProducto => $unidades) {
            if ($unidades != "Confirmar modificacion" && (int)$unidades != 0) {
                $consulta_actualizacion = "UPDATE stock SET unidades=$unidades where idProducto=$idProducto";
                $stmnt = $pdo->prepare($consulta_actualizacion);
                $stmnt->execute();
            }
        }
    }
    ?>

  <h1>MODIFICACION DE STOCK</h1>
  <hr>
  <p>Actualice su stock quitando o añadiendo unidades por producto y confirme modificación:</p>
  <form method="post" action="">
    <table>
      <tr id="cabecera">
        <td>ID</td>
        <td>DESC</td>
        <td>€</td>
        <td>TALLA</td>
        <td>TIPO</td>
        <td>UDS</td>
      </tr>
      <?php
            // imprimos la tabla para la visualización del stock como en la pantalla de ver stock
            $resultado = $pdo->query($consulta_completa);
            $registros = $resultado->fetchAll();
            foreach ($registros as $row) {
                echo "<tr><td>" . $row['idProducto'] . "</td>" . "<td>" . $row['descripcion'] . "</td>" . "<td>" . $row['precio'] . "</td>" .  "<td>" . $row['talla'] . "</td>" .  "<td>" . $row['tipo'] . "</td>" .  "<td>" . $row['unidades'] . " <input type=\"number\" min=\"0\" max=\"999\" name=\"" . $row['idProducto'] . "\"value=\"" . $row['unidades'] . "\"" . "></td>" .  "</tr>";
            }
            ?>
    </table>
    <br><input id="confirmacion" type="submit" value="Confirmar modificacion" name="modificar">
  </form>
  <hr><br>
  <!-- por ultimo a continuación tenemos los formularios para añadir/quitar -->
  <p>Añadir nuevo modelo de producto a la tienda:</p>
  <form method="post" action="">
    <label for="idDesc">Descripcion:</label><input type="text" id="idDesc" name="descripcion" maxlength="70" required>
    <br><br><label for="idPre">Precio(xxx.xx)€:</label><input type="text" id="idPre" name="precio"
      pattern="[0-9]{1,3}[.]{1,1}[0-9]{1,2}" required>
    <br><br><label for="idTalla">Talla(S, M ó L):</label><input type="text" id="idTalla" name="talla" maxlength="1"
      minlength="1" pattern="[SML]" required>
    <br><br><label for="idTipo">TIPO(CAMI ó PANT):</label><input type="text" id="idTipo" name="tipo" pattern="CAMI|PANT"
      required>
    <br><br><input type="submit" value="Añadir producto" name="nuevoProducto">
  </form>
  <hr><br>
  <p>Eliminar modelo de producto de la tienda:</p>
  <form method="post" action="">
    <label for="idDesc">Id del producto:</label><input type="text" id="idDesc" name="prod_eliminar" pattern="[0-9]{1,3}"
      required>
    <br><br><input type="submit" value="Eliminar producto" name="eliminar">
  </form>
  <hr><br><br><a href="stock.php">I.Resumen de stock</a><br>
  <br><a href="menu.php">II.Volver a menu</a><br><br>
  <form method="post"
    action="http://www.misitio.com/practicas_AUTENTICACION_P1_UT4/practica_AUTENTICACION_crypt/login.php">
    <input type="submit" value="Cerrar sesion" name="cerrar">
  </form>
</body>

</html>