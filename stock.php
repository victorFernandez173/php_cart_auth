<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ver stock</title>
    <meta charset="utf-8">
    <?php
    include 'includes/comprobacion_inicio_sesion.php';
    include 'includes/css_ver_stock.php';
    include 'includes/mysql.php'
    ?>
</head>

<body>
    <h1>BIENVENIDO AL STOCK</h1>
    <hr>
    <p>Su stock:</p>
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
        foreach ($pdo->query($consulta_completa) as $row) {
            echo "<tr><td>" . $row['idProducto'] . "</td>" . "<td>" . $row['descripcion'] . "</td>" . "<td>" . $row['precio'] . "</td>" .  "<td>" . $row['talla'] . "</td>" .  "<td>" . $row['tipo'] . "</td>" .  "<td>" . $row['unidades'] . "</td>" .  "</tr>";
        }
        ?>
    </table>
    <hr><br><br><a href="modStock.php">I.Modificar stock</a><br>
    <br><a href="menu.php">II.Volver a menu</a><br><br>

    <form method="post" action="http://www.misitio.com/practica_AUTENTICACION_crypt/login.php">
        <br><input type="submit" value="Cerrar sesion" name="cerrar">
    </form>
</body>

</html>