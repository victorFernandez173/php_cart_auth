<!DOCTYPE html>
<html>

<head>
  <title>Autenticacion</title>
  <meta charset="utf-8">
  <?php include 'includes/mysql.php'; ?>
</head>

<body style="background: orange;">
  <h1 style="text-align:center;">PANTALLA DE LOGUEO</h1>
  <hr>
  <?php
	session_start();
	$_SESSION["credencialesCorrectas"] = false;
	if (isset($_POST["cerrar"])) {
		echo "Ha cerrado sesion <br><br>";
		session_unset();
	} else if (isset($_SESSION["sinLogueo"]) && $_SESSION["sinLogueo"] == true) {
		echo "Has sido redirigido. Logueate por favor: <br><br><br>";
		session_unset();
	} else if (isset($_POST["usuario"])) {
		// comprobamos la existencia del usuario en la base de datos
		// tambien que su contraseña hasheada con crypt() y el salt blowfish sea correcta
		$datosUsuario = $pdo->prepare("SELECT u.idUsuario, u.nombre, u.clave, d.telf FROM usuario u JOIN datosUsu d ON u.idUsuario = d.idDatosUsu where u.nombre = ?");
		$datosUsuario->execute([$_POST["usuario"]]);
		$resultadoUsuario = $datosUsuario->fetchAll();

		if(empty($resultadoUsuario)){
			echo "Usuario desconocido. Prueba otra vez: <br><br><br>";
		} else {
			$campo_salt = "$2y$07$" . $_POST["usuario"] . $resultadoUsuario[0]["telf"];
            if(strlen($campo_salt) < 29){
                $campo_salt = $campo_salt . $_POST["usuario"] . $resultadoUsuario[0]["telf"];
            }

			$crypt_blowfish = crypt($_POST["clave"], $campo_salt);
			if($crypt_blowfish == $resultadoUsuario[0]["clave"]){
				$_SESSION["credencialesCorrectas"] = true;
				header("Location: menu.php");
			}
			echo "Credenciales erroneas. Prueba otra vez: <br><br><br>";
		}
    }
	?>

  <form name="formulario" method="post" action="">
    <label for="idUsuario">Usuario: </label>
    <input id="idUsuario" type="text" name="usuario" required><br><br>
    <label for="idPassword">Clave: </label>
    <input id="idPassword" type="password" name="clave" required<br><br><br>
    <input type="submit" value="Acceder">
  </form>

  <p>No tienes usuario? Crea uno <a
      href="http://www.misitio.com/practicas_AUTENTICACION_P1_UT4/practica_AUTENTICACION_crypt/crear_usuario.php">aquí.</a>
  </p>
</body>

</html>