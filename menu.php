<!DOCTYPE html>

<head>
  <title>Menú</title>
  <meta charset="utf-8">
  <?php 
    include 'includes/css_menu.php';
    include 'includes/comprobacion_inicio_sesion.php'; 
    ?>
</head>

<body>
  <h1>MENU DE LA TIENDA</h1>
  <hr>
  <br><br><a href="stock.php">I.Ver stock</a><br>
  <br><a href="comprar.php">II.Comprar</a><br><br>
  <form method="post"
    action="http://www.misitio.com/practicas_AUTENTICACION_P1_UT4/practica_AUTENTICACION_crypt/login.php">
    <br><input type="submit" value="Cerrar sesion" name="cerrar">
  </form>
</body>

</html>